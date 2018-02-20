<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\Common\Domain\Transaction\TransactionManager;
use Damax\User\Application\Command\ConfirmEmail;
use Damax\User\Application\Command\RequestEmailConfirmation;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Domain\Model\ActionRequest;
use Damax\User\Domain\Model\ActionRequestRepository;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Domain\TokenGenerator\TokenGenerator;

class ConfirmationService
{
    use UserServiceTrait;

    private $requests;
    private $tokenGenerator;
    private $transactionManager;

    public function __construct(UserRepository $users, ActionRequestRepository $requests, TokenGenerator $tokenGenerator, TransactionManager $transactionManager)
    {
        $this->users = $users;
        $this->requests = $requests;
        $this->tokenGenerator = $tokenGenerator;
        $this->transactionManager = $transactionManager;
    }

    public function requestEmailConfirmation(RequestEmailConfirmation $command): void
    {
        $user = $this->getUser($command->userId);

        if ($user->email()->confirmed()) {
            return;
        }

        $this->requests->save(ActionRequest::emailConfirmation($this->tokenGenerator, $user));
    }

    /**
     * @throws ActionRequestNotFound
     * @throws ActionRequestExpired
     */
    public function confirmEmail(ConfirmEmail $command): void
    {
        if (null === $request = $this->requests->byToken($command->token)) {
            throw ActionRequestNotFound::byToken($command->token);
        }

        if (!$request->activeEmailConfirmation()) {
            throw ActionRequestExpired::withToken($command->token);
        }

        $request->user()->confirmEmail();

        $this->transactionManager->run(function () use ($request) {
            $this->users->save($request->user());
            $this->requests->remove($request);
        });
    }
}

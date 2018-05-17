<?php

declare(strict_types=1);

namespace Damax\User\Application\Service;

use Damax\Common\Domain\Transaction\TransactionManager;
use Damax\User\Application\Command\ChangePassword;
use Damax\User\Application\Command\RequestPasswordReset;
use Damax\User\Application\Command\ResetPassword;
use Damax\User\Application\Dto\PasswordResetDto;
use Damax\User\Application\Dto\PasswordResetRequestDto;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Domain\Model\ActionRequest;
use Damax\User\Domain\Model\ActionRequestRepository;
use Damax\User\Domain\Model\UserRepository;
use Damax\User\Domain\Password\Encoder;
use Damax\User\Domain\TokenGenerator\TokenGenerator;

class PasswordService
{
    use UserServiceTrait;

    private $encoder;
    private $requests;
    private $tokenGenerator;
    private $transactionManager;

    public function __construct(
        UserRepository $users,
        Encoder $encoder,
        ActionRequestRepository $requests,
        TokenGenerator $tokenGenerator,
        TransactionManager $transactionManager
    ) {
        $this->users = $users;
        $this->encoder = $encoder;
        $this->requests = $requests;
        $this->tokenGenerator = $tokenGenerator;
        $this->transactionManager = $transactionManager;
    }

    public function changePassword(ChangePassword $command): void
    {
        $user = $this->getUser($command->userId);
        $user->changePassword($this->encoder->encode($command->password));

        $this->users->save($user);
    }

    /**
     * @throws UserNotFound
     */
    public function requestPasswordReset(PasswordResetRequestDto $request): void
    {
        $user = $this->getUser($request->userId);

        $this->requests->save(ActionRequest::resetPassword($this->tokenGenerator, $user));
    }

    /**
     * @throws ActionRequestNotFound
     * @throws ActionRequestExpired
     */
    public function resetPassword(PasswordResetDto $reset): void
    {
        if (null === $request = $this->requests->byToken($reset->token)) {
            throw ActionRequestNotFound::byToken($reset->token);
        }

        if (!$request->activePasswordReset()) {
            throw ActionRequestExpired::withToken($reset->token);
        }

        $user = $request->user();
        $user->changePassword($this->encoder->encode($reset->newPassword));

        $this->transactionManager->run(function () use ($user, $request) {
            $this->users->save($user);
            $this->requests->remove($request);
        });
    }

    public function hasActiveResetRequest(string $token): bool
    {
        if (null === $request = $this->requests->byToken($token)) {
            return false;
        }

        return $request->activePasswordReset();
    }
}

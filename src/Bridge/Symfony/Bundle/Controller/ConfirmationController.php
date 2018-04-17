<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Command;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\ConfirmEmail;
use Damax\User\Application\Command\RequestEmailConfirmation;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\ConfirmationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/confirmation")
 */
class ConfirmationController
{
    /**
     * @Method("POST")
     * @Route("/email-request")
     * @Command(RequestEmailConfirmation::class, validate=true)
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function requestEmailAction(ConfirmationService $service, RequestEmailConfirmation $command): array
    {
        try {
            $service->requestEmailConfirmation($command);
        } catch (UserNotFound $e) {
            throw new NotFoundHttpException();
        }

        return ['ok' => true];
    }

    /**
     * @Method("POST")
     * @Route("/email")
     * @Command(ConfirmEmail::class, validate=true)
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function confirmEmailAction(ConfirmationService $service, ConfirmEmail $command): array
    {
        try {
            $service->confirmEmail($command);
        } catch (ActionRequestNotFound | ActionRequestExpired $e) {
            throw new NotFoundHttpException();
        }

        return ['ok' => true];
    }
}

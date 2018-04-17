<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Command;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\RequestPasswordReset;
use Damax\User\Application\Command\ResetPassword;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\PasswordService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/password")
 */
class PasswordController
{
    /**
     * @Method("POST")
     * @Route("/reset-request")
     * @Command(RequestPasswordReset::class, validate=true)
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function requestResetAction(PasswordService $service, RequestPasswordReset $command): array
    {
        try {
            $service->requestPasswordReset($command);
        } catch (UserNotFound $e) {
            throw new NotFoundHttpException();
        }

        return ['ok' => true];
    }

    /**
     * @Method("POST")
     * @Route("/reset")
     * @Command(ResetPassword::class, validate=true)
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function resetAction(PasswordService $service, ResetPassword $command): array
    {
        try {
            $service->resetPassword($command);
        } catch (ActionRequestNotFound | ActionRequestExpired $e) {
            throw new NotFoundHttpException();
        }

        return ['ok' => true];
    }
}

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
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/password")
 */
class PasswordController
{
    /**
     * @OpenApi\Post(
     *     tags={"user"},
     *     summary="Initiate password reset routine.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=RequestPasswordReset::class))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="Request result.",
     *         @OpenApi\Schema(type="object", @OpenApi\Property(property="ok", type="boolean"))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="User not found."
     *     )
     * )
     *
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
     * @OpenApi\Post(
     *     tags={"user"},
     *     summary="Reset user password by token.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=ResetPassword::class))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="Request result.",
     *         @OpenApi\Schema(type="object", @OpenApi\Property(property="ok", type="boolean"))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Password reset token not found."
     *     )
     * )
     *
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

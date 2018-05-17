<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Command;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\PasswordResetDto;
use Damax\User\Application\Dto\PasswordResetRequestDto;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\PasswordService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/password")
 */
class PasswordController
{
    private $service;

    public function __construct(PasswordService $service)
    {
        $this->service = $service;
    }

    /**
     * @OpenApi\Post(
     *     tags={"security"},
     *     summary="Request password reset.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=PasswordResetRequestDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=204,
     *         description="Request initiated."
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="User not found."
     *     )
     * )
     *
     * @Method("POST")
     * @Route("/reset-request")
     * @Command(PasswordResetRequestDto::class, validate=true, param="request")
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function requestResetAction(PasswordResetRequestDto $request): Response
    {
        try {
            $this->service->requestPasswordReset($request);
        } catch (UserNotFound $e) {
            throw new NotFoundHttpException();
        }

        return Response::create('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @OpenApi\Post(
     *     tags={"security"},
     *     summary="Reset user password.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=PasswordResetDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=204,
     *         description="Password reset."
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Password reset request not found."
     *     )
     * )
     *
     * @Method("POST")
     * @Route("/reset")
     * @Command(PasswordResetDto::class, validate=true, param="reset")
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function resetAction(PasswordResetDto $reset): Response
    {
        try {
            $this->service->resetPassword($reset);
        } catch (ActionRequestNotFound | ActionRequestExpired $e) {
            throw new NotFoundHttpException();
        }

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}

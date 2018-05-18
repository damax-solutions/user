<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\EmailConfirmationDto;
use Damax\User\Application\Dto\EmailConfirmationRequestDto;
use Damax\User\Application\Exception\ActionRequestExpired;
use Damax\User\Application\Exception\ActionRequestNotFound;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\ConfirmationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/confirmation")
 */
class ConfirmationController
{
    private $service;

    public function __construct(ConfirmationService $service)
    {
        $this->service = $service;
    }

    /**
     * @OpenApi\Post(
     *     tags={"registration"},
     *     summary="Request email confirmation.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=EmailConfirmationRequestDto::class))
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
     * @Route("/email-request")
     * @Serialize()
     * @Deserialize(EmailConfirmationRequestDto::class, validate=true, param="request")
     *
     * @throws NotFoundHttpException
     */
    public function requestEmailAction(EmailConfirmationRequestDto $request): Response
    {
        try {
            $this->service->requestEmailConfirmation($request);
        } catch (UserNotFound $e) {
            throw new NotFoundHttpException();
        }

        return Response::create('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @OpenApi\Post(
     *     tags={"registration"},
     *     summary="Confirm user email.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=EmailConfirmationDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=204,
     *         description="Email confirmed."
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Email confirmation request not found."
     *     )
     * )
     *
     * @Method("POST")
     * @Route("/email")
     * @Serialize()
     * @Deserialize(EmailConfirmationDto::class, validate=true, param="confirmation")
     *
     * @throws NotFoundHttpException
     */
    public function confirmEmailAction(EmailConfirmationDto $confirmation): Response
    {
        try {
            $this->service->confirmEmail($confirmation);
        } catch (ActionRequestNotFound | ActionRequestExpired $e) {
            throw new NotFoundHttpException();
        }

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}

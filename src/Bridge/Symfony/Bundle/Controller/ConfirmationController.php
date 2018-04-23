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
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/confirmation")
 */
class ConfirmationController
{
    /**
     * @OpenApi\Post(
     *     tags={"user"},
     *     summary="Initiate email confirmation routine.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=RequestEmailConfirmation::class))
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
     * @OpenApi\Post(
     *     tags={"user"},
     *     summary="Confirm user email by token.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=ConfirmEmail::class))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="Request result.",
     *         @OpenApi\Schema(type="object", @OpenApi\Property(property="ok", type="boolean"))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Email confirmation token not found."
     *     )
     * )
     *
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

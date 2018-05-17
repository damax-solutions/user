<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Command;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\ChangePassword;
use Damax\User\Application\Command\UpdateUser;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Dto\UserInfoDto;
use Damax\User\Application\Dto\UserPasswordDto;
use Damax\User\Application\Service\PasswordService;
use Damax\User\Application\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/user")
 */
class AuthenticatedUserController
{
    private $service;
    private $tokenStorage;

    public function __construct(UserService $service, TokenStorageInterface $tokenStorage)
    {
        $this->service = $service;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @OpenApi\Get(
     *     tags={"user-auth"},
     *     summary="Get authenticated user.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=200,
     *         description="User info.",
     *         @OpenApi\Schema(ref=@Model(type=UserDto::class, groups={"user_auth"}))
     *     )
     * )
     *
     * @Method("GET")
     * @Route("")
     * @Serialize({"user_auth"})
     */
    public function getAction(): UserDto
    {
        return $this->service->fetch($this->tokenStorage->getToken()->getUsername());
    }

    /**
     * @OpenApi\Patch(
     *     tags={"user-auth"},
     *     summary="Update authenticated user.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=UserInfoDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=200,
     *         description="Authenticated user.",
     *         @OpenApi\Schema(ref=@Model(type=UserDto::class, groups={"user_auth"}))
     *     )
     * )
     *
     * @Method("PATCH")
     * @Route("")
     * @Command(UserInfoDto::class, validate=true, param="info")
     * @Serialize({"user_auth"})
     */
    public function updateAction(UserInfoDto $info): UserDto
    {
        $command = new UpdateUser();
        $command->userId = $this->tokenStorage->getToken()->getUsername();
        $command->info = $info;

        return $this->service->update($command);
    }

    /**
     * @OpenApi\Put(
     *     tags={"user-auth"},
     *     summary="Change password.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=UserPasswordDto::class))
     *     ),
     *     @OpenApi\Response(response=204, description="Password changed."),
     * )
     *
     * @Method("PUT")
     * @Route("/password")
     * @Command(UserPasswordDto::class, validate=true, param="password")
     */
    public function changePasswordAction(PasswordService $service, UserPasswordDto $password): Response
    {
        $command = new ChangePassword();
        $command->password = $password->newPassword;
        $command->userId = $this->tokenStorage->getToken()->getUsername();

        $service->changePassword($command);

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}

<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Dto\UserRegistrationDto;
use Damax\User\Application\Exception\UserAlreadyExists;
use Damax\User\Application\Service\RegistrationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController
{
    /**
     * @OpenApi\Post(
     *     tags={"user-registration"},
     *     summary="User registration.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=UserRegistrationDto::class, groups={"user_registration"}))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="User info.",
     *         @OpenApi\Schema(ref=@Model(type=UserDto::class, groups={"user_registration"}))
     *     ),
     *     @OpenApi\Response(
     *         response=409,
     *         description="User already exists."
     *     )
     * )
     *
     * @Route("/register", methods={"POST"})
     * @Serialize({"user_registration"})
     * @Deserialize(UserRegistrationDto::class, validate=true, param="user", groups={"user_registration"})
     *
     * @throws ConflictHttpException
     */
    public function registerAction(RegistrationService $service, UserRegistrationDto $user): UserDto
    {
        $command = new RegisterUser();
        $command->user = $user;

        try {
            return $service->registerUser($command);
        } catch (UserAlreadyExists $e) {
            throw new ConflictHttpException();
        }
    }
}

<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Command;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Exception\UserAlreadyExists;
use Damax\User\Application\Service\RegistrationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RegistrationController
{
    /**
     * @OpenApi\Post(
     *     tags={"user"},
     *     summary="User registration.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=RegisterUser::class, groups={"registration"}))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="New user.",
     *         @OpenApi\Schema(ref=@Model(type=UserDto::class, groups={"registration"}))
     *     ),
     *     @OpenApi\Response(
     *         response=409,
     *         description="User already exists."
     *     )
     * )
     *
     * @Method("POST")
     * @Route("/register")
     * @Command(RegisterUser::class, validate=true, groups={"registration"})
     * @Serialize({"registration"})
     *
     * @throws ConflictHttpException
     */
    public function registerAction(RegistrationService $service, RegisterUser $command): UserDto
    {
        try {
            return $service->registerUser($command);
        } catch (UserAlreadyExists $e) {
            throw new ConflictHttpException();
        }
    }
}

<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Command;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Exception\UserAlreadyExists;
use Damax\User\Application\Service\RegistrationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RegistrationController
{
    /**
     * @Method("POST")
     * @Route("/register")
     * @Command(RegisterUser::class, validate=true)
     * @Serialize({"registration"})
     *
     * @throws ConflictHttpException
     */
    public function registerAction(RegistrationService $service, RegisterUser $command): UserDto
    {
        // Remove this with separate command?
        $command->creatorId = null;

        try {
            return $service->registerUser($command);
        } catch (UserAlreadyExists $e) {
            throw new ConflictHttpException();
        }
    }
}

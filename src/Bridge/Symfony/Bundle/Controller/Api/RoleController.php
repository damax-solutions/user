<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Command;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\CreateRole;
use Damax\User\Application\Command\UpdateRole;
use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Dto\RoleInfoDto;
use Damax\User\Application\Exception\RoleAlreadyExists;
use Damax\User\Application\Exception\RoleNotFound;
use Damax\User\Application\Service\RoleService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/user/roles")
 */
class RoleController
{
    private $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    /**
     * @OpenApi\Get(
     *     tags={"role"},
     *     summary="List roles.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=200,
     *         description="Roles list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=RoleDto::class, groups={"user_role"})))
     *     )
     * )
     *
     * @Method("GET")
     * @Route("")
     * @Serialize({"user_role"})
     */
    public function listAction(): array
    {
        return $this->service->fetchAll();
    }

    /**
     * @OpenApi\Get(
     *     tags={"role"},
     *     summary="Get role.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=200,
     *         description="Role info.",
     *         @OpenApi\Schema(ref=@Model(type=RoleDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Role not found."
     *     )
     * )
     *
     * @Method("GET")
     * @Route("/{code}")
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function getAction(string $code): RoleDto
    {
        try {
            return $this->service->fetch($code);
        } catch (RoleNotFound $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @OpenApi\Post(
     *     tags={"role"},
     *     summary="Create role.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=RoleDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="Role info.",
     *         @OpenApi\Schema(ref=@Model(type=RoleDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=409,
     *         description="Role already exists."
     *     )
     * )
     *
     * @Method("POST")
     * @Route("")
     * @Command(RoleDto::class, validate=true, param="role")
     * @Serialize()
     *
     * @throws ConflictHttpException
     */
    public function createAction(RoleDto $role): RoleDto
    {
        $command = new CreateRole();
        $command->role = $role;

        try {
            return $this->service->create($command);
        } catch (RoleAlreadyExists $e) {
            throw new ConflictHttpException();
        }
    }

    /**
     * @OpenApi\Patch(
     *     tags={"role"},
     *     summary="Update role.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=RoleInfoDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=200,
     *         description="Role info.",
     *         @OpenApi\Schema(ref=@Model(type=RoleInfoDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Role not found."
     *     )
     * )
     *
     * @Method("PATCH")
     * @Route("/{code}")
     * @Command(RoleInfoDto::class, validate=true, param="info")
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function updateAction(string $code, RoleInfoDto $info): RoleDto
    {
        $command = new UpdateRole();
        $command->code = $code;
        $command->info = $info;

        try {
            return $this->service->update($command);
        } catch (RoleNotFound $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @OpenApi\Delete(
     *     tags={"role"},
     *     summary="Delete role.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=204,
     *         description="Role deleted."
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Role not found."
     *     )
     * )
     *
     * @Method("DELETE")
     * @Route("/{code}")
     *
     * @throws NotFoundHttpException
     */
    public function deleteAction(string $code): Response
    {
        try {
            $this->service->delete($code);
        } catch (RoleNotFound $e) {
            throw new NotFoundHttpException();
        }

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}

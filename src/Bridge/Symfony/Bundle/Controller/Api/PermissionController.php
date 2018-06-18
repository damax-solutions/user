<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Command\CreatePermission;
use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Exception\PermissionAlreadyExists;
use Damax\User\Application\Exception\PermissionNotFound;
use Damax\User\Application\Service\PermissionService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/permissions")
 */
class PermissionController
{
    private $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    /**
     * @OpenApi\Get(
     *     tags={"user-permission"},
     *     summary="List permissions.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=200,
     *         description="Permissions list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=PermissionDto::class)))
     *     )
     * )
     *
     * @Method("GET")
     * @Route("")
     * @Serialize()
     */
    public function listAction(): array
    {
        return $this->service->fetchAll();
    }

    /**
     * @OpenApi\Post(
     *     tags={"user-permission"},
     *     summary="Create permission.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=PermissionDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="Permission info.",
     *         @OpenApi\Schema(ref=@Model(type=PermissionDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=409,
     *         description="Permission already exists."
     *     )
     * )
     *
     * @Method("POST")
     * @Route("")
     * @Serialize()
     * @Deserialize(PermissionDto::class, validate=true, param="permission")
     *
     * @throws ConflictHttpException
     */
    public function createAction(PermissionDto $permission): PermissionDto
    {
        $command = new CreatePermission();
        $command->permission = $permission;

        try {
            return $this->service->create($command);
        } catch (PermissionAlreadyExists $e) {
            throw new ConflictHttpException();
        }
    }

    /**
     * @OpenApi\Delete(
     *     tags={"user-permission"},
     *     summary="Delete permission.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=204,
     *         description="Permission deleted."
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Permission not found."
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
        } catch (PermissionNotFound $e) {
            throw new NotFoundHttpException();
        }

        return Response::create('', Response::HTTP_NO_CONTENT);
    }
}

<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\RoleDto;
use Damax\User\Application\Exception\RoleNotFound;
use Damax\User\Application\Service\RoleService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/user/roles")
 */
class RoleController
{
    /**
     * @OpenApi\Get(
     *     tags={"user"},
     *     summary="List roles.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=200,
     *         description="Roles list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=RoleDto::class, groups={"role"})))
     *     )
     * )
     *
     * @Method("GET")
     * @Route("")
     * @Serialize({"role"})
     */
    public function listAction(RoleService $service): array
    {
        return $service->fetchAll();
    }

    /**
     * @OpenApi\Get(
     *     tags={"user"},
     *     summary="Get role.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=200,
     *         description="User role.",
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
    public function getAction(string $code, RoleService $service): RoleDto
    {
        try {
            return $service->fetch($code);
        } catch (RoleNotFound $e) {
            throw new NotFoundHttpException();
        }
    }
}

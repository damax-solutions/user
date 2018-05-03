<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\PermissionDto;
use Damax\User\Application\Service\PermissionService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;

/**
 * @Route("/user/permissions")
 */
class PermissionController
{
    /**
     * @OpenApi\Get(
     *     tags={"user"},
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
    public function listAction(PermissionService $service): array
    {
        return $service->fetchAll();
    }
}

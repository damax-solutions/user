<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Exception\UserNotFound;
use Damax\User\Application\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user/users")
 */
class UserController
{
    /**
     * @OpenApi\Get(
     *     tags={"user"},
     *     summary="List users.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Parameter(
     *         name="page",
     *         type="integer",
     *         in="query",
     *         default=1
     *     ),
     *     @OpenApi\Response(
     *         response=200,
     *         description="Users list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=UserDto::class)))
     *     )
     * )
     *
     * @Method("GET")
     * @Route("")
     * @Serialize()
     */
    public function listAction(Request $request, UserService $service): Pagerfanta
    {
        return $service
            ->fetchRange()
            ->setAllowOutOfRangePages(true)
            ->setMaxPerPage(20)
            ->setCurrentPage($request->query->getInt('page', 1))
        ;
    }

    /**
     * @OpenApi\Get(
     *     tags={"user"},
     *     summary="Get user.",
     *     security={
     *         {"Bearer"=""}
     *     },
     *     @OpenApi\Response(
     *         response=200,
     *         description="User.",
     *         @OpenApi\Schema(ref=@Model(type=UserDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="User not found."
     *     )
     * )
     *
     * @Method("GET")
     * @Route("/{id}")
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function getAction(string $id, UserService $service): UserDto
    {
        try {
            return $service->fetch($id);
        } catch (UserNotFound $e) {
            throw new NotFoundHttpException();
        }
    }
}

<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\UserDto;
use Damax\User\Application\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/users")
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
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=UserDto::class, groups={"registration"})))
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
}

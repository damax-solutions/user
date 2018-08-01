<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Swagger\Annotations as OpenApi;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController
{
    /**
     * @OpenApi\Post(
     *     tags={"security"},
     *     summary="User login.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(
     *             type="object",
     *             @OpenApi\Property(property="username", type="string"),
     *             @OpenApi\Property(property="password", type="string")
     *         )
     *     ),
     *     @OpenApi\Response(
     *         response=200,
     *         description="Authentication result.",
     *         @OpenApi\Schema(type="object", @OpenApi\Property(property="token", type="string"))
     *     ),
     *     @OpenApi\Response(
     *         response=401,
     *         description="Bad credentials."
     *     )
     * )
     *
     * @Route("/login", methods={"POST"}, name="security_login")
     */
    public function loginAction()
    {
    }
}

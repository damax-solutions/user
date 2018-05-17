<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;

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
     * @Method("POST")
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
    }
}

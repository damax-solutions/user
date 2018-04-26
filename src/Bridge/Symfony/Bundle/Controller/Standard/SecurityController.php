<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Standard;

use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig_Environment;

class SecurityController
{
    /**
     * @Method("GET")
     * @Route("/login", name="security_login")
     */
    public function loginAction(Twig_Environment $twig, AuthenticationUtils $utils): Response
    {
        $template = $twig->render('@DamaxUser/Security/login.html.twig', [
            'error' => $utils->getLastAuthenticationError(),
            'last_username' => $utils->getLastUsername(),
        ]);

        return Response::create($template);
    }

    /**
     * @Method("POST")
     * @Route("/check", name="security_check")
     *
     * @throws RuntimeException
     */
    public function checkAction(): void
    {
        throw new RuntimeException(sprintf('Invalid method "%s" call.', __METHOD__));
    }

    /**
     * @Method("GET")
     * @Route("/logout", name="security_logout")
     *
     * @throws RuntimeException
     */
    public function logoutAction(): void
    {
        throw new RuntimeException(sprintf('Invalid method "%s" call.', __METHOD__));
    }
}

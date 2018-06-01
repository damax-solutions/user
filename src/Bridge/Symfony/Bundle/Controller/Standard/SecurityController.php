<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Standard;

use Damax\User\Bridge\Symfony\Bundle\Form\Type\LoginType;
use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Method("GET")
     * @Route("/login", name="security_login")
     */
    public function loginAction(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class, null, ['last_username' => $utils->getLastUsername()]);

        return $this->render('@DamaxUser/security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $utils->getLastAuthenticationError(),
        ]);
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

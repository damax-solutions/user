<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Standard;

use Damax\User\Application\Service\PasswordService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\PasswordResetRequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/password")
 */
class PasswordController extends Controller
{
    /**
     * @Route("/reset-request", name="password_reset_request")
     */
    public function requestResetAction(Request $request, PasswordService $password): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password->requestPasswordReset($form->getData());

            $message = $this->get('translator')->trans('password.message.reset_requested', [], 'damax-user');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('@DamaxUser/Password/reset_request.html.twig', ['form' => $form->createView()]);
    }
}

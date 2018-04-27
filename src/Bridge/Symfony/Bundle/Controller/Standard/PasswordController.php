<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Standard;

use Damax\User\Application\Command\ResetPassword;
use Damax\User\Application\Service\PasswordService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\PasswordResetRequestType;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\PasswordResetType;
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
    public function requestResetAction(Request $request, PasswordService $service): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->requestPasswordReset($form->getData());

            $message = $this->get('translator')->trans('password.message.reset_requested', [], 'damax-user');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('@DamaxUser/Password/reset_request.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/reset/{token}", name="password_reset")
     */
    public function resetAction(Request $request, string $token, PasswordService $service): Response
    {
        if (!$service->hasActiveResetRequest($token)) {
            return $this->render('@DamaxUser/Password/reset_expired.html.twig');
        }

        $command = new ResetPassword();
        $command->token = $token;

        $form = $this->createForm(PasswordResetType::class, $command)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->resetPassword($command);

            $message = $this->get('translator')->trans('password.message.changed', [], 'damax-user');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('@DamaxUser/Password/reset.html.twig', ['form' => $form->createView()]);
    }
}

<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Standard;

use Damax\User\Application\Command\RegisterUser;
use Damax\User\Application\Service\RegistrationService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request, RegistrationService $service): Response
    {
        $form = $this->createForm(RegisterType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new RegisterUser();
            $command->user = $form->getData();

            $service->registerUser($command);

            $message = $this->get('translator')->trans('registration.message.completed', [], 'damax-user');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('@DamaxUser/Registration/register.html.twig', ['form' => $form->createView()]);
    }
}

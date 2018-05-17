<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Standard;

use Damax\User\Application\Command\UpdateUser;
use Damax\User\Application\Dto\UserInfoDto;
use Damax\User\Application\Service\UserService;
use Damax\User\Bridge\Symfony\Bundle\Form\Type\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @Method("GET")
     * @Route("", name="profile_view")
     */
    public function viewAction(UserService $service): Response
    {
        $user = $service->fetch($this->getUser()->getUsername());

        return $this->render('@DamaxUser/Profile/view.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/edit", name="profile_edit")
     */
    public function editAction(Request $request, UserService $service): Response
    {
        $user = $service->fetch($this->getUser()->getUsername());

        $form = $this->createForm(ProfileType::class, UserInfoDto::fromUserDto($user))->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new UpdateUser();
            $command->info = $form->getData();
            $command->userId = $user->id;

            $service->update($command);

            $message = $this->get('translator')->trans('profile.message.updated', [], 'damax-user');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('profile_view');
        }

        return $this->render('@DamaxUser/Profile/edit.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }
}

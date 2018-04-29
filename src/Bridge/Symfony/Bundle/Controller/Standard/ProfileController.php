<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Standard;

use Damax\User\Application\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}

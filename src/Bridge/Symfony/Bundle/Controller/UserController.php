<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller;

use Damax\User\Application\Service\UserService;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/users")
 */
class UserController
{
    /**
     * @Method("POST")
     * @Route("")
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

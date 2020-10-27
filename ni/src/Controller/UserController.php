<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", methods={"GET"})
     */
    public function number()
    {
        $user = $this->getUser();

        return new JsonResponse(
            ['name' => $user->getName()],
            200
        );
    }
}
<?php

namespace App\Controller;

use App\ResponseBuilder\ErrorResponse;
use App\ResponseBuilder\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", methods={"GET"})
     */
    public function showUser()
    {
        $user = $this->getUser();

        if (!$user) {
            return new ErrorResponse('No user data found. You should not see this error so you probably hacked the system');
        }

        return new SuccessResponse(['name' => $user->getName()]);
    }
}
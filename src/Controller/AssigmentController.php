<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AssigmentController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('assigment/index.html.twig', [
            'controller_name' => 'AssigmentController',
        ]);
    }
}

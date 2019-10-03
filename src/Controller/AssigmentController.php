<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssigmentController extends AbstractController
{
    /**
     * @Route("/assigment", name="assigment")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('assigment/index.html.twig', [
            'controller_name' => 'AssigmentController',
        ]);
    }
}

<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    private $siteUrl;

    /**
     * @param Request $request
     * 
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $this->siteUrl = $request->getBaseUrl();

        return new Response('Hello');
    }
}

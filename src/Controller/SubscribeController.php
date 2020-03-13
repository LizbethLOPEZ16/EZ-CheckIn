<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeController extends AbstractController
{
    /**
     * @Route("/subscribe", name="subscribe")
     */
    public function index()
    {
        return $this->render('subscribe/index.html.twig', [
            'controller_name' => 'SubscribeController',
        ]);
    }
}

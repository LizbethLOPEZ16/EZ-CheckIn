<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HostController extends AbstractController
{
    /**
     * @Route("/host", name="host")
     */
    public function index()
    {
        return $this->render('host/index.html.twig', [
            'controller_name' => 'HostController',
        ]);
    }
}

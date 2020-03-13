<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StaysController extends AbstractController
{
    /**
     * @Route("/stays", name="stays")
     */
    public function index()
    {
        return $this->render('stays/index.html.twig', [
            'controller_name' => 'StaysController',
        ]);
    }
}

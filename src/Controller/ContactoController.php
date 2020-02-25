<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    /**
     * @Route("/contacto", name="contacto")
     */
    public function index()
    {
        return $this->render('contacto/index.html.twig', [
            'controller_name' => 'ContactoController',
        ]);
    }
}

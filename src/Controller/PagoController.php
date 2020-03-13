<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PagoController extends AbstractController
{
    /**
     * @Route("/pago", name="pago")
     */
    public function index()
    {
        return $this->render('pago/index.html.twig', [
            'controller_name' => 'PagoController',
        ]);
    }
}

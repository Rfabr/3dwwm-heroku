<?php

namespace App\Controller\Frontend;

use App\Entity\Designer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DesignerController extends AbstractController
{
    /**
     * @Route("/designer/{slug}", name="app_frontend_designer_display")
     */
    public function display(Designer $designer): Response
    {
        return $this->render('frontend/designer/display.html.twig', [
            'designer' => $designer
        ]);
    }
}

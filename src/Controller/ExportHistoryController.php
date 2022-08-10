<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportHistoryController extends AbstractController
{
    /**
     * @Route("/ExportHistory", name="app_export_history")
     */
    public function index(Request $request): Response
    {
        //przyjmij wartości z z $requesta co do filtrów
        //jeżeli filtry puste weż dane z
        // $data = $this->getDoctrine()->getRepository(Crud::class)->findAll();
        //jeżeli filtry nie puste trza dodać w repozitory pobranie danych 

        $data = $this->getDoctrine()->getRepository(Crud::class)->findAll();
        return $this->render('export_history/index.html.twig', [
            'controller_name' => 'ExportHistoryController',
            'data' => $data,
        ]);
    }
}

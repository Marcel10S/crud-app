<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        if (!$request->isXMLHttpRequest()) {
            return new JsonResponse(['error_msg' => 'Błąd wprowadzono niepoprawne dane']);
        }
        $content = $request->getContent();
        if (empty($content)) {
            return new JsonResponse(['error_msg' => 'Błąd wprowadzono niepoprawne dane']);
        }
        $filters = json_decode($content, true);
        if (
            empty($filters['export_place'])
            && empty($filters['date_from'])
            && empty($filters['date_to'])
        ) {
            $exportsHistory =  $this->getDoctrine()->getRepository(Entity::class)->findAll();
        } else {
            $exportsHistory = $this->doctrine->getRepository(Entity::class)->getRecordsByFilters($filters);
        }

        return $this->render('export_history/index.html.twig',
            [
                'controller_name' => 'ExportHistoryController',
                'exportsHistory' => $exportsHistory,
            ]);
    }
}

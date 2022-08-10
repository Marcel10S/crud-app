<?php

namespace App\Controller;

use App\Entity\Exports;
use App\Repository\ExportsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportHistoryController extends AbstractController
{
    public function __construct(ExportsRepository $exportsRepository, ManagerRegistry $doctrine)
    {
        $this->exportsRepository = $exportsRepository;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/ExportHistory", name="app_export_history")
     */
    public function index(Request $request): Response
    {
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (empty($content)) {
                return new JsonResponse(['error_msg' => 'Błąd wprowadzono niepoprawne dane']);
            }
            $filters = json_decode($content, true);
        }

        if (
            empty($filters['export_place'])
            && empty($filters['date_from'])
            && empty($filters['date_to'])
        ) {
            $exportsHistory = $this->getDoctrine()->getRepository(Exports::class)->findAll();
        } else {
            $exportsHistory = $this->exportsRepository->getRecordsByFilters($filters);
        }

        //there was problem witch displaying data on object names so i changed them on display prefix
        foreach ($exportsHistory as $key => $export) {
            $exportDate = $export->getExportDate();
            $exportsHistory[$key]->display_name = $export->getName();
            $exportsHistory[$key]->display_assigned_person = $export->getAssignedPerson();
            $exportsHistory[$key]->display_export_date = $exportDate->format("Y-m-d");
            $exportsHistory[$key]->display_export_time = $exportDate->format("H:i");
            $exportsHistory[$key]->display_export_place = $export->getExportPlace();
        }

        return $this->render('export_history/index.html.twig',
            [
                'controller_name' => 'ExportHistoryController',
                'exportsHistory' => $exportsHistory,
            ]);
    }
}

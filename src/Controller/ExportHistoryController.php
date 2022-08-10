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
    const DEFAULT_FILTER_PLACEHOLDER = [
        'export_place' => 'Lokal:',
        'date_from' => 'Od:',
        'date_to' => 'Do:',
    ];

    public function __construct(ExportsRepository $exportsRepository, ManagerRegistry $doctrine)
    {
        $this->exportsRepository = $exportsRepository;
        $this->doctrine = $doctrine;
    }

    //TODO - Czyli co bym zaproponował dodania w większym wymiarze czasu
    // 1 paginację wyników

    /**
     * @Route("/ExportHistory", name="app_export_history")
     */
    public function index(Request $request): Response
    {
        $filters = $this->setupFilters($request);
        $exportsHistory = $this->getDisplayData($filters);
        $filters = $this->manageFilters($filters);
        $exportsHistory = $this->manageDisplayData($exportsHistory);
        $rendered_app = $this->render('export_history/index.html.twig',
            [
                'controller_name' => 'ExportHistoryController',
                'exportsHistory' => $exportsHistory,
                'filters' => $filters,
            ]);

        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(['view' => $rendered_app->getContent(),
            ]);
        }
        return $rendered_app;
    }

    protected function setupFilters(Request $request): array
    {
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {
                return json_decode($content, true);
            }
        }
        return [];
    }

    protected function manageFilters($filters): array
    {
        $filters['export_place'] = empty($filters['export_place']) ? static::DEFAULT_FILTER_PLACEHOLDER['export_place'] : $filters['export_place'];
        $filters['date_from'] = empty($filters['date_from']) ? static::DEFAULT_FILTER_PLACEHOLDER['date_from'] : $filters['date_from'];
        $filters['date_to'] = empty($filters['date_to']) ? static::DEFAULT_FILTER_PLACEHOLDER['date_to'] : $filters['date_to'];
        return $filters;
    }

    protected function getDisplayData($filters)
    {
        if (
            empty($filters['export_place'])
            && empty($filters['date_from'])
            && empty($filters['date_to'])
        ) {
            return $this->getDoctrine()->getRepository(Exports::class)->findAll();
        } else {
            return $this->exportsRepository->getRecordsByFilters($filters);
        }
    }

    //there was problem witch displaying data on object names so i changed them on display prefix
    protected function manageDisplayData($exportsHistory)
    {
        foreach ($exportsHistory as $key => $export) {
            $exportDate = $export->getExportDate();
            $exportsHistory[$key]->display_name = $export->getName();
            $exportsHistory[$key]->display_assigned_person = $export->getAssignedPerson();
            $exportsHistory[$key]->display_export_date = $exportDate->format("Y-m-d");
            $exportsHistory[$key]->display_export_time = $exportDate->format("H:i");
            $exportsHistory[$key]->display_export_place = $export->getExportPlace();
        }
        return $exportsHistory;
    }
}

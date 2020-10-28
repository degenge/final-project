<?php

namespace App\Controller;

use App\Entity\Visit;
use App\Form\VisitType;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/visit")
 */
class VisitController extends AbstractController
{
    private VisitRepository $visitRepository;

    /**
     * VisitController constructor.
     * @param VisitRepository $visitRepository
     */
    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    /**
     * @Route("/", name="visit_index", methods={"GET"})
     * @param VisitRepository $visitRepository
     * @return Response
     */
    public function index(VisitRepository $visitRepository): Response
    {
        return $this->render('visit/index.html.twig', [
            'visits' => $visitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/api/{countryCode}", name="visit_api_index", methods={"GET"})
     * @param string $countryCode
     * @param Request $request
     * @return JsonResponse
     */
    public function api_index(string $countryCode, Request $request): JsonResponse
    {
        $visits = $this->visitRepository->findAllByCountryCode($countryCode);
        // TODO: check if could be converted to visits
//        $data   = [];
//
//        foreach ($visits as $visit) {
//            $data[] = [
//                'id'              => $visit->getId(),
//                'title'           => $visit->getTitle(),
//                'description'     => $visit->getDescription(),
//                'dateVisitedFrom' => $visit->getDateVisitedFrom(),
//                'dateVisitedTill' => $visit->getDateVisitedTill(),
//            ];
//        }
        return new JsonResponse($visits, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="visit_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $visit = new Visit();
        $visit->setDateInserted(new \DateTime());

        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visit);
            $entityManager->flush();

            return $this->redirectToRoute('visit_index');
        }

        return $this->render('visit/new.html.twig', [
            'visit' => $visit,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visit_show", methods={"GET"})
     * @param Visit $visit
     * @return Response
     */
    public function show(Visit $visit): Response
    {
        return $this->render('visit/show.html.twig', [
            'visit' => $visit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="visit_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Visit $visit
     * @return Response
     */
    public function edit(Request $request, Visit $visit): Response
    {
        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visit_index');
        }

        return $this->render('visit/edit.html.twig', [
            'visit' => $visit,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visit_delete", methods={"DELETE"})
     * @param Request $request
     * @param Visit $visit
     * @return Response
     */
    public function delete(Request $request, Visit $visit): Response
    {
        if ($this->isCsrfTokenValid('delete' . $visit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($visit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('visit_index');
    }
}

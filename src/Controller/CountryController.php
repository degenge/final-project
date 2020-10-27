<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/country")
 */
class CountryController extends AbstractController
{

    private CountryRepository $countryRepository;

    /**
     * Style Controller constructor.
     * @param CountryRepository $countryRepository
     */
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @Route("/", name="country_index", methods={"GET"})
     * @param CountryRepository $countryRepository
     * @return Response
     */
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('country/index.html.twig', [
            'countries' => $countryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/api", name="country_api_index", methods={"GET"})
     */
    public function api_index(): JsonResponse
    {
        $countries = $this->countryRepository->findAll();
        $data      = [];

        foreach ($countries as $country) {
            $data[] = [
                'id'         => $country->getId(),
                'code'       => $country->getCode(),
                'name'       => $country->getName(),
                'dateInsert' => $country->getDateInsert(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="country_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $country = new Country();
        $form    = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->redirectToRoute('country_index');
        }

        return $this->render('country/new.html.twig', [
            'country' => $country,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="country_show", methods={"GET"})
     * @param Country $country
     * @return Response
     */
    public function show(Country $country): Response
    {
        return $this->render('country/show.html.twig', [
            'country' => $country,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="country_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Country $country
     * @return Response
     */
    public function edit(Request $request, Country $country): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('country_index');
        }

        return $this->render('country/edit.html.twig', [
            'country' => $country,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="country_delete", methods={"DELETE"})
     * @param Request $request
     * @param Country $country
     * @return Response
     */
    public function delete(Request $request, Country $country): Response
    {
        if ($this->isCsrfTokenValid('delete' . $country->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->redirectToRoute('country_index');
    }
}

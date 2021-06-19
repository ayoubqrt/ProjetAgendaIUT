<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SalleRepository;
use App\Entity\Salle;

/**
 * @Route("/api/salles", name="api_salles_")
 */
class SalleController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(SalleRepository $repository): JsonResponse
    {
        $salles = $repository->findAll();
        return $this->json($salles, 200);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Salle $salle = null): JsonResponse
    {
        if (is_null($salle)) {
            return $this->json([
                'message' => 'Cette salle est introuvable',
            ], 404);
        }

        return $this->json($salle);
    }

}

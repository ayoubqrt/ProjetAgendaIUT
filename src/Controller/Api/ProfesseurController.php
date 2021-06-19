<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfesseurRepository;
use App\Entity\Professeur;
use App\Entity\Avis;

/**
 * @Route("/api/professeurs", name="api_professeurs_")
 */
class ProfesseurController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(ProfesseurRepository $repository): JsonResponse
    {
        $professeurs = $repository->findAll();
        return $this->json($professeurs, 200);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Professeur $professeur = null): JsonResponse
    {
        if (is_null($professeur)) {
            return $this->json([
                'message' => 'Ce professeur est introuvable',
            ], 404);
        }

        return $this->json($professeur);
    }

    /**
     * @Route("/{id}/avis", name="index_avis", methods={"GET"})
     */
    public function indexAvis(Professeur $professeur = null): JsonResponse
    {
        if (is_null($professeur)) {
            return $this->json([
                'message' => 'Ce professeur est introuvable',
            ], 404);
        }

        return $this->json($professeur->getAvis()->toArray());
    }

    /**
     * @Route("/{id}/avis", name="create_avis", methods={"POST"})
     */
    public function createAvis(Request $request, Professeur $professeur = null, ValidatorInterface $validator, 
    EntityManagerInterface $em): JsonResponse
    {
        if (is_null($professeur)) {
            return $this->json([
                'message' => 'Ce professeur est introuvable',
            ], 404);
        }

        $data = json_decode($request->getContent(), true);
        $data['professeur'] = $professeur;
        $avis = new Avis($data);

        $errors = $validator->validate($avis);

        if ($errors->count() > 0) {
            return $this->json($this->formatErrors($errors), 400);
        }

        $em->persist($avis);
        $em->flush();

        return $this->json($avis, 201);
    }

    /**
     * @Route("/avis/{id}", name="delete_avis", methods={"DELETE"})
     */
    public function deleteAvis(Avis $avis = null, EntityManagerInterface $em): JsonResponse
    {
        if (is_null($avis)) {
            return $this->json([
                'message' => 'Cet avis est introuvable',
            ], 404);
        }

        $em->remove($avis);
        $em->flush();

        return $this->json(null , 204);
    }

    /**
     * @Route("/avis/{id}", name="update_avis", methods={"PATCH"})
     */
    public function updateAvis(Request $request, Avis $avis = null, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        if (is_null($avis)) {
            return $this->json([
                'message' => 'Cet avis est introuvable',
            ], 404);
        }

        $data = json_decode($request->getContent(), true);
        $errors = $avis->updateFromArray($data);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $attribute) {
                $messages[$attribute] = "Cet attribute n'existe pas.";
            }

            return $this->json($messages, 400);
        }

        $errors = $validator->validate($avis);

        if ($errors->count() > 0) {
            return $this->json($this->formatErrors($errors), 400);
        }

        $em->persist($avis);
        $em->flush();

        return $this->json($avis, 200);
    }

    protected function formatErrors($errors): array
    {
        $messages = [];
        foreach ($errors as $error) {
            $messages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $messages;
    }
}

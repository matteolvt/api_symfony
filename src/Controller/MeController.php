<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MeController extends AbstractController
{
    // C'est cette ligne (l'Attribut) qui crée la route !
    #[Route('/api/me', name: 'app_me', methods: ['GET'])]
    public function index(): JsonResponse
    {
        // 1. On récupère l'utilisateur connecté via le Token JWT
        $user = $this->getUser();

        // 2. Si le token est invalide, $user sera null (mais le firewall bloque avant normalement)
        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // 3. On renvoie les infos de l'utilisateur en JSON
        return $this->json([
            'message' => 'Connexion réussie !',
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\User;
use App\Traits\ProcessError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserController extends AbstractController
{
    use ProcessError;

    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    #[Route('/api/me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/api/me', name: 'user_update', methods: ['PUT'])]
    public function updateUser(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $data = $request->toArray();

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $user->setPassword($data['password']);
        }

        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return $this->json(['errors' => $this->errorsToArray($errors)], 400);
        }

        $this->entityManager->flush();

        return $this->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ]
        ]);
    }

    #[Route('/api/me', name: 'user_delete', methods: ['DELETE'])]
    public function deleteUser(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json(['message' => 'User deleted successfully']);
    }
}

<?php

namespace App\Controller;

use App\Dto\LoginRequest;
use App\Entity\User;
use App\Traits\ProcessError;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AuthController extends AbstractController
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

    /**
     * @throws Exception
     */
    #[Route('/api/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $user = new User();
        $user->setEmail($data['email'] ?? null);
        $user->setPassword($data['password'] ?? null);
        $user->setName($data['name'] ?? null);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return $this->json(['error' => $this->errorsToArray($errors)], 400);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ]
        ], 201);
    }

    #[Route('/api/login', name: 'user_login', methods: ['POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $loginRequest = new LoginRequest($data['email'] ?? null, $data['password'] ?? null);
        $errors = $validator->validate($loginRequest);
        if (count($errors) > 0) {
            return $this->json(['error' => $this->errorsToArray($errors)], 400);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $loginRequest->email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $loginRequest->password)) {
            return $this->json(['error' => 'Invalid email or password'], 401);
        }

        $token = $jwtManager->create($user);

        return $this->json([
            'message' => 'Login successful',
            'token' => $token
        ]);
    }
}

<?php

namespace App\Traits;

use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ProcessError
{
    public function errorsToArray(ConstraintViolationListInterface $errors): array
    {
        $errorArray = [];
        foreach ($errors as $error) {
            $errorArray[$error->getPropertyPath()] = [
                'message' => $error->getMessage()
            ];
        }

        return $errorArray;
    }
}
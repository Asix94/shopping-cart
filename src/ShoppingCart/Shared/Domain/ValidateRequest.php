<?php

namespace App\ShoppingCart\Shared\Domain;

use App\ShoppingCart\Shared\Domain\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;

final class ValidateRequest
{
    public static function validate(Request $request, array $params): void
    {
        foreach ($params as $param) {
            if (!$request->query->has($param)) {
                throw new ValidationException('param ' . $param . ' does not exist');
            }
        }
    }
}
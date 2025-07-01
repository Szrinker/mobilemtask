<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PriceToDecimalTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?float
    {
        if (null === $value) {
            return null;
        }

        return $value / 100;
    }

    public function reverseTransform(mixed $value): ?int
    {
        if (null === $value) {
            return null;
        }

        return (int) round($value * 100);
    }
}

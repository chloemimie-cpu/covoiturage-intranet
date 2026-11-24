<?php

declare(strict_types=1);

namespace App\Validators;

/**
 * Contrôle du nom d'une agence.
 */
final class AgenceValidator
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 50;

    /**
     * Valide un nom d'agence.
     *
     * @return string|null Message d'erreur, ou null si le nom est valide.
     */
    public function validate(string $nom): ?string
    {
        $length = mb_strlen($nom);

        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            return 'Le nom doit contenir entre ' . self::MIN_LENGTH . ' et ' . self::MAX_LENGTH . ' caractères.';
        }

        if (preg_match('/^\p{L}[\p{L}\s\'\-]*$/u', $nom) !== 1) {
            return 'Le nom ne doit contenir que des lettres, espaces, apostrophes et tirets.';
        }

        return null;
    }
}
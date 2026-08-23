<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Petit validateur de formulaire accumulant les erreurs par champ.
 *
 */

final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /**
     * Ajoute une erreur pour un champ si la condition est vraie.
     */

    public function check(bool $failsWhenTrue, string $field, string $message): void
    {
        if ($failsWhenTrue && !isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    /**
     * @return bool true si aucune erreur n'a ete enregistree.
     */

    public function passes(): bool
    {
        return $this->errors === [];
    }

    /**
     * @return array<string, string> Les erreurs, indexees par nom de champ.
     */

    public function errors(): array
    {
        return $this->errors;
    }
}

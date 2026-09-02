<?php
namespace App\Service;
class NoteService{
      public static function valeurObligatoire(array $data, string $field): mixed
    {
        if (
            !array_key_exists($field, $data)
            || (empty($data[$field]) && $data[$field] !== 0 && $data[$field] !== 0.0 && $data[$field] !== '0')
        ) {
            throw new \InvalidArgumentException("Le champ {$field} est obligatoire.");
        }

        return $data[$field];
    }

    public static function convertirNote(mixed $value): float
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('La noteBrute doit être numérique.');
        }

        $note = (float) $value;
        self::verifierNote($note);

        return $note;
    }

    public static function verifierNote(float $note): void
    {
        if (!is_finite($note) || $note < 0.0 || $note > 20.0) {
            throw new \InvalidArgumentException('La noteBrute doit être comprise entre 0 et 20.');
        }
    }
}
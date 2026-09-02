<?php
namespace App\Service;
class DateService{
  public static function convertirDate(mixed $value, string $field): \DateTimeImmutable
    {
        if ($value instanceof \DateTimeImmutable) {
            return $value;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException("Le champ {$field} doit être une date au format Y-m-d.");
        }

        $dateValue = trim($value);
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $dateValue);



        if ($date === false || $date->format('Y-m-d') !== $dateValue) {
            throw new \InvalidArgumentException("Le champ {$field} doit être une date valide au format Y-m-d.");
        }

        return $date;
    }
}
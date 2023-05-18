<?php

namespace App\Service;

class PeselService
{
    public function validatePesel(string $pesel): bool
    {
        if (strlen($pesel) !== 11) {
            return false;
        }

        if (!ctype_digit($pesel)) {
            return false;
        }

        if ($this->validateDigitNumber($pesel) === false){
            return false;
        }

        if ($this->validateDate($pesel) === false){
            return false;
        }

        return true;
    }

    public function validateDigitNumber(string $pesel): bool
    {
        $multipliers = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += ((int) $pesel[$i] * $multipliers[$i]) % 10;
        }

        $checkDigit = 10 - ($sum % 10);

        return $checkDigit === (int) $pesel[10];
    }

    public function validateDate(string $pesel): bool
    {
        $month = (int)substr($pesel, 2, 2);
        $day = (int)substr($pesel, 4, 2);


        if ($day > 31 || $day === 0){
            return false;
        }

        if ($month > 92 ||
            ($month > 12 && $month < 21) ||
            ($month > 32 && $month < 41) ||
            ($month > 52 && $month < 61) ||
            ($month > 72 && $month < 81) ||
            $month === 0
        ){
            return false;
        }

        return true;
    }
}

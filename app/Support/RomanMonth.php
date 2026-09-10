<?php

namespace App\Support;

class RomanMonth
{
    private const MAP = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    public static function fromMonth(int $month): string
    {
        return self::MAP[$month] ?? (string) $month;
    }
}

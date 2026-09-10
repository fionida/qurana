<?php

namespace App\Support;

use Carbon\CarbonInterface;
use InvalidArgumentException;

/**
 * Konversi tanggal Masehi ke Hijriyah (perkiraan aritmetika, untuk saran isian admin).
 */
class HijriDate
{
    private const MONTHS = [
        1 => 'Muharram',
        2 => 'Safar',
        3 => 'Rabiul Awal',
        4 => 'Rabiul Akhir',
        5 => 'Jumadil Awal',
        6 => 'Jumadil Akhir',
        7 => 'Rajab',
        8 => 'Sya\'ban',
        9 => 'Ramadhan',
        10 => 'Syawal',
        11 => 'Dzulqa\'dah',
        12 => 'Dzulhijjah',
    ];

    public static function formatFromGregorian(CarbonInterface $date): string
    {
        [$day, $month, $year] = self::gregorianToHijri(
            (int) $date->format('Y'),
            (int) $date->format('n'),
            (int) $date->format('j'),
        );

        $monthName = self::MONTHS[$month] ?? (string) $month;

        return sprintf('%02d %s %d H', $day, $monthName, $year);
    }

    /**
     * @return array{0: int, 1: int, 2: int} day, month, year
     */
    private static function gregorianToHijri(int $year, int $month, int $day): array
    {
        if ($month < 3) {
            $year--;
            $month += 12;
        }

        $a = (int) floor($year / 100);
        $b = 2 - $a + (int) floor($a / 4);
        $jd = (int) floor(365.25 * ($year + 4716))
            + (int) floor(30.6001 * ($month + 1))
            + $day + $b - 1524;

        $l = $jd - 1948440 + 10632;
        $n = (int) floor(($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = (int) floor((10985 - $l) / 5316) * (int) floor((50 * $l) / 17719)
            + (int) floor($l / 5670) * (int) floor((43 * $l) / 15238);
        $l = $l - (int) floor((30 - $j) / 15) * (int) floor((17719 * $j) / 50)
            - (int) floor($j / 16) * (int) floor((15238 * $j) / 43) + 29;
        $hMonth = (int) floor((24 * $l) / 709);
        $hDay = $l - (int) floor((709 * $hMonth) / 24);
        $hYear = 30 * $n + $j - 30;

        if ($hDay < 1 || $hMonth < 1 || $hMonth > 12) {
            throw new InvalidArgumentException('Konversi hijriyah gagal.');
        }

        return [$hDay, $hMonth, $hYear];
    }
}

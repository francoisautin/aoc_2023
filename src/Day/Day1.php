<?php

declare(strict_types=1);

namespace App\Day;

class Day1 extends DayAbstract
{
    public function __construct()
    {
        parent::__construct('input01.txt');
    }

    public function part1(): string
    {
        $lines = explode(PHP_EOL, $this->input);
        $calibratedValues = array_map([$this, 'getCalibrationValuePart1'], $lines);
        return (string) array_sum($calibratedValues);
    }

    public function part2(): string
    {
        $lines = explode(PHP_EOL, $this->input);
        $calibratedValues = array_map([$this, 'getCalibrationValuePart2'], $lines);
        return (string) array_sum($calibratedValues);
    }

    private function getCalibrationValuePart1(string $input): int
    {
        $numbers = preg_replace('~\D~', '', $input);
        return (10 * (int) substr($numbers, 0, 1)) + (int) substr($numbers, -1);
    }

    private function getCalibrationValuePart2(string $input): int
    {
        $numericalDigits = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $sanitizedInput = '';
        for ($pos = 0; $pos < strlen($input); $pos++) {
            $remaining = substr($input, $pos);
            if (is_numeric($remaining[0])) {
                $sanitizedInput .= $remaining[0];
            } else {
                for ($i = 0; $i < sizeof($numericalDigits); $i++) {
                    if (str_starts_with($remaining, $numericalDigits[$i])) {
                        $sanitizedInput .= $i + 1;
                        break;
                    }
                }
            }
        }
        return $this->getCalibrationValuePart1($sanitizedInput);
    }
}

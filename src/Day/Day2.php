<?php

declare(strict_types=1);

namespace App\Day;

/**
 * @phpstan-type CubeSet array{'green': int, 'blue': int, 'red': int}
 */
class Day2 extends DayAbstract
{
    public function __construct()
    {
        parent::__construct('input02.txt');
    }

    public function part1(): string
    {
        /** @var string[] $games */
        $games = array_map(fn ($game) => preg_replace('/Game (\d)*: /', '', $game), explode(PHP_EOL, trim($this->input)));
        $sets = array_map([$this, 'stringToSets'], $games);
        $apexSets = array_map([$this, 'getTheoreticalLargestSet'], $sets);

        /** @var CubeSet $largestPossibleSet */
        $largestPossibleSet = [
            'red' => 12,
            'green' => 13,
            'blue' => 14,
        ];
        $result = 0;
        for ($i = 0; $i < sizeof($apexSets); $i++) {
            if ($apexSets[$i]['red'] <= $largestPossibleSet['red'] &&
                $apexSets[$i]['green'] <= $largestPossibleSet['green'] &&
                $apexSets[$i]['blue'] <= $largestPossibleSet['blue']) {
                $result += $i + 1;
            }
        }
        return (string) $result;
    }

    public function part2(): string
    {
        /** @var string[] $games */
        $games = array_map(fn ($game) => preg_replace('/Game (\d)*: /', '', $game), explode(PHP_EOL, trim($this->input)));
        $sets = array_map([$this, 'stringToSets'], $games);
        $apexSets = array_map([$this, 'getTheoreticalLargestSet'], $sets);
        return (string) array_sum(array_map(fn ($set) => array_product($set), $apexSets));
    }

    /**
     * @param string $input
     * @return CubeSet[]
     */
    private function stringToSets(string $input): array
    {
        $cubeSets = [];
        $stringSets = explode(';', $input);
        foreach ($stringSets as $stringSet) {
            $cubeTypesShown = explode(',', $stringSet);
            $newSet = [
                'red' => 0,
                'green' => 0,
                'blue' => 0,
            ];
            foreach ($cubeTypesShown as $cubeType) {
                $cubeType = trim($cubeType);
                if (str_contains($cubeType, 'red')) {
                    $newSet['red'] = (int) explode(' ', $cubeType)[0];
                } elseif (str_contains($cubeType, 'blue')) {
                    $newSet['blue'] = (int) explode(' ', $cubeType)[0];
                } elseif (str_contains($cubeType, 'green')) {
                    $newSet['green'] = (int) explode(' ', $cubeType)[0];
                }
            }
            $cubeSets[] = $newSet;
        }

        return $cubeSets;
    }

    /**
     * @param CubeSet[] $sets
     * @return CubeSet
     */
    private function getTheoreticalLargestSet(array $sets): array
    {
        $theoreticalLargestSet = [
            'red' => 0,
            'green' => 0,
            'blue' => 0,
        ];

        foreach ($sets as $set) {
            if ($set['red'] > $theoreticalLargestSet['red']) {
                $theoreticalLargestSet['red'] = $set['red'];
            }
            if ($set['green'] > $theoreticalLargestSet['green']) {
                $theoreticalLargestSet['green'] = $set['green'];
            }
            if ($set['blue'] > $theoreticalLargestSet['blue']) {
                $theoreticalLargestSet['blue'] = $set['blue'];
            }
        }

        return $theoreticalLargestSet;
    }
}

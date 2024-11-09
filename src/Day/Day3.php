<?php

declare(strict_types=1);

namespace App\Day;

use Override;

/**
 * @phpstan-type Coord array{'x': int, 'y': int}
 * @phpstan-type Grid array{'dim': array{'x': int, 'y': int}, 'cells': array<int, array<int, string>>}
 */
class Day3 extends DayAbstract
{
    public function __construct()
    {
        parent::__construct('input03.txt');
    }

    #[Override]
    public function part1(): string
    {
        /** @var int[] $partNumbers */
        $partNumbers = [];
        $grid = $this->initGrid();
        $isSerialNumber = false;
        $charSequence = '';

        for ($i = 0; $i < $grid['dim']['y']; $i++) {
            for ($j = 0; $j < $grid['dim']['x']; $j++) {
                $charInCell = $grid['cells'][$i][$j];
                if (!is_numeric($charInCell)) {
                    if ($isSerialNumber) {
                        $partNumbers[] = (int) $charSequence;
                        $isSerialNumber = false;
                    }
                    $charSequence = '';
                } else {
                    $charSequence .= $charInCell;
                    $isSerialNumber |= $this->hasAdjacentSpecialChar(['x' => $j, 'y' => $i], $grid);
                }
            }
        }

        return (string) array_sum($partNumbers);
    }

    #[Override]
    public function part2(): string
    {
        /** @var array<string, int[]> $gearAssociations */
        $gearAssociations = [];
        $grid = $this->initGrid();
        $isSerialNumber = false;
        $charSequence = '';
        /** @var string[] $gearsAssociatedToThisNumber */
        $gearsAssociatedToThisNumber = [];

        for ($i = 0; $i < $grid['dim']['y']; $i++) { for ($j = 0; $j < $grid['dim']['x']; $j++) {
            $charInCell = $grid['cells'][$i][$j];
            if (!is_numeric($charInCell)) {
                if ($isSerialNumber && !empty($gearsAssociatedToThisNumber)) {
                    foreach ($gearsAssociatedToThisNumber as $gear) {
                        if (!isset($gearAssociations[$gear])) {
                            $gearAssociations[$gear] = [];
                        }
                        $gearAssociations[$gear][] = $charSequence;
                    }
                    $isSerialNumber = false;
                }
                $gearsAssociatedToThisNumber = [];
                $charSequence = '';
            } else {
                $charSequence .= $charInCell;
                $gear = $this->hasStarAsNeighbor(['x' => $j, 'y' => $i], $grid);
                if (null !== $gear) {
                    $isSerialNumber = true;
                    if (!in_array($gear, $gearsAssociatedToThisNumber, true)) {
                        $gearsAssociatedToThisNumber[] = $gear;
                    }
                }
            }
        }}

        return (string) array_sum(array_map(
            fn ($value) => array_product($value),
            array_filter($gearAssociations, fn ($gear) => count($gear) == 2)
        ));
    }

    /**
     * @return Grid
     */
    private function initGrid(): array
    {
        $grid = [
            'dim' => [
                'x' => 0,
                'y' => 0,
            ],
            'cells' => [],
        ];

        $lines = explode(PHP_EOL, trim($this->input));
        $grid['dim']['y'] = count($lines);
        $grid['dim']['x'] = strlen($lines[0]);

        foreach ($lines as $line) {
            $grid['cells'][] = str_split($line);
        }

        return $grid;
    }

    /**
     * @param Coord $cellCoord
     * @param Grid $grid
     * @return bool
     */
    private function hasAdjacentSpecialChar(array $cellCoord, array $grid): bool
    {
        $toCheck = $this->getCellNeighbors($cellCoord, $grid);
        foreach ($toCheck as $coord) {
            $charInCell = $grid['cells'][$coord['y']][$coord['x']];
            if ('.' !== $charInCell && !is_numeric($charInCell)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Coord $cellCoord
     * @param Grid $grid
     * @return string|null
     */
    private function hasStarAsNeighbor(array $cellCoord, array $grid): ?string
    {
        $neighbors = $this->getCellNeighbors($cellCoord, $grid);
        foreach ($neighbors as $coord) {
            $charInCell = $grid['cells'][$coord['y']][$coord['x']];
            if ('*' === $charInCell) {
                return "$coord[x],$coord[y]";
            }
        }

        return null;
    }

    private function getCellNeighbors(array $cellCoord, array $grid): array
    {
        $toCheck = [
            ['x' => $cellCoord['x'] - 1, 'y' => $cellCoord['y'] - 1],
            ['x' => $cellCoord['x'],     'y' => $cellCoord['y'] - 1],
            ['x' => $cellCoord['x'] + 1, 'y' => $cellCoord['y'] - 1],
            ['x' => $cellCoord['x'] - 1, 'y' => $cellCoord['y']],
            ['x' => $cellCoord['x'] + 1, 'y' => $cellCoord['y']],
            ['x' => $cellCoord['x'] - 1, 'y' => $cellCoord['y'] + 1],
            ['x' => $cellCoord['x'],     'y' => $cellCoord['y'] + 1],
            ['x' => $cellCoord['x'] + 1, 'y' => $cellCoord['y'] + 1],
        ];

        return array_filter($toCheck, function ($cellCoord) use ($grid) {
            return $cellCoord['x'] >= 0 && $cellCoord['x'] < $grid['dim']['x'] &&
                $cellCoord['y'] >= 0 && $cellCoord['y'] < $grid['dim']['y'];
        });
    }
}

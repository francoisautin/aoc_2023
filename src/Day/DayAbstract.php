<?php

declare(strict_types=1);

namespace App\Day;

use App\Loader\InputLoadException;
use App\Loader\Loader;

abstract class DayAbstract implements DayInterface
{
    protected readonly ?string $input;

    public function __construct(string $fileName)
    {
        try {
            $this->input = Loader::loadFileContents($fileName);
        } catch (InputLoadException) {
            $this->input = null;
        }
    }

    final public function getResults(): string
    {
        if (null === $this->input) {
            return 'Failed to load input file.';
        } else {
            $resultPartOne = $this->part1();
            $resultPartTwo = $this->part2();
            return <<<EOD
            Part 1: $resultPartOne
            Part 2: $resultPartTwo
            EOD;
        }
    }

    public function part1(): string
    {
        return 'Not implemented';
    }

    public function part2(): string
    {
        return 'Not implemented';
    }
}

<?php

declare(strict_types=1);

namespace App\Day;

interface DayInterface
{
    public function getResults(): string;
    public function part1(): string;
    public function part2(): string;
}

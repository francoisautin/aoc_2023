<?php

declare(strict_types=1);

namespace App\Day;

use Override;

class Day4 extends DayAbstract
{
    public function __construct()
    {
        parent::__construct('input04.txt');
    }

    #[Override]
    public function part1(): string
    {
        /** @var string[] $cards */
        $cards = array_map(fn ($card) => preg_replace('/Card( *)(\d)*: /', '', $card), explode(PHP_EOL, trim($this->input)));
        return (string) array_sum(array_map([$this, 'getCardWorth'], $cards));
    }

    #[Override]
    public function part2(): string
    {
        /** @var string[] $cards */
        $cards = array_map(fn ($card) => preg_replace('/Card( *)(\d)*: /', '', $card), explode(PHP_EOL, trim($this->input)));
        $additions = [];
        for ($i = 0; $i < sizeof($cards); $i++) {
            $additions[] = 1;
        }
        for ($i = 0; $i < sizeof($cards); $i++) {
            $numberOfWinningNumbers = $this->getNumberOfWinningNumbers($cards[$i]);
            for ($j = 0; $j < $numberOfWinningNumbers; $j++) {
                $additions[$i + $j + 1] += (1 * $additions[$i]);
            }
        }
        return (string) array_sum($additions);
    }

    private function getNumberOfWinningNumbers(string $card): int
    {
        $cardParts = explode('|', $card);
        $winningNumbers = array_map(
            fn ($number) => (int) $number,
            array_filter(explode(' ', trim($cardParts[0])), fn ($str) => is_numeric($str))
        );
        $actualNumbers = array_map(
            fn ($number) => (int) $number,
            array_filter(explode(' ', trim($cardParts[1])), fn ($str) => is_numeric($str))
        );
        return sizeof(array_intersect($winningNumbers, $actualNumbers));
    }

    public function getCardWorth(string $card): int
    {
        $numberOfWinningNumbers = $this->getNumberOfWinningNumbers($card);
        return match ($numberOfWinningNumbers) {
            0 => 0,
            default => 1 << $numberOfWinningNumbers - 1,
        };
    }

}

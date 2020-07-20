<?php

namespace PGNChess;

use PGNChess\Board;
use PGNChess\PGN\Symbol;

/**
 * Abstract snapshot.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
abstract class AbstractSnapshot
{
    protected $board;

    protected $moves;

    protected $snapshot = [];

    public function __construct(string $movetext)
    {
        $this->board = new Board;
        $this->moves = $this->moves($this->filter($movetext));
    }

    abstract public function take(): array;

    protected function moves(string $movetext)
    {
        $items = [];
        $pairs = array_filter(preg_split('/[0-9]+\./', $movetext));
        foreach ($pairs as $pair) {
            $items[] = array_values(array_filter(explode(' ', $pair)));
        }

        return $items;
    }

    protected function filter(string $movetext)
    {
        $movetext = str_replace([
            Symbol::RESULT_WHITE_WINS,
            Symbol::RESULT_BLACK_WINS,
            Symbol::RESULT_DRAW,
            Symbol::RESULT_UNKNOWN,
        ], '', $movetext);

        return $movetext;
    }

    protected function normalize()
    {
        $values = array_merge(
            array_column($this->snapshot, Symbol::WHITE),
            array_column($this->snapshot, Symbol::BLACK)
        );

        $min = min($values);
        $max = max($values);

        if ($max - $min > 0) {
            foreach ($this->snapshot as $key => $val) {
                $this->snapshot[$key][Symbol::WHITE] = round(($val[Symbol::WHITE] - $min) / ($max - $min), 2);
                $this->snapshot[$key][Symbol::BLACK] = round(($val[Symbol::BLACK] - $min) / ($max - $min), 2);
            }
        }
    }
}
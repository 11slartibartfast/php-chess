<?php

namespace Chess;

use Chess\PGN\Symbol;

/**
 * Ascii.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Ascii
{
    private $fen;

    private $array;

    public function __construct(Fen $fen)
    {
        $this->fen = $fen;

        $this->array = [
            7 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            6 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            5 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            4 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            3 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            2 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            1 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            0 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
        ];

        $this->build();
    }

    public function getArray()
    {
        return $this->array;
    }

    public function print()
    {
        foreach ($this->array as $i => $rank) {
            foreach ($rank as $j => $file) {
                echo $this->array[$i][$j];
            }
            echo PHP_EOL;
        }
    }

    protected function build()
    {
        foreach ($this->fen->getPieces() as $piece) {
            $position = $piece->getPosition();
            $rank = $position[0];
            $file = $position[1] - 1;
            if (Symbol::WHITE === $piece->getColor()) {
                $this->array[$file][ord($rank)-97] = ' '.$piece->getIdentity().' ';
            } else {
                $this->array[$file][ord($rank)-97] = ' '.strtolower($piece->getIdentity()).' ';
            }
        }
    }
}

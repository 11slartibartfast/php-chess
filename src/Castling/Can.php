<?php

namespace PGNChess\Castling;

use PGNChess\Castling\Rule as CastlingRule;
use PGNChess\PGN\Symbol;

/**
 * Can castle class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Can
{
    /*
     * Can castle short.
     *
     * @param string $color
     * @param \stdClass $castling
     * @param \stdClass $space
     * @return bool
     */
    public static function short(string $color, \stdClass $castling, \stdClass $space): bool
    {
        return $castling->{$color}->{Symbol::CASTLING_SHORT} &&
            !(in_array(
                CastlingRule::color($color)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->f,
                $space->{Symbol::oppositeColor($color)})
             ) &&
            !(in_array(
                CastlingRule::color($color)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->g,
                $space->{Symbol::oppositeColor($color)})
             );
    }

    /*
     * Can castle long.
     *
     * @param string $color
     * @param \stdClass $castling
     * @param \stdClass $space
     * @return bool
     */
    public static function long(string $color, \stdClass $castling, \stdClass $space): bool
    {
        return $castling->{$color}->{Symbol::CASTLING_LONG} &&
            !(in_array(
                CastlingRule::color($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->b,
                $space->{Symbol::oppositeColor($color)})
             ) &&
            !(in_array(
                CastlingRule::color($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->c,
                $space->{Symbol::oppositeColor($color)})
             ) &&
            !(in_array(
                CastlingRule::color($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->d,
                $space->{Symbol::oppositeColor($color)})
             );
    }
}

<?php
namespace PGNChess\PGN;

use PGNChess\Castling;
use PGNChess\PGN\Notation;
use PGNChess\PGN\Validator;

/**
 * Converter class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license MIT
 */
class Converter
{
    /**
     * Converts a PGN move into a stdClass object.
     *
     * @param string $color
     * @param string $pgn
     * @return stdClass
     * @throws \InvalidArgumentException
     */
    static public function toObject($color, $pgn)
    {
        Validator::color($color);

        $isCheck = substr($pgn, -1) === '+' || substr($pgn, -1) === '#';

        switch(true) {
            case preg_match('/^' . Move::KING . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KING,
                    'color' => $color,
                    'identity' => Symbol::KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                ]];
                break;

            case preg_match('/^' . Move::KING_CASTLING_SHORT . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KING_CASTLING_SHORT,
                    'color' => $color,
                    'identity' => Symbol::KING,
                    'position' => Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position
                ];
                break;

            case preg_match('/^' . Move::KING_CASTLING_LONG . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KING_CASTLING_LONG,
                    'color' => $color,
                    'identity' => Symbol::KING,
                    'position' => Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->position
                ];
                break;

            case preg_match('/^' . Move::KING_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::KING_CAPTURES,
                    'color' => $color,
                    'identity' => Symbol::KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                ]];
                break;

            case preg_match('/^' . Move::PIECE . '$/', $pgn):
                if (!$isCheck) {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -2), 1);
                    $nextPosition = mb_substr($pgn, -2);
                } else {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -3), 1);
                    $nextPosition = mb_substr(mb_substr($pgn, 0, -1), -2);
                }
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::PIECE,
                    'color' => $color,
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => $currentPosition,
                        'next' => $nextPosition
                ]];
                break;

            case preg_match('/^' . Move::PIECE_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::PIECE_CAPTURES,
                    'color' => $color,
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => !$isCheck ? mb_substr(mb_substr($pgn, 0, -3), 1) : mb_substr(mb_substr($pgn, 0, -4), 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Move::KNIGHT . '$/', $pgn):
                if (!$isCheck) {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -2), 1);
                    $nextPosition = mb_substr($pgn, -2);
                } else {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -3), 1);
                    $nextPosition = mb_substr(mb_substr($pgn, 0, -1), -2);
                }
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KNIGHT,
                    'color' => $color,
                    'identity' => Symbol::KNIGHT,
                    'position' => (object) [
                        'current' => $currentPosition,
                        'next' => $nextPosition
                ]];
                break;

            case preg_match('/^' . Move::KNIGHT_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::KNIGHT_CAPTURES,
                    'color' => $color,
                    'identity' => Symbol::KNIGHT,
                    'position' => (object) [
                        'current' => !$isCheck ? mb_substr(mb_substr($pgn, 0, -3), 1) : mb_substr(mb_substr($pgn, 0, -4), 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Move::PAWN . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN,
                    'color' => $color,
                    'identity' => Symbol::PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => !$isCheck ? $pgn : mb_substr($pgn, 0, -1)
                ]];
                break;

            case preg_match('/^' . Move::PAWN_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN_CAPTURES,
                    'color' => $color,
                    'identity' => Symbol::PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Move::PAWN_PROMOTES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN_PROMOTES,
                    'color' => $color,
                    'identity' => Symbol::PAWN,
                    'newIdentity' => !$isCheck ? mb_substr($pgn, -1) : mb_substr($pgn, -2, -1),
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, 0, 2)
                ]];
                break;

            case preg_match('/^' . Move::PAWN_CAPTURES_AND_PROMOTES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN_CAPTURES_AND_PROMOTES,
                    'color' => $color,
                    'identity' => Symbol::PAWN,
                    'newIdentity' => !$isCheck ? mb_substr($pgn, -1) : mb_substr($pgn, -2, -1),
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, 2, 2)
                ]];
                break;

            default:
                throw new \InvalidArgumentException("This move is not valid: $pgn.");
                break;
        }
    }
}

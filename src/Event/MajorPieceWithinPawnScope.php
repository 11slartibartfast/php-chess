<?php

namespace Chess\Event;

use Chess\PGN\Symbol;

/**
 * A major piece is within a pawn's scope.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class MajorPieceWithinPawnScope extends AbstractEvent
{
    const DESCRIPTION = "A major piece was placed within a pawn's scope";

    public function capture(string $color): int
    {
        if ($this->board->getHistory()) {
            $last = array_slice($this->board->getHistory(), -1)[0];
            if ($last->move->color === Symbol::oppColor($color)) {
                foreach ($this->board->getPiecesByColor($color) as $piece) {
                    if ($piece->getIdentity() === Symbol::PAWN) {
                        foreach ($piece->getCaptureSquares() as $square) {
                            if ($target = $this->board->getPieceByPosition($square)) {
                                switch ($target->getIdentity()) {
                                    case Symbol::QUEEN:
                                        return 1;
                                    case Symbol::ROOK:
                                        return 1;
                                }
                            }
                        }
                    }
                }
            }
        }

        return 0;
    }
}

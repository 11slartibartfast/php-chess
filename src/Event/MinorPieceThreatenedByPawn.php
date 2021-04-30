<?php

namespace Chess\Event;

use Chess\PGN\Symbol;
use Chess\Piece\Pawn;

/**
 * A minor piece is threatened by a pawn.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class MinorPieceThreatenedByPawn extends AbstractEvent
{
    const DESC = 'A minor piece is now threatened by a pawn';

    public function capture(string $color): int
    {
        if ($this->board->getHistory()) {
            $last = array_slice($this->board->getHistory(), -1)[0];
            if ($last->move->color === $color && $last->move->identity === Symbol::PAWN) {
                $pawn = $this->board->getPieceByPosition($last->move->position->next);
                if (is_a($pawn, Pawn::class)) {
                    foreach ($pawn->getCaptureSquares() as $square) {
                        if ($piece = $this->board->getPieceByPosition($square)) {
                            switch (true) {
                                case Symbol::oppColor($piece->getColor()) && $piece->getIdentity() === Symbol::BISHOP:
                                    return 1;
                                case Symbol::oppColor($piece->getColor()) && $piece->getIdentity() === Symbol::KNIGHT:
                                    return 1;
                            }
                        }
                    }
                }
            }
        }

        return 0;
    }
}

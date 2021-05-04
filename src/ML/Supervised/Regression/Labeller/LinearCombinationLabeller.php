<?php

namespace Chess\ML\Supervised\Regression\Labeller;

use Chess\AbstractPicture;
use Chess\Heuristic\LinearCombinationEvaluation;
use Chess\PGN\Symbol;

/**
 * LinearCombination labeller.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class LinearCombinationLabeller
{
    private $heuristicPicture;

    private $label;

    private $weights;

    public function __construct(AbstractPicture $heuristicPicture)
    {
        $this->heuristicPicture = $heuristicPicture;

        $this->label = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $this->weights = (new LinearCombinationEvaluation($heuristicPicture))->getWeights();
    }

    public function label(): array
    {
        foreach ($this->heuristicPicture->sample() as $color => $arr) {
            foreach ($arr as $key => $val) {
                $this->label[$color] += $this->weights[$key] * $val;
            }
        }

        return $this->label;
    }
}

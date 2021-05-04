<?php

namespace Chess\Heuristic;

use Chess\AbstractPicture;
use Chess\PGN\Symbol;

class AdditionEvaluation implements HeuristicEvaluationInterface
{
    private $weigths;

    public function __construct()
    {
        $this->weights = [];
    }

    public function getWeights(): array
    {
        return $this->weigths;
    }

    public function evaluate(AbstractPicture $heuristicPic): array
    {
        $result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $picture = $heuristicPic->take();

        for ($i = 0; $i < count($heuristicPic->getDimensions()); $i++) {
            $result[Symbol::WHITE] += end($picture[Symbol::WHITE])[$i];
            $result[Symbol::BLACK] += end($picture[Symbol::BLACK])[$i];
        }

        return $result;
    }
}
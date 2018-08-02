<?php

namespace TSP\Algorithm {

    use TSP\Model\Edge;
    use TSP\Model\Graph;

    class MinimumPerfectMatching {
        /**
         * @var int[]
         */
        private $Matches;

        /**
         * @var Graph $graph
         */
        private $Graph;

        /**
         * PerfectMatching constructor.
         * @param Graph $graph
         * @throws OddVerticesMatchingException
         * @throws IncompleteGraphMatchingException
         */
        public function __construct($graph) {
            if ($graph->GetVertices() % 2) {
                throw new OddVerticesMatchingException();
            }
            if (!$graph->IsCompleteGraph()) {
                throw new IncompleteGraphMatchingException();
            }

            $this->Graph = clone $graph;
            $this->Matches = [];

            $this->Calculate();
        }

        /**
         *
         */
        private function Calculate() {
            $edges = $this->Graph->Edges;
            usort($edges, function(Edge $edgeA, Edge $edgeB) { return $edgeA->GetWeight() > $edgeB->GetWeight(); });

            foreach ($edges as $edge) {
                if ($this->AllIsMatched()) {
                    return;
                }

                if (!$this->IsMatched($edge->GetVertices()[0]) && !$this->IsMatched($edge->GetVertices()[1])) {
                    $this->Matches[] = $edge->GetVertices();
                }
            }
        }

        /**
         * @param int $vertex
         * @return bool
         */
        private function IsMatched($vertex) {
            foreach ($this->Matches as $match) {
                if ($match[0] == $vertex || $match[1] == $vertex) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @return bool
         */
        private function AllIsMatched() {
            return count($this->Matches) * 2 == $this->Graph->GetVertices();
        }

        /**
         * @return int[]
         */
        public function GetMatches() {
            return $this->Matches;
        }

        /**
         * @return int
         */
        public function GetWeightSummation() {
            $sumWeight = 0;
            foreach ($this->Matches as $match) {
                $sumWeight += $this->Graph->GetEdge($match[0], $match[1])->GetWeight();
            }

            return $sumWeight;
        }
    }
}
<?php

namespace TSP\Algorithm {

    use TSP\Model\Graph;

    class GreedyWeight {

        /**
         * @var Graph $Graph
         */
        private $Graph;

        /**
         * @var int[] $VertexOrder
         */
        private $VertexOrder;

        /**
         * GreedyWeight constructor.
         * @param Graph $graph
         */
        public function __construct($graph, $startVertex = 0) {
            $this->Graph = clone $graph;
            $this->Graph->MakeCompleteGraph();
            $this->VertexOrder = [$startVertex];
            $this->Calculate();
        }

        /**
         * @return int
         */
        public function GetTotalWeight() {
            $adjacency = $this->Graph->GetAdjacencyMatrix();

            $sum = 0;
            $previousVertex = null;
            foreach ($this->VertexOrder as $vertex) {
                if (!is_null($previousVertex)) {
                    $sum += $adjacency[$previousVertex][$vertex];
                }

                $previousVertex = $vertex;
            }

            return $sum;
        }

        /**
         * @return int[]
         */
        public function GetVertexOrder() {
            return $this->VertexOrder;
        }

        private function Calculate() {
            $adjacency = $this->Graph->GetAdjacencyMatrix();

            $currentVertex = $this->VertexOrder[0];
            while (!$this->AllVerticesAreCalculated()) {
                $nextVertex = $this->FindNextVertexOf($currentVertex, $adjacency);
                $this->VertexOrder[] = $nextVertex;
                $currentVertex = $nextVertex;
            }
            $this->VertexOrder[] = $this->VertexOrder[0];
        }

        /**
         * @return bool
         */
        private function AllVerticesAreCalculated() {
            return count($this->VertexOrder) == $this->Graph->GetVertices();
        }

        /**
         * @param int $vertex
         * @param int[][] $adjacency
         * @return int
         */
        private function FindNextVertexOf($vertex, $adjacency) {
            $minWeight = null;
            $selectedVertex = null;

            for ($v = 0; $v < $this->Graph->GetVertices(); $v++) {
                if ($v == $vertex || in_array($v, $this->VertexOrder)) {
                    continue;
                }

                $weightToV = $adjacency[$vertex][$v];
                if (is_null($minWeight) || $weightToV < $minWeight) {
                    $minWeight = $weightToV;
                    $selectedVertex = $v;
                }
            }

            return $selectedVertex;
        }

        /**
         * @return string
         */
        public function __toString() {
            return implode(' -> ', $this->Graph->GetVerticesNames($this->VertexOrder));
        }

    }

}
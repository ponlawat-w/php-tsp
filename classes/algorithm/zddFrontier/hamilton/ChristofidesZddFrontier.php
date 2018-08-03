<?php

namespace TSP\Algorithm {

    use TSP\Model\Graph;

    class ChristofidesZddFrontier {

        /**
         * @var int $Weight
         */
        private $Weight;

        /**
         * @var int[] $VertexOrder
         */
        private $VertexOrder;

        /**
         * @var Graph $Graph
         */
        private $Graph;

        /**
         * ChristofidesZddFrontier constructor.
         * @param Graph $graph
         * @param int $startVertex
         * @throws
         */
        public function __construct($graph, $startVertex = 0) {
            $this->Graph = $graph;

            $christofides = new Christofides($this->Graph, $startVertex);
            $this->Weight = $christofides->GetTotalWeight();
            $this->VertexOrder = $christofides->GetVertexOrder();

            $zdd = new ZddFrontierHamilton($this->Graph, $startVertex, $this->Weight);
            $newWeight = $zdd->GetTotalWeight();
            if ($newWeight) {
                $this->Weight = $newWeight;
                $this->VertexOrder = $zdd->GetVertexOrder();
            }
        }

        /**
         * @return int
         */
        public function GetTotalWeight() {
            return $this->Weight;
        }

        /**
         * @return int[]
         */
        public function GetVertexOrder() {
            return $this->GetVertexOrder();
        }

        /**
         * @return string
         */
        public function __toString() {
            return implode(' -> ', $this->Graph->GetVerticesNames($this->VertexOrder));
        }
    }

}
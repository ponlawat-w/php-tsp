<?php

namespace TSP\Algorithm\GLNPSO {

    use TSP\Model\Graph;

    class Particle {
        /**
         * @var double[] $Positions
         */
        private $Positions;

        /**
         * @var int $PersonalBestCost
         */
        private $PersonalBestCost;
        /**
         * @var double[] $PersonalBestPositions
         */
        private $PersonalBestPositions;

        /**
         * @var int $LocalBestCost
         */
        private $LocalBestCost;
        /**
         * @var double[] $LocalBestPositions
         */
        private $LocalBestPositions;

        /**
         * @var double[] $NearBestPositions
         */
        public $NearBestPositions;

        /**
         * @var double[] $Velocities
         */
        public $Velocities;

        /**
         * Particle constructor.
         * @param Graph $graph
         * @param int $startVertex
         */
        public function __construct($graph, $startVertex) {
            $this->Positions = [];
            $this->Velocities = [];
            for ($v = 0; $v < $graph->GetVertices(); $v++) {
                $this->Positions[$v] = $v == $startVertex ? -1 : mt_rand() / mt_getrandmax();
                $this->Velocities[$v] = 0;

                $this->NearBestPositions[$v] = null;
                $this->LocalBestPositions[$v] = null;
            }

            $this->PersonalBestPositions = $this->Positions;
            $this->PersonalBestCost = $this->GetTotalCost($graph->GetAdjacencyMatrix());

            $this->LocalBestCost = null;
        }

        /**
         * @param int $vertex
         */
        public function MoveVertex($vertex) {
            $this->Positions[$vertex] += $this->Velocities[$vertex];
            if ($this->Positions[$vertex] < 0) {
                $this->Positions[$vertex] = 0;
            }
        }

        /**
         * @param int[][] $adjacency
         */
        public function UpdatePersonalBest($adjacency) {
            $newCost = $this->GetTotalCost($adjacency);

            if (is_null($this->PersonalBestCost) || $newCost < $this->PersonalBestCost) {
                $this->PersonalBestCost = $newCost;
                $this->PersonalBestPositions = $this->Positions;
            }
        }

        /**
         * @param int $cost
         * @param double[] $positions
         */
        public function UpdateLocalBest($cost, $positions) {
            $this->LocalBestCost = $cost;
            $this->LocalBestPositions = $positions;
        }

        /**
         * @return int[]
         */
        public function GetVertexOrder() {
            asort($this->Positions, SORT_ASC);

            $order = [];

            $previousVertex = null;
            foreach ($this->Positions as $vertex => $weight) {
                if (!is_null($previousVertex)) {
                    $order[] = $previousVertex;
                }

                $previousVertex = $vertex;
            }

            $order[] = $previousVertex;
            $order[] = $order[0];

            return $order;
        }

        /**
         * @param int[][] $adjacency
         * @return int
         */
        public function GetTotalCost($adjacency) {
            $sum = 0;
            $order = $this->GetVertexOrder();
            for ($v = 1; $v < count($order); $v++) {
                $sum += $adjacency[$order[$v - 1]][$order[$v]];
            }

            return $sum;
        }

        /**
         * @return double[]
         */
        public function GetPositions() {
            return $this->Positions;
        }

        /**
         * @param int $v
         * @return double
         */
        public function GetPositionOfVertex($v) {
            return $this->Positions[$v];
        }

        /**
         * @return int
         */
        public function GetPersonalBestCost() {
            return $this->PersonalBestCost;
        }

        /**
         * @param int $v
         * @return double
         */
        public function GetPersonalBestPositionOfVertex($v) {
            return $this->PersonalBestPositions[$v];
        }

        /**
         * @param int $v
         * @return double
         */
        public function GetLocalBestPositionOfVertex($v) {
            return $this->LocalBestPositions[$v];
        }
    }

}
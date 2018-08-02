<?php

namespace TSP\Algorithm {

    use TSP\Algorithm\GLNPSO\Particle;
    use TSP\Model\Graph;

    class GLNPSO {
        /**
         * @var int $MaxPrintedParticle
         */
        public $MaxPrintedParticle = 5;

        /**
         * @var double $BestConstant
         */
        public $BestConstant = 1.0;
        /**
         * @var double $GlobalConstant
         */
        public $GlobalConstant = 1.0;
        /**
         * @var double $LocalConstant
         */
        public $LocalConstant = 1.0;
        /**
         * @var double $NearConstant
         */
        public $NearConstant = 1.0;

        /**
         * @var int $NeighbourAmount
         */
        public $NeighbourAmount = 10;

        /**
         * @var int $Iteration
         */
        public $Iteration = 500;

        /**
         * @var Graph $Graph
         */
        private $Graph;

        /**
         * @var int $StartVertex
         */
        private $StartVertex;

        /**
         * @var int[][] $AdjacencyMatrix
         */
        private $AdjacencyMatrix;

        /**
         * @var Particle[] $Particles
         */
        private $Particles;

        /**
         * @var int $GlobalBestCost
         */
        private $GlobalBestCost;
        /**
         * @var double[] $GlobalBestPositions
         */
        private $GlobalBestPositions;

        /**
         * GLNPSO constructor.
         * @param Graph $graph
         * @param int $startVertex = 0
         * @param int $particles
         */
        public function __construct($graph, $startVertex = 0, $particles = 100) {
            $this->Graph = $graph;
            $this->Graph->MakeCompleteGraph();
            $this->AdjacencyMatrix = $this->Graph->GetAdjacencyMatrix();

            $this->Particles = [];

            for ($p = 0; $p < $particles; $p++) {
                $this->Particles[] = new Particle($this->Graph, $startVertex);
            }

            $this->StartVertex = $startVertex;
        }

        /**
         * @return $this
         */
        public function Calculate() {
            $this->ApplyGlobalBest();

            for ($i = 0; $i < $this->Iteration; $i++) {
                if ($i % 100 == 99 || $i == $this->Iteration - 1) {
                    $show = $i + 1;
                    echo "\rIteration {$show} of {$this->Iteration}";
                }
                $this->MoveParticles();
            }
            echo PHP_EOL;

            return $this;
        }

        private function MoveParticles() {
            foreach ($this->Particles as $particleIndex => $particle) {
                for ($v = 0; $v < $this->Graph->GetVertices(); $v++) {
                    if ($v == $this->StartVertex) {
                        continue;
                    }

                    $velocity = 1 * $particle->Velocities[$v];
                    $velocity += $this->BestConstant * self::RandomDouble() * ($particle->GetPersonalBestPositionOfVertex($v) - $particle->GetPositionOfVertex($v));
                    $velocity += $this->LocalConstant * self::RandomDouble() * ($particle->GetLocalBestPositionOfVertex($v) - $particle->GetPositionOfVertex($v));
//                    $velocity += $this->NearConstant * self::RandomDouble() * ($particle->NearBestPositions[$v] - $particle->GetPositionOfVertex($v));
                    $velocity += $this->GlobalConstant * self::RandomDouble() * ($this->GlobalBestPositions[$v] - $particle->GetPositionOfVertex($v));

                    $particle->Velocities[$v] = $velocity;
                    $particle->MoveVertex($v);
                }

                $particle->UpdatePersonalBest($this->AdjacencyMatrix);
            }
            $this->ApplyGlobalBest();
            $this->ApplyLocalBest();
//            $this->ApplyNearBest();
        }

        private function ApplyLocalBest() {
            $particleAmount = count($this->Particles);
            for ($n = 0; $n < $this->NeighbourAmount; $n++) {
                $firstIndex = $n * $this->NeighbourAmount;
                $lastIndex = (($n + 1) * $this->NeighbourAmount) - 1;
                $lastIndex = ($lastIndex >= $particleAmount || ($n == $this->NeighbourAmount && $lastIndex < $particleAmount)) ?
                    $particleAmount - 1 : $lastIndex;

                $bestIndex = $firstIndex;
                $bestCost = $this->Particles[$bestIndex]->GetTotalCost($this->AdjacencyMatrix);
                for ($p = $firstIndex + 1; $p <= $lastIndex; $p++) {
                    $pCost = $this->Particles[$p]->GetTotalCost($this->AdjacencyMatrix);
                    if ($pCost < $bestCost) {
                        $bestCost = $pCost;
                        $bestIndex = $p;
                    }
                }

                $bestPositions = $this->Particles[$bestIndex]->GetPositions();
                for ($p = $firstIndex; $p <= $lastIndex; $p++) {
                    $this->Particles[$p]->UpdateLocalBest($bestCost, $bestPositions);
                }
            }
        }

        private function ApplyNearBest() {
            $FDR = [];
            $particleAmount = count($this->Particles);
            $maxFDR = null;
            $maxFDRPosition = [null, null, null];
            for ($i = 0; $i < $particleAmount; $i++) {
                if (!isset($FDR[$i])) {
                    $FDR[$i] = [];
                }

                for ($j = 0; $j < $particleAmount; $j++) {
                    if ($i == $j) {
                        continue;
                    }
                    if (!isset($FDR[$i][$j])) {
                        $FDR[$i][$j] = [];
                    }

                    for ($v = 0; $v < $this->Graph->GetVertices(); $v++) {
                        if ($this->Particles[$j]->GetPersonalBestPositionOfVertex($v) == $this->Particles[$i]->GetPositionOfVertex($v)) {
                            continue;
                        }

                        if (!isset($FDR[$i][$j][$v])) {
                            $FDR[$i][$j][$v] = 0;
                        }

                        $currentFDR = &$FDR[$i][$j][$v];
                        $currentFDR = $this->Particles[$i]->GetTotalCost($this->AdjacencyMatrix) - $this->Particles[$j]->GetPersonalBestCost();
                        $currentFDR /= abs($this->Particles[$j]->GetPersonalBestPositionOfVertex($v) - $this->Particles[$i]->GetPositionOfVertex($v));

                        if (is_null($maxFDR) || $currentFDR > $maxFDR) {
                            $maxFDR = $currentFDR;
                            $maxFDRPosition = [$i, $j, $v];
                        }
                    }
                }
            }

            if ($maxFDR) {
                $j = $maxFDRPosition[1];
                for ($i = 0; $i < $particleAmount; $i++) {
                    for ($v = 0; $v < $this->Graph->GetVertices(); $v++) {
                        $this->Particles[$i]->NearBestPositions[$v] = $this->Particles[$j]->GetPersonalBestPositionOfVertex($v);
                    }
                }
            }
        }

        private function ApplyGlobalBest() {
            foreach ($this->Particles as $particle) {
                $newCost = $particle->GetTotalCost($this->AdjacencyMatrix);

                if (is_null($this->GlobalBestCost) || $newCost < $this->GlobalBestCost) {
                    $this->GlobalBestCost = $newCost;
                    $this->GlobalBestPositions = $particle->GetPositions();
                }
            }
        }

        /**
         * @return string
         */
        public function __toString() {
            $graph = $this->Graph;

            $adjacency = $this->AdjacencyMatrix;
            $particles = $this->Particles;
            usort($particles, function($particleA, $particleB) use ($adjacency) {
                return $particleA->GetTotalCost($adjacency) > $particleB->GetTotalCost($adjacency);
            });
            $particles = array_values($particles);

            $str = '';
            for ($p = 0; $p < $this->MaxPrintedParticle; $p++) {
                $particle = $particles[$p];
                $vertexOrder = $particle->GetVertexOrder();
                $vertexNameOrder = array_map(function($vertex) use ($graph) { return $graph->GetVertexName($vertex); }, $vertexOrder);
                $str .= implode(' -> ', $vertexNameOrder);
                $str .= ': ' . $particle->GetTotalCost($adjacency);
                $str .= PHP_EOL;
            }

            if ($this->GlobalBestCost) {
                $globalBestPositions = $this->GlobalBestPositions;
                asort($globalBestPositions, SORT_ASC);
                $globalBestVertexOrder = array_keys($globalBestPositions);
                $globalBestVertexOrder[] = $globalBestVertexOrder[0];
                $globalBestVertexNameOrder = array_map(function($vertex) use ($graph) { return $graph->GetVertexName($vertex); }, $globalBestVertexOrder);
                $str .= 'Global: ';
                $str .= implode(' -> ', $globalBestVertexNameOrder);
                $str .= ": {$this->GlobalBestCost}";
                $str .= PHP_EOL;
            }

            return $str;
        }

        /**
         * @return double
         */
        private static function RandomDouble() {
            return mt_rand() / mt_getrandmax();
        }
    }

}
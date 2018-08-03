<?php

namespace TSP\Algorithm {

    use TSP\Model\BinaryTree\BinaryNode;
    use TSP\Model\Graph;

    abstract class ZddFrontierPathFinding {

        /**
         * @var Graph $Graph
         */
        protected $Graph;

        /**
         * @var int[][] $Frontiers
         */
        protected $Frontiers;

        /**
         * @var BinaryNode $TerminalTrue
         */
        protected $TerminalTrue;
        /**
         * @var BinaryNode $TerminalFalse
         */
        protected $TerminalFalse;

        /**
         * @param int $vertex
         * @param int $edgeIndex
         * @return bool
         */
        private function VertexIsFinished($vertex, $edgeIndex) {
            for ($e = $edgeIndex + 1; $e < count($this->Graph->Edges); $e++) {
                if ($this->Graph->Edges[$e]->Connects($vertex)) {
                    return false;
                }
            }

            return true;
        }

        protected function CalculateFrontiers() {
            $this->Frontiers = [[]];

            foreach ($this->Graph->Edges as $eI => $edge) {
                $this->Frontiers[$eI + 1] = $this->Frontiers[$eI];
                $currentFrontier = &$this->Frontiers[$eI + 1];
                foreach ($edge->GetVertices() as $v) {
                    if (!in_array($v, $currentFrontier)) {
                        $currentFrontier[] = $v;
                    }

                    if ($this->VertexIsFinished($v, $eI)) {
                        array_splice($currentFrontier, array_search($v, $currentFrontier), 1);
                    }
                }
            }
        }
    }

}
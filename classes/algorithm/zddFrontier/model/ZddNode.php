<?php

namespace TSP\Algorithm\ZddFrontierPathFinding\Model {

    use TSP\Model\BinaryTree\BinaryNode;
    use TSP\Model\Edge;
    use TSP\Model\Graph;

    class ZddNode extends BinaryNode {
        /**
         * @var int[] $Degrees
         */
        public $Degrees;

        /**
         * @var int[] $Components
         */
        public $Components;

        /**
         * @var int[] $UsedEdges
         */
        public $UsedEdges;

        /**
         * @var bool $EndsWithTrue
         */
        public $EndsWithTrue;

        /**
         * @return ZddNode
         */
        public function __clone() {
            $newNode = new ZddNode($this->Value);
            $newNode->Degrees = $this->Degrees;
            $newNode->Components = $this->Components;
            $newNode->UsedEdges = $this->UsedEdges;
            return $newNode;
        }

        /**
         * @param mixed $childValue
         * @return ZddNode
         */
        public function ForkChild($childValue) {
            $childNode = new ZddNode($childValue);
            $childNode->Degrees = $this->Degrees;
            $childNode->Components = $this->Components;
            $childNode->UsedEdges = $this->UsedEdges;
            return $childNode;
        }

        /**
         * @param bool|int $whichChild
         * @param BinaryNode $childNode
         */
        public function SetChild($whichChild, $childNode) {
            parent::SetChild($whichChild, $childNode);

            if ($this->HasTwoChildren()) {
                unset($this->UsedEdges);
                unset($this->Degrees);
                unset($this->Components);
            }
        }

        /**
         * @param Edge $edge
         */
        public function ApplyDegreesComponents($edge) {
            $vertices = $edge->GetVertices();

            $this->Degrees[$vertices[0]]++;
            $this->Degrees[$vertices[1]]++;

            $minComponent = min($this->Components[$vertices[0]], $this->Components[$vertices[1]]);
            $maxComponent = max($this->Components[$vertices[0]], $this->Components[$vertices[1]]);
            for ($v = 0; $v < count($this->Components); $v++) {
                if ($this->Components[$v] == $maxComponent) {
                    $this->Components[$v] = $minComponent;
                }
            }

            $this->UsedEdges[] = $this->Value;
        }

        /**
         * @param Graph $graph
         * @return ZddNode
         */
        public static function CreateRootNode($graph) {
            $rootNode = new ZddNode(0);
            $rootNode->Degrees = [];
            $rootNode->Components = [];
            $rootNode->UsedEdges = [];
            $rootNode->EndsWithTrue = false;

            for ($v = 0; $v < $graph->GetVertices(); $v++) {
                $rootNode->Degrees[$v] = 0;
                $rootNode->Components[$v] = $v;
            }

            return $rootNode;
        }
    }

}
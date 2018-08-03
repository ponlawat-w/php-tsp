<?php

namespace TSP\Algorithm {

    use TSP\Algorithm\ZddFrontierPathFinding\Model\ZddNode;
    use TSP\Model\BinaryTree;
    use TSP\Model\BinaryTree\BinaryNode;
    use TSP\Model\Edge;
    use TSP\Model\Graph;

    class ZddFrontierHamilton extends ZddFrontierPathFinding {
        /**
         * @var int[] $BestVertexOrder
         */
        private $BestVertexOrder;
        /**
         * @var int $BestWeight
         */
        private $BestWeight;

        /**
         * @var BinaryTree $Tree
         */
        private $Tree;

        /**
         * @var int $StartVertex
         */
        private $StartVertex;

        /**
         * ZddFrontierHamilton constructor.
         * @param Graph $graph
         * @param int $startVertex
         * @param int $minimumWeight
         */
        public function __construct($graph, $startVertex = 0, $minimumWeight = 0) {
            $this->Graph = clone $graph;
            $this->Graph->MakeCompleteGraph();

            $this->BestWeight = $minimumWeight ? $minimumWeight : $this->Graph->GetTotalWeight() * 2;
            $this->BestVertexOrder = [];

            $this->StartVertex = $startVertex;

            $this->TerminalTrue = new BinaryNode(true);
            $this->TerminalFalse = new BinaryNode(false);

            $this->CalculateFrontiers();
            $this->CalculateBinaryTree();
        }

        private function CalculateBinaryTree() {
            $rootNode = ZddNode::CreateRootNode($this->Graph);
            $this->Tree = new BinaryTree($rootNode);
            $this->Traverse();

            if (!count($this->BestVertexOrder)) {
                $this->BestWeight = null;
            }
        }

        /**
         * @param ZddNode $node
         * @param int $index
         * @return ZddNode|BinaryNode
         */
        private function Traverse($node = null, $index = 0) {
            if (!$node && $this->Tree->GetRootNode()) {
                $this->Traverse($this->Tree->GetRootNode());
            } else {
                foreach ([false, true] as $isUsed) {
                    $terminalStatus = $this->CheckTerminal($node, $isUsed);

                    if (is_null($terminalStatus) && $index + 1 < count($this->Graph->Edges)) {
                        $childNode = $node->ForkChild($index + 1);
                        $node->SetChild($isUsed, $this->Traverse($childNode, $index + 1));

                        if ($childNode->EndsWithTrue) {
                            $node->EndsWithTrue = true;
                        }
                    } else if ($terminalStatus) {
                        $this->BestVertexOrder = $this->GetVertexOrderFromEdgeIndices($node->UsedEdges);
                        $this->BestWeight = $this->EdgesTotalWeights($node);
                        $node->SetChild($isUsed, $this->TerminalTrue);
                        $node->EndsWithTrue = true;

                        echo $this . ' = ' . $this->BestWeight . PHP_EOL;
                    } else {
                        $node->SetChild($isUsed, $this->TerminalFalse);
                    }
                }

                if (!$node->EndsWithTrue) {
                    unset($node);
                    return $this->TerminalFalse;
                }
            }

            return $node;
        }

        /**
         * @param ZddNode $node
         * @param bool $isUsed
         * @return int|null
         */
        private function CheckTerminal($node, $isUsed) {
            $index = $node->Value;
            if ($index >= count($this->Graph->Edges)) {
                return false;
            }

            $edge = $this->Graph->Edges[$index];

            if ($isUsed) {
                $node->ApplyDegreesComponents($edge);
            }

            $currentWeight = $this->EdgesTotalWeights($node);
            if (!is_null($currentWeight) && $currentWeight >= $this->BestWeight) {
                return false;
            }

            for ($v = 0; $v < $this->Graph->GetVertices(); $v++) {
                if ($node->Degrees[$v] != 2) {
                    return in_array($v, $this->Frontiers[$index + 1]) ? null : false;
                }

                if ($v > 0 && $node->Components[$v] != $node->Components[$v - 1]) {
                    return in_array($v, $this->Frontiers[$index + 1]) || in_array($v - 1, $this->Frontiers[$index + 1]) ?
                        null : false;
                }
            }

            return true;
        }

        /**
         * @param ZddNode $node
         * @return int
         */
        private function EdgesTotalWeights($node) {
            if (!$node->UsedEdges) {
                return null;
            }

            $sum = 0;
            foreach ($node->UsedEdges as $edgeIndex) {
                $sum += $this->Graph->Edges[$edgeIndex]->GetWeight();
            }

            return $sum;
        }

        /**
         * @param int[] $edgeIndices
         * @return int[]
         */
        private function GetVertexOrderFromEdgeIndices($edgeIndices) {
            $vertexOrder = [$this->StartVertex];
            $currentVertex = $vertexOrder[0];
            $nextEdgeIndex = $this->GetNextEdgeIndexFromIndices($currentVertex, $edgeIndices);
            while (!is_null($nextEdgeIndex)) {
                $nextVertex = $this->Graph->Edges[$nextEdgeIndex]->GetAnotherVertex($currentVertex);
                $vertexOrder[] = $nextVertex;
                array_splice($edgeIndices, array_search($nextEdgeIndex, $edgeIndices), 1);

                $currentVertex = $nextVertex;
                $nextEdgeIndex = $this->GetNextEdgeIndexFromIndices($currentVertex, $edgeIndices);
            }

            return $vertexOrder;
        }

        /**
         * @param int $currentVertex
         * @param int[] $indices
         * @return int
         */
        private function GetNextEdgeIndexFromIndices($currentVertex, $indices) {
            foreach ($indices as $index) {
                if ($this->Graph->Edges[$index]->Connects($currentVertex)) {
                    return $index;
                }
            }

            return null;
        }

        /**
         * @return int
         */
        public function GetTotalWeight() {
            return $this->BestWeight;
        }

        /**
         * @return int[]
         */
        public function GetVertexOrder() {
            return $this->BestVertexOrder;
        }

        /**
         * @param ZddNode $node
         * @return string
         */
        public function GetNodeStatus($node) {
            $str = '';
            for ($e = 0; $e < count($this->Graph->Edges); $e++) {
                $str .= in_array($e, $node->UsedEdges) ? '1' : '0';
            }

            return $str;
        }

        public function __toString() {
            return implode(' -> ', $this->Graph->GetVerticesNames($this->BestVertexOrder));
        }

    }

}
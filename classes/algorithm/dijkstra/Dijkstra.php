<?php

namespace TSP\Algorithm {

    use TSP\Model\Graph;
    use TSP\Algorithm\Dijkstra\Node;

    class Dijkstra {
        /**
         * @var int $StartVertex
         */
        public $StartVertex;

        /**
         * @var Node[] $Nodes
         */
        private $Nodes;

        /**
         * @param Graph $graph
         * @param int $startVertex
         */
        public function __construct($graph, $startVertex) {
            $this->StartVertex = $startVertex;
            $this->Nodes = [];
            for ($v = 0; $v < $graph->GetVertices(); $v++) {
                $this->Nodes[$v] = new Node($v);
            }
            $this->Nodes[$this->StartVertex]->Value = 0;

            $this->Calculate($graph);
        }

        /**
         * @param Graph $graph
         */
        private function Calculate($graph) {
            $currentNode = $this->Nodes[$this->StartVertex];

            while ($currentNode && !$this->AllNodeVisited()) {
                foreach ($graph->GetConnectingEdges($currentNode->GetVertex()) as $connectingEdge) {
                    $anotherVertex = $connectingEdge->GetAnotherVertex($currentNode->GetVertex());
                    if ($this->Nodes[$anotherVertex]->Visited) {
                        continue;
                    }

                    $newValue = $currentNode->Value + $connectingEdge->GetWeight();
                    $this->Nodes[$anotherVertex]->CompareAndApply($currentNode->GetVertex(), $newValue);
                }
                $currentNode->Visited = true;

                $currentNode = $this->GetNextNode();
            }
        }

        /**
         * @return bool
         */
        private function AllNodeVisited() {
            foreach ($this->Nodes as $node) {
                if (!$node->Visited) {
                    return false;
                }
            }

            return true;
        }

        /**
         * @return Node
         */
        private function GetNextNode() {
            $nextNode = null;
            $minValue = null;
            foreach ($this->Nodes as $node) {
                if (!$node->Visited && $node->Value) {
                    if (is_null($minValue)) {
                        $minValue = $node->Value;
                        $nextNode = $node;
                    } else if ($node->Value < $minValue) {
                        $minValue = $node->Value;
                        $nextNode = $node;
                    }
                }
            }

            return $nextNode;
        }

        /**
         * @param $vertex
         * @return int
         */
        public function GetValueOf($vertex) {
            return $this->Nodes[$vertex]->Value;
        }
    }
}
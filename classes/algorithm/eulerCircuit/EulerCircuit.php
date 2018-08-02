<?php

namespace TSP\Algorithm {

    use TSP\Model\Edge;
    use TSP\Model\Graph;

    class EulerCircuit {
        /**
         * @var Graph $Graph
         */
        private $Graph;

        /**
         * @var Edge[] $PathEdges
         */
        private $PathEdges;

        /**
         * @var int[] $VertexOrder
         */
        private $VertexOrder;

        /**
         * EulerCircuit constructor.
         * @param Graph $graph
         * @param int $firstVertex
         * @throws OddDegreeEulerCircuitException
         */
        public function __construct($graph, $firstVertex = null) {
            for ($v = 0; $v < $graph->GetVertices(); $v++) {
                if ($graph->GetDegree($v) % 2) {
                    throw new OddDegreeEulerCircuitException();
                }
            }

            $this->Graph = $graph;
            $this->PathEdges = [];
            $this->VertexOrder = [];

            $this->Calculate($firstVertex);
        }

        /**
         * @param int $firstVertex
         */
        private function Calculate($firstVertex) {
            $startVertex = is_null($firstVertex) ? $this->GetFirstVertex() : $firstVertex;
            $subCircuit = [];
            $subOrder = [$startVertex];

            $currentVertex = $startVertex;
            while (count($this->PathEdges) != count($this->Graph->Edges)) {
                $edge = $this->GetNextEdge($currentVertex, $subCircuit);

                $anotherVertex = $edge->GetAnotherVertex($currentVertex);

                $subCircuit[] = $edge;
                $subOrder[] = $anotherVertex;

                $currentVertex = $anotherVertex;

                if ($currentVertex == $startVertex) {
                    $this->JoinCircuit($subCircuit, $subOrder);

                    $startVertex = $this->GetNextStartVertex();
                    $subCircuit = [];
                    $subOrder = [$startVertex];

                    $currentVertex = $startVertex;
                }
            }
        }

        /**
         * @param Edge[] $subCircuitEdges
         * @param int[] $subCircuitVertices
         */
        private function JoinCircuit($subCircuitEdges, $subCircuitVertices) {
            $startVertex = $subCircuitVertices[0];

            $insertIndex = 0;
            foreach ($this->VertexOrder as $index => $vertex) {
                if ($vertex == $startVertex) {
                    $insertIndex = $index + 1;
                    break;
                }
            }

            $edgesToBeInsertedAfter = $insertIndex ? array_splice($this->PathEdges, $insertIndex) : [];
            $verticesToBeInsertedAfter = $insertIndex ? array_splice($this->VertexOrder, $insertIndex) : [];

            if ($insertIndex) {
                $subCircuitVertices = array_splice($subCircuitVertices, 1);
            }

            $this->PathEdges = array_merge($this->PathEdges, $subCircuitEdges, $edgesToBeInsertedAfter);
            $this->VertexOrder = array_merge($this->VertexOrder, $subCircuitVertices, $verticesToBeInsertedAfter);
        }

        /**
         * @return int
         */
        private function GetFirstVertex() {
            for ($v = 0; $v < $this->Graph->GetVertices(); $v++) {
                if ($this->Graph->GetDegree($v)) {
                    return $v;
                }
            }

            return null;
        }

        /**
         * @return int
         */
        private function GetNextStartVertex() {
            foreach ($this->VertexOrder as $vertex) {
                foreach ($this->Graph->GetConnectingEdges($vertex) as $edge) {
                    if (!in_array($edge, $this->PathEdges)) {
                        return $vertex;
                    }
                }
            }

            return null;
        }

        /**
         * @param int $vertex
         * @param Edge[] $currentPath
         * @return Edge
         */
        private function GetNextEdge($vertex, $currentPath) {
            foreach ($this->Graph->GetConnectingEdges($vertex) as $edge) {
                if (!in_array($edge, $this->PathEdges) && !in_array($edge, $currentPath)) {
                    return $edge;
                }
            }

            return null;
        }

        /**
         * @return Edge[]
         */
        public function GetPathEdges() {
            return $this->PathEdges;
        }

        /**
         * @return int[]
         */
        public function GetVertexOrder() {
            return $this->VertexOrder;
        }
    }

}
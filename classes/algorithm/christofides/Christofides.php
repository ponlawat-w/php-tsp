<?php

namespace TSP\Algorithm {

    use TSP\Algorithm\Christofides\PathStep;
    use TSP\Model\Graph;

    class Christofides {
        /**
         * @var PathStep[] $PathEdges
         */
        private $PathEdges;

        /**
         * @var Graph
         */
        private $Graph;

        /**
         * Christofides constructor.
         * @param Graph $graph
         * @param int $startVertex
         * @throws \Exception
         */
        public function __construct($graph, $startVertex = null) {
            $this->Graph = clone $graph;
            $this->PathEdges = [];
            $this->Calculate($startVertex);
        }

        /**
         * @param int $startVertex
         * @throws \Exception
         */
        private function Calculate($startVertex = null) {
            $this->Graph->MakeCompleteGraph();
            $spanningTree = SpanningTree::Create($this->Graph);
            $oddDegreeVertices = self::GetOddDegreeVertices($spanningTree);
            $oddSubGraph = $this->Graph->GetSubGraph($oddDegreeVertices, true);
            $perfectMatching = new MinimumPerfectMatching($oddSubGraph);
            $treeUnionMatching = self::UnionWithMatching($spanningTree, $perfectMatching, $oddSubGraph);
            $euler = new EulerCircuit($treeUnionMatching, $startVertex);
            $vertexOrder = array_values(array_unique($euler->GetVertexOrder()));
            $vertexOrder[] = $vertexOrder[0];
            for ($i = 0; $i < count($vertexOrder) - 1; $i++) {
                $vertex = $vertexOrder[$i];
                $nextVertex = $vertexOrder[$i + 1];

                $edge = $this->Graph->GetEdge($vertex, $nextVertex);
                $this->PathEdges[] = new PathStep($edge, $vertex, $nextVertex);
            }
        }

        /**
         * @param Graph $graph
         * @param MinimumPerfectMatching $matching
         * @param Graph $graphThatProvidedMatching
         * @return Graph
         */
        private function UnionWithMatching($graph, $matching, $graphThatProvidedMatching) {
            $newGraph = clone $graph;
            foreach ($matching->GetMatches() as $match) {
                $v1Name = $graphThatProvidedMatching->GetVertexName($match[0]);
                $v2Name = $graphThatProvidedMatching->GetVertexName($match[1]);

                $v1 = $this->Graph->GetVertexValue($v1Name);
                $v2 = $this->Graph->GetVertexValue($v2Name);

                $edge = $this->Graph->GetEdge($v1, $v2);
                $newGraph->Edges[] = $edge;
            }

            return $newGraph;
        }

        /**
         * @return PathStep[]
         */
        public function GetPathSteps() {
            return $this->GetPathSteps();
        }

        /**
         * @return int[]
         */
        public function GetVertexOrder() {
            if (!count($this->PathEdges)) {
                return [];
            }

            $order = [$this->PathEdges[0]->GetVertices()[0]];
            foreach ($this->PathEdges as $edge) {
                $order[] = $edge->GetVertices()[1];
            }

            return $order;
        }

        /**
         * @return int
         */
        public function GetTotalWeight() {
            $sum = 0;
            foreach ($this->PathEdges as $edge) {
                $sum += $edge->GetWeight();
            }

            return $sum;
        }

        /**
         * @return string
         */
        public function __toString() {
            if (!count($this->PathEdges)) {
                return '';
            }

            $vertices = [$this->Graph->GetVertexName($this->PathEdges[0]->GetVertices()[0])];
            foreach ($this->PathEdges as $edge) {
                $vertices[] = $this->Graph->GetVertexName($edge->GetVertices()[1]);
            }

            return implode(' -> ', $vertices);
        }

        /**
         * @param Graph $graph
         * @return int[]
         */
        private static function GetOddDegreeVertices($graph) {
            $vertices = [];
            for ($v = 0; $v < $graph->GetVertices(); $v++) {
                if ($graph->GetDegree($v) % 2) {
                    $vertices[] = $v;
                }
            }

            return $vertices;
        }
    }

}
<?php

namespace TSP\Model {

    use TSP\Algorithm\Dijkstra;

    class Graph {
        /**
         * @var int $Vertices
         */
        private $Vertices;

        /**
         * @var Edge[] $Edges
         */
        public $Edges;

        /**
         * @var string[] $VerticesMapping
         */
        private $VerticesMapping;

        /**
         * Graph constructor.
         * @param int $vertices
         * @param string|string[] $mapping
         */
        public function __construct($vertices, $mapping = null) {
            $this->Vertices = $vertices;
            $this->Edges = [];

            $this->SetMapping($mapping);
        }

        /**
         * @param Edge $edge
         * @return bool
         */
        public function HasEdge($edge) {
            foreach ($this->Edges as $myEdge) {
                if ($myEdge->EquallyConnects($edge)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @param int $v1
         * @param int $v2
         * @return bool
         */
        public function HasEdgeByVertices($v1, $v2) {
            foreach ($this->Edges as $edge) {
                if ($edge->SameVertices($v1, $v2)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @param int $v1
         * @param int $v2
         * @return Edge|null
         */
        public function GetEdge($v1, $v2) {
            foreach ($this->Edges as $edge) {
                if ($edge->SameVertices($v1, $v2)) {
                    return $edge;
                }
            }

            return null;
        }

        /**
         * @param int $v1
         * @param int $v2
         * @param int $weight
         * @return $this
         */
        public function AddEdge($v1, $v2, $weight = 0) {
            $newEdge = new Edge($v1, $v2, $weight);
            if (!$this->HasEdge($newEdge)) {
                $this->Edges[] = $newEdge;
            }

            return $this;
        }

        /**
         * @param string $v1Name
         * @param string $v2Name
         * @param int $weight
         * @return $this
         */
        public function AddEdgeByName($v1Name, $v2Name, $weight = 0) {
            $v1 = $this->GetVertexValue($v1Name);
            $v2 = $this->GetVertexValue($v2Name);

            if ($v1 > -1 && $v2 > -1) {
                return $this->AddEdge($v1, $v2, $weight);
            }
            return $this;
        }

        /**
         * @return int
         */
        public function GetTotalWeight() {
            $sum = 0;
            foreach ($this->Edges as $edge) {
                $sum += $edge->GetWeight();
            }
            return $sum;
        }

        /**
         * @param string|string[] $mapping
         * @return bool
         */
        public function SetMapping($mapping) {
            if (is_string($mapping) && mb_strlen($mapping) == $this->Vertices) {
                $mappingStr = $mapping;
                $mapping = [];
                for ($i = 0; $i < mb_strlen($mappingStr); $i++) {
                    $mapping[$i] = mb_substr($mappingStr, $i, 1);
                }
            }

            if (!is_array($mapping) || count($mapping) != $this->Vertices) {
                return false;
            }

            $this->VerticesMapping = $mapping;
            return true;
        }

        /**
         * @return string[]
         */
        public function GetMapping() {
            return $this->VerticesMapping;
        }

        /**
         * @return int
         */
        public function GetVertices() {
            return $this->Vertices;
        }

        /**
         * @param int $vertex
         * @return string|int
         */
        public function GetVertexName($vertex) {
            return isset($this->VerticesMapping[$vertex]) ? $this->VerticesMapping[$vertex] : $vertex;
        }

        /**
         * @param string $vertexName
         * @return int
         */
        public function GetVertexValue($vertexName) {
            foreach ($this->VerticesMapping as $index => $name) {
                if ($name == $vertexName) {
                    return $index;
                }
            }

            return -1;
        }

        /**
         * @param int $vertex
         * @return Edge[]
         */
        public function GetConnectingEdges($vertex) {
            $edges = [];
            foreach ($this->Edges as $edge) {
                if ($edge->Connects($vertex)) {
                    $edges[] = $edge;
                }
            }

            return $edges;
        }

        /**
         * @param int $vertex
         * @return int
         */
        public function GetDegree($vertex) {
            $degree = 0;
            foreach ($this->Edges as $edge) {
                if ($edge->Connects($vertex)) {
                    $degree++;
                }
            }

            return $degree;
        }

        /**
         * @return $this
         */
        public function MakeCompleteGraph() {
            for ($vi = 0; $vi < $this->Vertices; $vi++) {

                $dijkstra = null;

                for ($vj = 0; $vj < $this->Vertices; $vj++) {
                    if ($vi == $vj) {
                        continue;
                    }

                    if (!$this->HasEdgeByVertices($vi, $vj)) {
                        if (!$dijkstra) {
                            $dijkstra = new Dijkstra($this, $vi);
                        }

                        $this->AddEdge($vi, $vj, $dijkstra->GetValueOf($vj));
                    }
                }
            }

            return $this;
        }

        /**
         * @return int[][]
         */
        public function GetAdjacencyMatrix() {
            $adjacency = [];
            for ($vi = 0; $vi < $this->Vertices; $vi++) {
                $adjacency[$vi] = [];
                for ($vj = 0; $vj < $this->Vertices; $vj++) {
                    $edge = $this->GetEdge($vi, $vj);
                    $adjacency[$vi][$vj] = $edge ? $edge->GetWeight() : null;
                }
            }

            return $adjacency;
        }

        /**
         * @return string
         */
        public function __toString() {
            $str = "Conceptual Graph\n  size: {$this->Vertices}\n  edges (" . count($this->Edges) . "):\n";
            foreach ($this->Edges as $edge) {
                $str .= "    ({$this->GetVertexName($edge->GetVertices()[0])}, {$this->GetVertexName($edge->GetVertices()[1])}) => {$edge->GetWeight()}\n";
            }

            return $str;
        }
    }

}
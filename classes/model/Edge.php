<?php

namespace TSP\Model {

    class Edge {
        /**
         * @var int[] $Vertices
         */
        private $Vertices;

        /**
         * @var int $Weight
         */
        private $Weight;

        /**
         * Edge constructor.
         * @param int $v1
         * @param int $v2
         * @param int $weight
         */
        public function __construct($v1, $v2, $weight = 1) {
            $this->Vertices = [$v1, $v2];
            $this->Weight = $weight;
        }

        /**
         * @param int $vertex
         * @return bool
         */
        public function Connects($vertex) {
            return in_array($vertex, $this->Vertices);
        }

        /**
         * @return int[]
         */
        public function GetVertices() {
            return $this->Vertices;
        }

        /**
         * @return int
         */
        public function GetWeight() {
            return $this->Weight;
        }

        /**
         * @param int $v1
         * @return int
         */
        public function GetAnotherVertex($v1) {
            if ($v1 == $this->Vertices[0]) {
                return $this->Vertices[1];
            }
            if ($v1 == $this->Vertices[1]) {
                return $this->Vertices[0];
            }

            return -1;
        }

        /**
         * @param Edge $edge
         * @return bool
         */
        public function EquallyConnects($edge) {
            return $this->SameVertices($edge->GetVertices()[0], $edge->GetVertices()[1]);
        }

        /**
         * @param int $v1
         * @param int $v2
         * @return bool
         */
        public function SameVertices($v1, $v2) {
            return ($this->Vertices[0] == $v1 && $this->Vertices[1] == $v2)
                || ($this->Vertices[1] == $v1 && $this->Vertices[0] == $v2);
        }
    }

}
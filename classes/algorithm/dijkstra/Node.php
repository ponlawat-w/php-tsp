<?php

namespace TSP\Algorithm\Dijkstra {

    class Node {
        /**
         * @var int $Vertex
         */
        private $Vertex;

        /**
         * @var int $Value
         */
        public $Value;

        /**
         * @var bool $Visited
         */
        public $Visited;

        /**
         * @var int $Previous
         */
        public $Previous;

        /**
         * Node constructor.
         * @param int $vertex
         * @param int|null $value
         */
        public function __construct($vertex, $value = null) {
            $this->Vertex = $vertex;
            $this->Value = $value;
            $this->Visited = false;
            $this->Previous = null;
        }

        /**
         * @return int
         */
        public function GetVertex() {
            return $this->Vertex;
        }

        /**
         * @param int $vertex
         * @param int $value
         * @return $this
         */
        public function CompareAndApply($vertex, $value) {
            if (is_null($this->Value) || $value < $this->Value) {
                $this->Value = $value;
                $this->Previous = $vertex;
            }

            return $this;
        }
    }

}
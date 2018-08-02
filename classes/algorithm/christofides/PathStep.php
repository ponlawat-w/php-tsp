<?php

namespace TSP\Algorithm\Christofides {

    use TSP\Algorithm\PathStepIncorrectFromToException;
    use TSP\Model\Edge;

    class PathStep extends Edge {
        private $Direction;

        /**
         * PathStep constructor.
         * @param Edge $edge
         * @param int $from
         * @param int $to
         * @throws PathStepIncorrectFromToException
         */
        public function __construct($edge, $from, $to) {
            if (!$edge || !$edge->SameVertices($from, $to)) {
                throw new PathStepIncorrectFromToException();
            }

            parent::__construct($edge->GetVertices()[0], $edge->GetVertices()[1], $edge->GetWeight());
            $this->Direction = $edge->GetVertices()[0] == $from ? 1 : -1;
        }

        /**
         * @return int[]
         */
        public function GetVertices() {
            return $this->Direction > 0 ? $this->Vertices : array_reverse($this->Vertices);
        }
    }

}
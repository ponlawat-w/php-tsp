<?php

namespace TSP\Algorithm {

    class OddVerticesMatchingException extends \Exception {
        public function __construct() {
            parent::__construct('Vertices must be even number to perform perfect matching');
        }
    }

    class IncompleteGraphMatchingException extends \Exception {
        public function __construct() {
            parent::__construct('Graph must be a complete graph to perform perfect matching');
        }
    }

}
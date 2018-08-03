<?php

namespace TSP\Model {

    use TSP\Model\BinaryTree\BinaryNode;

    class BinaryTree {

        /**
         * @var BinaryNode $RootNode
         */
        private $RootNode;

        /**
         * BinaryTree constructor.
         * @param BinaryNode $rootNode
         */
        public function __construct($rootNode) {
            $this->RootNode = $rootNode;
        }

        /**
         * @return BinaryNode
         */
        public function GetRootNode() {
            return $this->RootNode;
        }

    }

}
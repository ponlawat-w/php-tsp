<?php

namespace TSP\Model\BinaryTree {

    class BinaryNode {

        /**
         * @var mixed $Value
         */
        public $Value;

        /**
         * @var BinaryNode[] $Children
         */
        private $Children;

        /**
         * BinaryNode constructor.
         * @param mixed $value
         */
        public function __construct($value) {
            $this->Value = $value;
            $this->Children = [null, null];
        }

        /**
         * @param int|bool $whichChild
         * @param BinaryNode $childNode
         */
        public function SetChild($whichChild, $childNode) {
            $this->Children[$whichChild ? 1 : 0] = $childNode;
        }

        /**
         * @param int|bool $whichChild
         * @return BinaryNode
         */
        public function GetChild($whichChild) {
            return $this->Children[$whichChild ? 1 : 0];
        }

        /**
         * @return bool
         */
        public function HasChild() {
            return ($this->Children[0] || $this->Children[1]) ? true : false;
        }

        /**
         * @return bool
         */
        public function HasTwoChildren() {
            return ($this->Children[0] && $this->Children[1]) ? true : false;
        }

        /**
         * @return string
         */
        public function __toString() {
            if (is_bool($this->Value)) {
                return $this->Value ? 'true' : 'false';
            }

            $str = $this->Value->__toString();
            return is_string($str) ? $str : 'BinaryNodeObject';
        }

    }

}
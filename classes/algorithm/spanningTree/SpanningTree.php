<?php

namespace TSP\Algorithm {

    use TSP\Model\Edge;
    use TSP\Model\Graph;

    class SpanningTree {

        /**
         * @param Graph $graph
         * @return bool
         */
        public static function Check($graph) {
            return $graph->GetVertices() - 1 == count($graph->Edges);
        }

        /**
         * @param int[] $component
         * @param int $index1
         * @param int $index2
         */
        private static function CombineComponent(&$component, $index1, $index2) {
            $minComponent = min($component[$index1], $component[$index2]);
            $maxComponent = max($component[$index1], $component[$index2]);
            foreach ($component as $componentIndex => $componentValue){
                if ($componentValue == $maxComponent) {
                    $component[$componentIndex] = $minComponent;
                }
            }
        }

        /**
         * @param Graph $graph
         * @return Graph
         */
        public static function Create($graph) {
            $tree = new Graph($graph->GetVertices(), $graph->GetMapping());
            $tree->Edges = $graph->Edges;
            usort($tree->Edges, function(Edge $edgeA, Edge $edgeB) { return $edgeA->GetWeight() > $edgeB->GetWeight(); });

            $components = [];
            for ($v = 0; $v < $tree->GetVertices(); $v++) {
                $components[$v] = $v;
            }

            $edges = [];
            foreach ($tree->Edges as $index => $edge) {
                $vertices = $edge->GetVertices();
                if ($components[$vertices[0]] != $components[$vertices[1]]) {
                    $edges[] = $edge;
                    self::CombineComponent($components, $vertices[0], $vertices[1]);
                }
            }
            $tree->Edges = $edges;

            return $tree;
        }
    }
}
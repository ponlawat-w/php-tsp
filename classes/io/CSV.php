<?php

namespace TSP\IO {

    use TSP\Model\Edge;
    use TSP\Model\Graph;

    class CSV {
        /**
         * @param string $csvFilePath
         * @return Graph
         */
        public static function Import($csvFilePath) {
            $file = fopen($csvFilePath, 'r');
            if (!$file) {
                return null;
            }
            $mapping = [];
            $edges = [];
            while ($row = fgetcsv($file)) {
                $from = $row[0];
                $to = $row[1];
                $distance = $row[2];

                if (!in_array($from, $mapping)) {
                    $mapping[] = $from;
                }
                if (!in_array($to, $mapping)) {
                    $mapping[] = $to;
                }

                $edges[] = new Edge(array_search($from, $mapping), array_search($to, $mapping), $distance);
            }
            fclose($file);

            $graph = new Graph(count($mapping), $mapping);
            $graph->Edges = $edges;
            return $graph;
        }

        /**
         * @param Graph $graph
         * @param string $csvFilePath
         * @return bool
         */
        public static function Export($graph, $csvFilePath) {
            $file = fopen($csvFilePath, 'w');
            if (!$file) {
                return false;
            }

            foreach ($graph->Edges as $edge) {
                fputcsv($file, [
                    $graph->GetVertexName($edge->GetVertices()[0]),
                    $graph->GetVertexName($edge->GetVertices()[1]),
                    $edge->GetWeight()
                ]);
            }
            fclose($file);

            return true;
        }
    }

}
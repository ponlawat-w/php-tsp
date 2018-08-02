<?php
require_once(__DIR__ . '/../../loader.php');

$graph = new \TSP\Model\Graph(6, 'abcdef');
$graph->AddEdgeByName('a', 'b')
    ->AddEdgeByName('a', 'e')
    ->AddEdgeByName('b', 'd')
    ->AddEdgeByName('b', 'e')
    ->AddEdgeByName('b', 'f')
    ->AddEdgeByName('c', 'f')
    ->AddEdgeByName('c', 'd')
    ->AddEdgeByName('d', 'f')
    ->AddEdgeByName('d', 'e')
    ->AddEdgeByName('e', 'f');

try {
    $eulerCircuit = new \TSP\Algorithm\EulerCircuit($graph);

    var_dump($eulerCircuit->GetVertexOrder());

} catch(\TSP\Algorithm\OddDegreeEulerCircuitException $ex) {
    echo 'Exception Thrown!' . PHP_EOL;
}

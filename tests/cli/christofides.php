<?php
require_once(__DIR__ . '/../../loader.php');

$graph = new \TSP\Model\Graph(6, 'abcdef');
$graph->AddEdgeByName('a', 'b', 1)
    ->AddEdgeByName('a', 'd', 2)
    ->AddEdgeByName('b', 'c', 3)
    ->AddEdgeByName('b', 'd', 5)
    ->AddEdgeByName('d', 'e', 4)
    ->AddEdgeByName('c', 'e', 6)
    ->AddEdgeByName('c', 'f', 2)
    ->AddEdgeByName('e', 'f', 1);

try {
    $christofides = new \TSP\Algorithm\Christofides($graph);
    echo $christofides . ' = ' . $christofides->GetTotalWeight() . PHP_EOL;
} catch (Exception $ex) {
    echo $ex->getMessage() . PHP_EOL;
    exit;
}

$graph = new \TSP\Model\Graph(9, 'abcdefghi');
$graph->AddEdgeByName('a', 'b', 2)
    ->AddEdgeByName('b', 'c', 4)
    ->AddEdgeByName('c', 'd', 2)
    ->AddEdgeByName('d', 'e', 1)
    ->AddEdgeByName('e', 'f', 6)
    ->AddEdgeByName('f', 'a', 7)
    ->AddEdgeByName('a', 'g', 3)
    ->AddEdgeByName('b', 'g', 6)
    ->AddEdgeByName('g', 'i', 1)
    ->AddEdgeByName('g', 'h', 3)
    ->AddEdgeByName('f', 'i', 5)
    ->AddEdgeByName('i', 'h', 4)
    ->AddEdgeByName('h', 'c', 2)
    ->AddEdgeByName('i', 'e', 2)
    ->AddEdgeByName('h', 'd', 8);


try {
    $christofides = new \TSP\Algorithm\Christofides($graph, $graph->GetVertexValue('g'));
    echo $christofides . ' = ' . $christofides->GetTotalWeight() . PHP_EOL;
} catch (Exception $ex) {
    echo $ex->getMessage() . PHP_EOL;
    exit;
}
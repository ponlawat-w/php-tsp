<?php
namespace TSP {
    require_once(__DIR__ . '/../../loader.php');
    $graph = new Model\Graph(9, ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
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

    echo $graph;
}
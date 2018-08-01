<?php
namespace algorithm\dijkstra;
use TSP\Algorithm\Dijkstra;
use TSP\Model\Graph;
use PHPUnit\Framework\TestCase;

class DijkstraTest extends TestCase
{
    public function testGetValueOf()
    {
        $graph = new Graph(9, 'abcdefghi');
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

        $dijkstraA = new Dijkstra($graph, $graph->GetVertexValue('a'));
        $this->assertEquals($dijkstraA->GetValueOf($graph->GetVertexValue('i')), 4);
        $this->assertEquals($dijkstraA->GetValueOf($graph->GetVertexValue('d')), 7);

        $dijkstraC = new Dijkstra($graph, $graph->GetVertexValue('c'));
        $this->assertEquals($dijkstraC->GetValueOf($graph->GetVertexValue('a')), 6);
        $this->assertEquals($dijkstraC->GetValueOf($graph->GetVertexValue('f')), 9);
    }
}

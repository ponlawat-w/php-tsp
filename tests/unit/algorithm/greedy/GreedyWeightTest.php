<?php
namespace algorithm\greedy;

use TSP\Algorithm\GreedyWeight;
use PHPUnit\Framework\TestCase;
use TSP\Model\Graph;

class GreedyWeightTest extends TestCase
{

    public function testGetTotalWeight()
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

        $greedy = new GreedyWeight($graph, $graph->GetVertexValue('g'));
        $this->assertLessThan($graph->GetTotalWeight(), $greedy->GetTotalWeight());
    }
}

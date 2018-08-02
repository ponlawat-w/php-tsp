<?php

namespace model;

use TSP\Model\Graph;
use TSP\Algorithm\SpanningTree;
use PHPUnit\Framework\TestCase;

class SpanningTreeTest extends TestCase
{

    public function testCreate()
    {
        $graph = new Graph(6, 'abcdef');
        $graph->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('a', 'd', 2)
            ->AddEdgeByName('b', 'c', 3)
            ->AddEdgeByName('b', 'd', 5)
            ->AddEdgeByName('d', 'e', 4)
            ->AddEdgeByName('c', 'e', 6)
            ->AddEdgeByName('c', 'f', 2)
            ->AddEdgeByName('e', 'f', 1);

        $spanningTree = SpanningTree::Create($graph);
        $this->assertTrue(SpanningTree::Check($spanningTree));
        $this->assertEquals(9, $spanningTree->GetTotalWeight());

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

        $spanningTree = SpanningTree::Create($graph);
        $this->assertTrue(SpanningTree::Check($spanningTree));
        $this->assertEquals(18, $spanningTree->GetTotalWeight());
    }

    public function testCheck()
    {
        $graph = new Graph(6, 'abcdef');
        $graph->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('a', 'd', 2)
            ->AddEdgeByName('b', 'c', 3)
            ->AddEdgeByName('b', 'd', 5)
            ->AddEdgeByName('d', 'e', 4)
            ->AddEdgeByName('c', 'e', 6)
            ->AddEdgeByName('c', 'f', 2)
            ->AddEdgeByName('e', 'f', 1);

        $this->assertFalse(SpanningTree::Check($graph));

        $tree = new Graph(5, 'abcde');
        $tree->AddEdgeByName('a', 'e', 2);
        $tree->AddEdgeByName('b', 'e', 1);
        $tree->AddEdgeByName('c', 'e', 4);
        $tree->AddEdgeByName('d', 'e', 1);

        $this->assertTrue(SpanningTree::Check($tree));
    }
}

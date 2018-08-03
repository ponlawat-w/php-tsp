<?php

namespace algorithm\zddFrontier\hamilton;

use TSP\Algorithm\ZddFrontierHamilton;
use PHPUnit\Framework\TestCase;
use TSP\Model\Graph;

class ZddFrontierHamiltonTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function test__construct()
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

        $zdd = new ZddFrontierHamilton($graph, $graph->GetVertexValue('f'));
        $this->assertNotNull($zdd->GetTotalWeight());
    }
}

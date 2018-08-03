<?php

namespace algorithm\glnpso;

use TSP\Algorithm\GLNPSO;
use PHPUnit\Framework\TestCase;
use TSP\Model\Graph;

class GLNPSOTest extends TestCase
{

    public function testCalculate()
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

        $glnpso = new GLNPSO($graph, $graph->GetVertexValue('g'), 200);
        $glnpso->Iteration = 500;
        $glnpso->PrintCalculationStatus = false;
        $glnpso->Calculate();
        $this->assertLessThan($graph->GetTotalWeight(), $glnpso->GetBestCost());
    }
}

<?php
namespace conceptual;

use TSP\Model\Graph;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase
{

    public function testGetAdjacencyMatrix()
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

        $adjacency = $graph->GetAdjacencyMatrix();
        $this->assertNull($adjacency[$graph->GetVertexValue('a')][$graph->GetVertexValue('f')]);

        $graph->MakeCompleteGraph();
        $adjacency = $graph->GetAdjacencyMatrix();
        $this->assertGreaterThan(0, $adjacency[$graph->GetVertexValue('a')][$graph->GetVertexValue('f')]);

        $this->assertGreaterThan($adjacency[$graph->GetVertexValue('a')][$graph->GetVertexValue('d')], $adjacency[$graph->GetVertexValue('a')][$graph->GetVertexValue('e')]);

        for ($i = 0; $i < count($adjacency); $i++) {
            for ($j = 0; $j < count($adjacency[$i]); $j++) {
                if ($i == $j) {
                    continue;
                }

                $this->assertNotNull($adjacency[$i][$j]);
                $this->assertGreaterThanOrEqual(0, $adjacency[$i][$j]);
            }
        }
    }
}

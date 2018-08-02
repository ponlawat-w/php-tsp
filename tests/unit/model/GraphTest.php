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

    public function testGetSubGraph() {
        $graph = new Graph(6, 'abcdef');
        $graph->AddEdgeByName('a', 'b', 2)
            ->AddEdgeByName('a', 'c', 3)
            ->AddEdgeByName('b', 'c', 4)
            ->AddEdgeByName('b', 'd', 5)
            ->AddEdgeByName('b', 'e', 4)
            ->AddEdgeByName('c', 'e', 2)
            ->AddEdgeByName('c', 'f', 1)
            ->AddEdgeByName('d', 'e', 3);
        $subGraph = $graph->GetSubGraphByNames(['b', 'd', 'e']);

        $this->assertEquals(3, $subGraph->GetVertices());
        $this->assertEquals(3, count($subGraph->Edges));
        $this->assertEquals(12, $subGraph->GetTotalWeight());

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

        $subGraph = $graph->GetSubGraphByNames(['a', 'b', 'g', 'h', 'i']);
        $this->assertEquals(5, $subGraph->GetVertices());
        $this->assertEquals(6, count($subGraph->Edges));
        $this->assertEquals(19, $subGraph->GetTotalWeight());

        $subGraph = $graph->GetSubGraphByNames(['a', 'b', 'g', 'h', 'i'], true);
        $this->assertEquals(5, $subGraph->GetVertices());
        $this->assertEquals(10, count($subGraph->Edges));
        $this->assertEquals(43, $subGraph->GetTotalWeight());
    }
}

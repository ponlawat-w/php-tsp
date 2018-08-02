<?php
namespace algorithm\christofides;

use TSP\Algorithm\Christofides;
use PHPUnit\Framework\TestCase;
use TSP\Model\Graph;

class ChristofidesTest extends TestCase
{
    /**
     * @var Graph[] $Graphs
     */
    private $Graphs;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->Graphs = [];

        $this->Graphs[0] = new Graph(6, 'abcdef');
        $this->Graphs[0]->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('a', 'd', 2)
            ->AddEdgeByName('b', 'c', 3)
            ->AddEdgeByName('b', 'd', 5)
            ->AddEdgeByName('d', 'e', 4)
            ->AddEdgeByName('c', 'e', 6)
            ->AddEdgeByName('c', 'f', 2)
            ->AddEdgeByName('e', 'f', 1);

        $this->Graphs[1] = new Graph(9, 'abcdefghi');
        $this->Graphs[1]->AddEdgeByName('a', 'b', 2)
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
    }

    public function testGetVertexOrder()
    {
        try {
            foreach ($this->Graphs as $graph) {
                $christofides = new Christofides($graph, 0);
                $this->assertEquals($graph->GetVertices() + 1, count($christofides->GetVertexOrder()));
                $this->assertEquals($graph->GetVertices(), count(array_unique($christofides->GetVertexOrder())));
            }
        } catch (\Exception $ex) {
            $this->assertTrue(false);
        }
    }

    public function testGetTotalWeight()
    {
        try {
            foreach ($this->Graphs as $graph) {
                $christofides = new Christofides($graph, $graph->GetVertices() - 1);
                $weight = $christofides->GetTotalWeight();
                $this->assertGreaterThan(0, $weight);
                $this->assertLessThan($graph->GetTotalWeight(), $weight);
            }
        } catch (\Exception $ex) {
            $this->assertTrue(false);
        }
    }
}

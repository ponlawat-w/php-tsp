<?php
namespace algorithm\eulerCircuit;

use TSP\Algorithm\EulerCircuit;
use PHPUnit\Framework\TestCase;
use TSP\Algorithm\OddDegreeEulerCircuitException;
use TSP\Model\Graph;

class EulerCircuitTest extends TestCase
{
    public function test__construct()
    {
        $graph = new Graph(4, 'abcd');
        $graph->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('b', 'c', 1)
            ->AddEdgeByName('c', 'd', 1);

        try {
            $euler = new EulerCircuit($graph);
            $euler->GetVertexOrder();
            $exceptionThrown = false;
        } catch (OddDegreeEulerCircuitException $ex) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);

        $graph->AddEdgeByName('d', 'a', 1);
        try {
            $euler = new EulerCircuit($graph);
            $euler->GetVertexOrder();
            $exceptionThrown = false;
        } catch (OddDegreeEulerCircuitException $ex) {
            $exceptionThrown = true;
        }
        $this->assertFalse($exceptionThrown);

        $graph = new Graph(5, 'abcde');
        $graph->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('a', 'c', 1)
            ->AddEdgeByName('a', 'd', 1)
            ->AddEdgeByName('a', 'e', 1)
            ->AddEdgeByName('e', 'd', 1)
            ->AddEdgeByName('c', 'b', 1);
        try {
            $euler = new EulerCircuit($graph);
            $this->assertEquals(count($graph->Edges), count($euler->GetPathEdges()));

        } catch (OddDegreeEulerCircuitException $ex) {
            $this->assertTrue(false);
        }
    }
}

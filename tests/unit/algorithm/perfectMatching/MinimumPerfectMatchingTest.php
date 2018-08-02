<?php
namespace algorithm\perfectMatching;

use TSP\Algorithm\MinimumPerfectMatching;
use PHPUnit\Framework\TestCase;
use TSP\Algorithm\OddVerticesMatchingException;
use TSP\Model\Graph;

class MinimumPerfectMatchingTest extends TestCase
{

    public function testGetMatches()
    {
        $graph = new Graph(5, 'abcde');
        $graph->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('a', 'c', 1)
            ->AddEdgeByName('c', 'd', 1)
            ->AddEdgeByName('b', 'd', 1)
            ->AddEdgeByName('e', 'a', 1)
            ->AddEdgeByName('e', 'b', 1)
            ->AddEdgeByName('e', 'c', 1)
            ->AddEdgeByName('e', 'd', 1);

        try {
            $matching = new MinimumPerfectMatching($graph);
            $matching->GetMatches();
            $exceptionThrown = false;
        }
        catch (\Exception $ex) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);

        $graph = new Graph(4, 'abcd');
        $graph->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('b', 'c', 1)
            ->AddEdgeByName('c', 'd', 1)
            ->AddEdgeByName('d', 'a', 1)
            ->AddEdgeByName('a', 'c', 2)
            ->AddEdgeByName('b', 'd', 2);

        try {
            $matching = new MinimumPerfectMatching($graph);
            $this->assertEquals(2, count($matching->GetMatches()));
            $exceptionThrown = false;
        } catch (\Exception $ex) {
            $exceptionThrown = true;
        }

        $this->assertFalse($exceptionThrown);
    }

    public function testGetWeightSummation() {
        $graph = new Graph(4, 'abcd');
        $graph->AddEdgeByName('a', 'b', 1)
            ->AddEdgeByName('b', 'c', 1)
            ->AddEdgeByName('c', 'd', 1)
            ->AddEdgeByName('d', 'a', 1)
            ->AddEdgeByName('a', 'c', 2)
            ->AddEdgeByName('b', 'd', 2);

        try {
            $matching = new MinimumPerfectMatching($graph);
            $this->assertEquals(2, $matching->GetWeightSummation());
        } catch (\Exception $ex) {
            $this->assertTrue(false);
        }

        $graph = new Graph(6, 'abcdef');
        $graph->AddEdgeByName('a', 'b', 2)
            ->AddEdgeByName('a', 'c', 3)
            ->AddEdgeByName('b', 'c', 4)
            ->AddEdgeByName('b', 'd', 5)
            ->AddEdgeByName('b', 'e', 4)
            ->AddEdgeByName('c', 'e', 2)
            ->AddEdgeByName('c', 'f', 1)
            ->AddEdgeByName('d', 'e', 3);
        $graph->MakeCompleteGraph();

        try {
            $matching = new MinimumPerfectMatching($graph);
            $this->assertEquals(6, $matching->GetWeightSummation());
        } catch (\Exception $ex) {
            $this->assertTrue(false);
        }
    }
}

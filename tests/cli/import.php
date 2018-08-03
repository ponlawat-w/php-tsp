<?php
require_once(__DIR__ . '/../../loader.php');

echo 'Importing...';
$graph = \TSP\IO\CSV::Import(__DIR__ . '/csv/soc-sign-bitcoinalpha.csv');
echo 'Done!' . PHP_EOL;

$v = [];
for ($i = 1; $i <= 25; $i++) {
    $v[] = $i;
}

echo 'Creating sub-graph...';
$graph = $graph->GetSubGraphByNames($v);
echo 'Done!' . PHP_EOL;

echo 'Exporting...';
\TSP\IO\CSV::Export($graph, __DIR__ . '/csv/exported.csv');
echo 'Done!' . PHP_EOL;
<?php

use React\Stream\Stream;
use React\EventLoop\Factory;
use Legionth\React\LineReader\LineReader;

require __DIR__ . '/../vendor/autoload.php';

$loop = Factory::create();

// Create a input stream
$lineReader = new LineReader();

$input = new Stream(STDIN, $loop);
$input->pipe($lineReader);

// Use a seperate output stream
$output = new Stream(STDOUT, $loop);
// The loop shouldn't wait for an output stream, so set this stream on pause
$output->pause();

$lineReader->pipe($output);

$loop->run();

<?php
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;
use Inhere\Console\Application;

$meta = [
    'name' => 'My Console App',
    'version' => '1.0.2',
];
$input = new Input;
$output = new Output;
$app = new Application($meta, $input, $output);

// add command routes
$app->command('demo', function (Input $in, Output $out) {
    $cmd = $in->getCommand();

    $out->info('hello, this is a test command: ' . $cmd);
});
// ... ...

// run
$app->run();

<?php

require_once 'IO/PNG.php';

$options = getopt("f:s");

function usage() {
    echo "Usage: php pngchunk.php -f <pngfile> # list".PHP_EOL;
    echo "Usage: php pngchunk.php -f <pngfile> -s # split".PHP_EOL;
}

if ((isset($options['f']) === false) ||
    (is_readable($options['f']) === false)) {
    usage();
    exit(1);
}

$pngfile = $options['f'];
$pngdata = file_get_contents($pngfile);

$png = new IO_PNG();
$png->parse($pngdata);

if (isset($options['s'])) {
    foreach ($png->_chunkList as $idx => $chunk) {
        $chunkName = $chunk['Name'];
        $chunkOffset = $chunk['_offset'];
        $chunkLength = $chunk['_length'];
        $filename = sprintf("%02d_%s.pnc", $idx, $chunkName);
        $data = substr($png->_pngdata, $chunkOffset, $chunkLength);
        file_put_contents($filename, $data);
    }
} else {
    // list only
    foreach ($png->_chunkList as $idx => $chunk) {
        $chunkName = $chunk['Name'];
        $chunkOffset = $chunk['_offset'];
        $chunkLength = $chunk['_length'];
        echo "{$pngfile}[$idx] $chunkName offset:$chunkOffset length:$chunkLength".PHP_EOL;
    }
}

exit(0);

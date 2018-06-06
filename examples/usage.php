<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

include_once __DIR__ . '/../vendor/autoload.php';

use xobotyi\basen\BaseN;

$conv   = new BaseN('abcdefghijklmnopqrstuvwxyz234567', false, true, true);
$base64 = new BaseN('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', true, true, true);

var_dump($base64->decode(base64_encode('Hello world!')));

$benchmark_start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    base64_encode('Hello world!');
}
echo "The script took " . (microtime(true) - $benchmark_start) . " seconds\n";

$benchmark_start = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    $base64->encode('Hello world!');
}
echo "The script took " . (microtime(true) - $benchmark_start) . " seconds\n";
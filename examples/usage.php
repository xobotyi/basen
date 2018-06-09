<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

include_once __DIR__ . '/../vendor/autoload.php';

$new = new \xobotyi\basen\BaseN('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', false, true);

var_dump($new->decodeRough($new->encodeRough('f')));
var_dump(\xobotyi\basen\Base32::encode('f'));
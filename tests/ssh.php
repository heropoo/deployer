<?php
/**
 * User: heropoo
 * Datetime: 2022/6/12 6:22 下午
 */

require_once __DIR__.'/../vendor/autoload.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;


//$ssh = new SSH2('127.0.0.1', 22);
//$res = $ssh->getServerPublicHostKey();
//var_dump($res);
$key = PublicKeyLoader::loadPrivateKey(file_get_contents('/root/.ssh/id_rsa'));
var_dump($key);
$ssh = new SSH2('127.0.0.1', 22);
$res = $ssh->login('root', $key);
var_dump($res);

<?php
require_once '../lib/class/comm/class.ApkParser.php';
$p = new ApkParser();
$res = $p->open('E:\PHPworkspace\MyGame\view\tieyou.apk');
echo $p->getXML();
?>
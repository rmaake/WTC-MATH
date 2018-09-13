<?php
require_once './header.php';
if ($argc != 2)
    showError(0);
// I know you asking yourself, "why so many objects and classes?", well two reasons really, 1; because I can. 2; reason 1;
$obj = new terms();
$lftObj = new getLeft();
$rghtObj = new getRight();
$sln = new Solve();
$tool = new Tools();

$str = explode("=", $argv[1]);
if ($tool->getSize($str) != 2 || empty($str[1]) || empty($str[0]))
{
    echo "Incorrect string format i.e equation invalid\nProgram exiting...\n";
    exit(1);
}
$lftObj->get_left($str[0], $obj);
$rghtObj->get_right($str[1], $obj);
$sln->resolve($obj);
?>
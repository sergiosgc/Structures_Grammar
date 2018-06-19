--TEST--
Create a Structures_Grammar_Symbol
--FILE--
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$symbol = \sergiosgc\Structures_Grammar_Symbol::create('A');
print((string) $symbol);
?>
--EXPECT--
A

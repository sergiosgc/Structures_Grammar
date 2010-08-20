--TEST--
Create a Structures_Grammar_Symbol
--FILE--
<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../') . ':' .
                        ini_get('include_path'));
require_once('Structures/Grammar/Symbol.php');
$symbol = Structures_Grammar_Symbol::create('A');
print((string) $symbol);
?>
--EXPECT--
A
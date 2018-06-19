--TEST--
Create a Structures_Grammar_Rule
--FILE--
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$rule = new \sergiosgc\Structures_Grammar_Rule();
$symbol = \sergiosgc\Structures_Grammar_Symbol::create('A');
$symbol->setTerminal(false);
$rule->addSymbolToLeft($symbol);
$symbol = \sergiosgc\Structures_Grammar_Symbol::create('b');
$symbol->setTerminal(true);
$rule->addSymbolToRight($symbol);
$symbol = \sergiosgc\Structures_Grammar_Symbol::create('B');
$symbol->setTerminal(false);
$rule->addSymbolToRight($symbol);
print($rule);
?>
--EXPECT--
A->bB

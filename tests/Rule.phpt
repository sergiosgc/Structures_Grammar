--TEST--
Create a Structures_Grammar_Rule
--FILE--
<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../') . ':' .
                        ini_get('include_path'));
require_once('Structures/Grammar/Symbol.php');
require_once('Structures/Grammar/Rule.php');
$rule = new Structures_Grammar_Rule();
$symbol = Structures_Grammar_Symbol::create('A');
$symbol->setTerminal(false);
$rule->addSymbolToLeft($symbol);
$symbol = Structures_Grammar_Symbol::create('b');
$symbol->setTerminal(true);
$rule->addSymbolToRight($symbol);
$symbol = Structures_Grammar_Symbol::create('B');
$symbol->setTerminal(false);
$rule->addSymbolToRight($symbol);
print($rule);
?>
--EXPECT--
A->bB

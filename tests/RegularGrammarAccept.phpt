--TEST--
Test acceptance on a regular-restricted Structures_Grammar
--FILE--
<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../') . ':' .
                        ini_get('include_path'));
require_once('Structures/Grammar/Symbol.php');
require_once('Structures/Grammar/Rule.php');
require_once('Structures/Grammar.php');
$grammar = new Structures_Grammar(true, true);
$grammar->addTerminal(Structures_Grammar_Symbol::create('b'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(Structures_Grammar_Symbol::create('B'));

$rule = new Structures_Grammar_Rule();
$rule->addSymbolToLeft(Structures_Grammar_Symbol::create('A'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(Structures_Grammar_Symbol::create('B'));
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] A->bB

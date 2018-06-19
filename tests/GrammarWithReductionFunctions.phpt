--TEST--
Create a Structures_Grammar with some reduction functions
--FILE--
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$grammar = new \sergiosgc\Structures_Grammar(false, false);
$grammar->addTerminal(\sergiosgc\Structures_Grammar_Symbol::create('b'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('A'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$grammar->addNonTerminal(\sergiosgc\Structures_Grammar_Symbol::create('S'));

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('A'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$rule->addReductionFunctionSymbolmap(0, 'A');
$rule->addReductionFunctionSymbolmap(1, 'C');
$rule->setReductionFunction(<<<EOS
return \$A . \$C;
EOS
);
$grammar->addRule($rule);

$rule = new \sergiosgc\Structures_Grammar_Rule();
$rule->addSymbolToLeft(\sergiosgc\Structures_Grammar_Symbol::create('S'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('b'));
$rule->addSymbolToRight(\sergiosgc\Structures_Grammar_Symbol::create('B'));
$rule->addReductionFunctionSymbolmap(0, 'xpto');
$rule->addReductionFunctionSymbolmap(1, 'foobar');
$rule->setReductionFunction(<<<EOS
return \$xpto || \$foobar;
EOS
);
$grammar->addRule($rule);

print($grammar);
?>
--EXPECT--
[0] A->bB
[1] S->bB

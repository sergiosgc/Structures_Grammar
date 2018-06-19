<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
namespace sergiosgc;

/**
 * Structures_Grammar is a representation of a formal generative grammar. The 
 * data structure represents grammars as proposed by Noam Chomsky, as:
 *  a) A set of non-terminal symbols (N)
 *  b) A set of terminal symbols (T)
 *  c) A set of production rules of the form (N U T)*N(N U T)* -> (N U T)*
 *     where U is the set union operator and * is the Kleene star operator (the one
 *     used in regexps).
 *  d) A start symbol S that is a member of N
 * The class automatically restricts sets N and T to be disjoint.
 *
 * The data structure allows for the grammar to be restricted to a context-free grammar
 * and to a regular grammar. You can also test any grammar to check that it is context-free
 * or regular.
 */
class Structures_Grammar
{
    protected $nonTerminals = null;
    protected $terminals = null;
    protected $rules = array();
    protected $startSymbol = null;
    protected $contextFree = false;
    protected $regular = false;
    protected $nullableSymbolSet = null;

    /* getStartSymbol {{{ */
    /**
     * Start symbol getter
     *
     * @return Structures_Grammar_Symbol The start symbol
     */
    public function getStartSymbol()
    {
        if (is_null($this->startSymbol) && $this->isContextFree() && count($this->rules) > 0) {
            $this->startSymbol = $this->rules[0]->getLeftSymbol(0);
        }
        return $this->startSymbol;
    }
    /* }}} */
    /* computeTerminals {{{ */
    public function computeTerminals()
    {
        $this->nonTerminals = array();
        $this->terminals = array();
        foreach ($this->rules as $rule) {
            for ($i=0; $i < $rule->leftCount(); $i++) $this->nonTerminals[] = $rule->getLeftSymbol($i);
        }
        foreach ($this->rules as $rule) {
            for ($i=0; $i < $rule->rightCount(); $i++) if (!$this->isNonTerminal($rule->getRightSymbol($i)) && !$this->isTerminal($rule->getRightSymbol($i))) $this->terminals[] = $rule->getRightSymbol($i);
        }
        for ($i=count($this->nonTerminals) - 1; $i>=0; $i--) $this->nonTerminals[$i]->setNonTerminal();
        for ($i=count($this->terminals) - 1; $i>=0; $i--) $this->terminals[$i]->setTerminal();
    }
    /* }}} */
    /* addNonTerminal {{{ */
    /**
     * NonTerminals addition accessor
     * 
     * @param Structures_Grammar_Symbol New value
     * @return Structures_Grammar_Symbol The added symbol
     */
    public function addNonTerminal($value)
    {
        $this->nonTerminals[] = $value;
        $value->setTerminal(false);
    }
    /* }}} */
    /* isNonTerminal {{{ */
    /**
     * Test value for NonTerminals membership
     * 
     * @param Structures_Grammar_Symbol Value to be tested
     * @return boolean True iff value is a member of nonTerminals
     */
    public function isNonTerminal($value)
    {
        if (is_null($this->nonTerminals)) $this->computeTerminals();
        if (!$value instanceof Structures_Grammar_Symbol) $value = Structures_Grammar_Symbol::create($value);
        foreach ($this->nonTerminals as $cursor) if ($value->__equals($cursor)) return true;
        return false;
    }
    /* }}} */
    /* getNonTerminals {{{ */
    /**
     * NonTerminals getter
     * 
     * @return boolean True iff value is a member of nonTerminals
     */
    public function getNonTerminals()
    {
        return $this->nonTerminals;
    }
    /* }}} */
    /* getNonTerminalSymbolset {{{ */
    /**
     * NonTerminals getter (as a symbol set
     * 
     * @return boolean True iff value is a member of nonTerminals
     */
    public function getNonTerminalSymbolSet()
    {
        return new Structures_Grammar_Symbol_Set($this->getNonTerminals());
    }
    /* }}} */
    /* addTerminal {{{ */
    /**
     * Terminals addition accessor
     * 
     * @param Structures_Grammar_Symbol New value
     * @return Structures_Grammar_Symbol The added symbol
     */
    public function addTerminal($value)
    {
        $this->terminals[] = $value;
        $value->setTerminal(true);
    }
    /* }}} */
    /* isTerminal {{{ */
    /**
     * Test value for Terminals membership
     * 
     * @param Structures_Grammar_Symbol|string Value to be tested
     * @return boolean True iff value is a member of Terminals
     */
    public function isTerminal($value)
    {
        if (is_null($this->terminals)) $this->computeTerminals();
        if (!$value instanceof Structures_Grammar_Symbol) $value = Structures_Grammar_Symbol::create($value);
        foreach ($this->terminals as $cursor) if ($value == $cursor) return true;
        return false;
    }
    /* }}} */
    /* getTerminals {{{ */
    /**
     * Terminals getter
     * 
     */
    public function getTerminals()
    {
        if (is_null($this->terminals)) $this->computeTerminals();
        return $this->terminals;
    }
    /* }}} */
    /* getTerminalSymbolset {{{ */
    /**
     * Terminals getter (as a symbol set
     * 
     * @return boolean True iff value is a member of nonTerminals
     */
    public function getTerminalSymbolSet()
    {
        return new Structures_Grammar_Symbol_Set($this->getTerminals());
    }
    /* }}} */
    /* addRule {{{ */
    /**
     * Rules addition accessor
     * 
     * @param Structures_Grammar_Symbol New value
     */
    public function addRule($value)
    {
        if ($this->isRegular() && !$value->isRegular()) throw new Structures_Grammar_RestrictionException(sprintf(
            'Trying to add non regular rule to regular grammar (%s)', (string) $value));
        if ($this->isContextFree() && !$value->isContextFree()) throw new Structures_Grammar_RestrictionException(sprintf(
            'Trying to add non context-free rule to context-free grammar (%s)', (string) $value));
        $this->rules[] = $value;
    }
    /* }}} */
    /* addContextFreeRule {{{ */
    public function &addContextFreeRule()
    {
        $symbols = func_get_args();
        if (count($symbols) == 0) throw new Structures_Grammar_Exception('At least one symbol is needed in a context-free grammar rule');
        foreach($symbols as $i => $symbol) if (!($symbol instanceof Structures_Grammar_Symbol)) $symbols[$i] = Structures_Grammar_Symbol::create($symbol);
        $rule = new Structures_Grammar_Rule();
        $rule->addSymbolToLeft($symbols[0]);
        for($i=1; $i<count($symbols); $i++) $rule->addSymbolToRight($symbols[$i]);
        $this->addRule($rule);
        return $rule;
    }
    /* }}} */
    /* getRules {{{ */
    /**
     * Rules getter
     * 
     * @param Structures_Grammar_Symbol Value to be tested
     * @return boolean True iff value is a member of Rules
     */
    public function getRules()
    {
        return $this->rules;
    }
    /* }}} */
    /* getRule {{{ */
    /**
     * Rules getter
     * 
     * @param int Rule index to get
     * @return Structures_Grammar_Rule|null Rule at index, or null if not found
     */
    public function getRule($i)
    {
        if (!array_key_exists($i, $this->rules)) return null;
        return $this->rules[$i];
    }
    /* }}} */
    /* getRuleIndex {{{ */
    /**
     * Find the index of a given rule
     * 
     * @param Structures_Grammar_Rule Rule to find
     * @return int Rule index
     */
    public function getRuleIndex($right)
    {
        foreach ($this->rules as $index => $left) if ($left == $right) return $index;
        return false;
    }
    /* }}} */
/* getRulesByLeftSymbol {{{ */
/**
 * For context-free grammars, find the set of rules whose left-side production symbol is equal to the parameter
 *
 * @param Structures_Grammar_Symbol The symbol to search
 * @return array An array of Structures_Grammar_Rule instances
 */
public function getRulesByLeftSymbol($left)
{
    $result = array();
    for($i=0; $i < count($this->rules); $i++) if ($left == $this->rules[$i]->getLeftSymbol(0)) $result[] = $this->rules[$i];
    return $result;
}
/* }}} */
/* isContextFree {{{ */
/** 
 * contextFree getter
 *
 * @param boolean True if grammar is restricted to a context-free grammar
 */
public function isContextFree()
{
    return $this->contextFree;
}
/* }}} */
    /* testContextFree {{{ */
    /**
     * Test the grammar to check if it is context-free. 
     *
     * @return boolean True if the grammar is context-free
     */
    public function testContextFree()
    {
        if ($this->isContextFree()) return true;
        foreach ($this->rules as $rule) if (!$rule->isContextFree()) return false;
        return true;
    }
    /* }}} */
    /* setContextFree {{{ */
    /**
     * Set grammar restriction to context-free. 
     * 
     * If the grammar was not restricted before this call, the method will test if the
     * grammar is context-free before setting the restriction. It will throw an exception
     * if trying to restrict a non-context-free grammar
     *
     * @param boolean True if the grammar should be restricted to being context-free
     */
    public function setContextFree($value)
    {
        if ($value && !$this->testContextFree()) throw new Structures_Grammar_RestrictionException('Grammar is not context-free. Unable to introduce restriction');
        $this->contextFree = $value;
        if (!$value) $this->setRegular(false);
    }
    /* }}} */
    /* isRegular {{{ */
    /** 
     * regular getter
     *
     * @param boolean True if grammar is restricted to a regular grammar
     */
    public function isRegular()
    {
        return $this->regular;
    }
    /* }}} */
    /* testRegular {{{ */
    /**
     * Test the grammar to check if it is regular. 
     *
     * @return boolean True if the grammar is regular
     */
    public function testRegular()
    {
        if ($this->isRegular()) return true;
        foreach ($this->rules as $rule) if (!$rule->isRegular()) return false;
        return true;
    }
    /* }}} */
    /* setRegular {{{ */
    /**
     * Set grammar restriction to regular. 
     * 
     * If the grammar was not restricted before this call, the method will test if the
     * grammar is regular before setting the restriction. It will throw an exception
     * if trying to restrict a non-regular grammar
     *
     * @param boolean True if the grammar should be restricted to being regular
     */
    public function setRegular($value)
    {
        if ($value && !$this->testRegular()) foreach ($this->rules as $rule) if (!$rule->isRegular()) throw new Structures_Grammar_RestrictionException(sprintf('Grammar is not regular. Unable to introduce restriction. Rule \'%s\' is not regular', (string) $rule));
        $this->regular = $value;
        if ($value) $this->setContextFree(true);
    }
    /* }}} */
    /* isSymbolNullable {{{ */
    /**
     * A symbol is nullable if it is non-terminal and there is a nullable rule representing a production for that non-terminal
     *
     * @return boolean true iff symbol is nullable
     */
    public function isSymbolNullable($symbol)
    {
        if (is_null($this->nullableSymbolSet)) $this->computeNullableSymbolSet();
        return $this->nullableSymbolSet->symbolExists($symbol);
    }
    /* }}} */
    /* computeNullableSymbolSet {{{ */
    protected function computeNullableSymbolSet()
    {
        if (!$this->testContextFree()) throw new Structures_Grammar_Exception('isSymbolNullable is implemented for context-free grammars only, and this is not a context-free grammar');
        $this->nullableSymbolSet =  new Structures_Grammar_Symbol_Set();
        do {
            $cardinality = $this->nullableSymbolSet->getSymbolCount();
            foreach ($this->rules as $rule) if ($rule->isNullable($this->nullableSymbolSet)) $this->nullableSymbolSet->addSymbol($rule->getLeftSymbol(0));
        } while ($cardinality < $this->nullableSymbolSet->getSymbolCount());
    }
    /* }}} */
    protected $firstSet = array();
    /* symbolFirstSet {{{ */
    /**
     * The grammatical first set for a non-terminal symbol is the set of 
     * terminal symbols that appear at position 0 on the right hand side of 
     * all the symbol's productions.
     * 
     * @param Structures_Grammar_Symbol Symbol whose first set is sought
     * @return Structures_Grammar_Symbol_Set First set for symbol
     */
    public function symbolFirstSet($symbol)
    {
        $result = new Structures_Grammar_Symbol_Set();
        if ($symbol->isTerminal()) {
            $result->addSymbol($symbol);
        } else {
            if (array_key_exists($symbol->getId(), $this->firstSet)) return $this->firstSet[$symbol->getId()];
            $this->firstSet[$symbol->getId()] = new Structures_Grammar_Symbol_Set();
            foreach ($this->rules as $rule) if ($rule->getLeftSymbol(0)->__equals($symbol)) {
                for ($i=0; $i<$rule->rightCount(); $i++) {
                    $result->union($this->symbolFirstSet($rule->getRightSymbol($i)));
                    if (!$this->isSymbolNullable($rule->getRightSymbol($i))) break;
                }
            }
            $this->firstSet[$symbol->getId()] = $result;
        }
        return $result;
    }
    /* }}} */
    /* constructor {{{ */
    /**
     * Create a new Structures_Grammar
     *
     * @param boolean Should the grammar be context free (defaults to true)
     * @param boolean Should the grammar be regular (defaults to false)
     */
    public function __construct($contextFree = true, $regular = false)
    {
        $this->setContextFree($contextFree);
        $this->setRegular($regular);
    }
    /* }}} */
    /* __toString {{{ */
    public function __toString()
    {
        $result = '';
        foreach ($this->rules as $index => $value) $result .= sprintf("[%d] %s\n", $index, (string) $value);

        return $result;
    }
    /* }}} */
    /* __equals {{{ */
    public function __equals($other)
    {
        if (!($other instanceof Structures_Grammar)) return false;
        $otherRules = $other->getRules();
        if (count($otherRules) != count($this->rules)) return false;
        foreach ($this->rules as $rule) if ($other->getRuleIndex($rule) === false) return false;
        return true;
    }
    /* }}} */
}

?>

<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
namespace sergiosgc;

/**
 * Structures_Grammar_Rule is a representation of a formal grammar rule according to Noah Chomsky. 
 *
 * A grammar rule is a production that transforms a sequence of symbols (terminal and non-terminal),
 * into another sequence of symbols. The left side of the production must contain at 
 * least one non-terminal symbol.
 * 
 * A grammar rule may contain a reduction function. A reduction function is composed by two parts:
 *  - a map from righthand symbol indexes to argument names, to be passed to the function
 *  - PHP code, as string to be used as the function body
 */
class Structures_Grammar_Rule
{
    protected $left = array();
    protected $right = array();
    protected $reductionFunctionSymbolmap = array();
    protected $reductionFunction = null;
    protected $priority = 0;
    public function getPriority() { return $this->priority; }
    public function setPriority($n) { $this->priority = (int) $n; }

    /* addReductionFunctionSymbolmap {{{ */
    public function addReductionFunctionSymbolmap($index, $symbol)
    {
        if ($index >= $this->rightCount()) throw new Structures_Grammar_UndefinedSymbol(sprintf('Attempt to add reduction symbol for index %d on grammar rule with %d symbols', $index, $this->rightCount()));
        $this->reductionFunctionSymbolmap[$index] = $symbol;
    }
    /* }}} */
    /* getReductionFunctionSymbolmap {{{ */
    public function getReductionFunctionSymbolmap()
    {
        for ($i=0; $i<$this->rightCount(); $i++) if (!array_key_exists($i, $this->reductionFunctionSymbolmap)) $this->reductionFunctionSymbolmap[$i] = '';
        ksort($this->reductionFunctionSymbolmap);

        return $this->reductionFunctionSymbolmap;
    }
    /* }}} */
    /* setReductionFunction {{{ */
    public function setReductionFunction($code)
    {
        $this->reductionFunction = $code;
    }
    /* }}} */
    /* getReductionFunction {{{ */
    public function getReductionFunction()
    {
        return $this->reductionFunction;
    }
    /* }}} */
    /* getLeft {{{ */
    /** 
     * Returns a numerically indexed array of symbols that make up the left part of the production
     */
    public function getLeft()
    {
        return $this->left;
    }
    /* }}} */
    /* getLeftSymbol {{{ */
    /**
     * Returns a lefthand symbol by numeric index
     *
     * @param int index
     * @return Structures_Grammar_Symbol Symbol at index
     */
    public function getLeftSymbol($index)
    {
        if ($index >= $this->leftCount()) return null;
        return $this->left[$index];
    }
    /* }}} */
    /* addSymbolToLeft {{{ */
    /**
     * Adds a new symbol to the left part of the production
     */
    public function addSymbolToLeft($value)
    {
        if (!($value instanceof Structures_Grammar_Symbol)) $value = Structures_Grammar_Symbol::create((string) $value);
        $this->left[] = $value;
    }
    /* }}} */
    /* leftCount {{{ */
    /**
     * Return how many symbols are there in the lefthand side of the rul
     *
     * @return int Lefthand symbol count
     */
    public function leftCount()
    {
        return count($this->left);
    }
    /* }}} */
    /* getRight {{{ */
    /** 
     * Returns a numerically indexed array of symbols that make up the right part of the production
     */
    public function getRight()
    {
        return $this->right;
    }
    /* }}} */
    /* addSymbolToRight {{{ */
    /**
     * Adds a new symbol to the left part of the production
     */
    public function addSymbolToRight($value)
    {
        if (!($value instanceof Structures_Grammar_Symbol)) $value = Structures_Grammar_Symbol::create((string) $value);
        $this->right[] = $value;
    }
    /* }}} */
    /* rightCount {{{ */
    /**
     * Return how many symbols are there in the righthand side of the rul
     *
     * @return int Righthand symbol count
     */
    public function rightCount()
    {
        return count($this->right);
    }
    /* }}} */
    /* getRightSymbol {{{ */
    /**
     * Returns a righthand symbol by numeric index
     *
     * @param int index
     * @return Structures_Grammar_Symbol Symbol at index
     */
    public function getRightSymbol($index)
    {
        if ($index >= $this->rightCount()) return null;
        return $this->right[$index];
    }
    /* }}} */
    /* isRegular {{{ */
    /**
     * Returns true iff this rule can be part of a regular grammar
     *
     * This rule can be part of a regular grammar if it can be part of a context-free grammar
     * and the right part of the production consists of one terminal symbol and optionally one
     * non-terminal symbol
     *
     * @return boolean True iff this rule can be part of a regular grammar
     */
    public function isRegular()
    {
        if (!$this->isContextFree()) return false;
        if (count($this->right) != 1 && count($this->right) != 2) return false;
        if ($this->right[0]->isNonTerminal()) return false;
        if (count($this->right) == 2 && $this->right[1]->isTerminal()) return false;
        return true;
    }
    /* }}} */
    /* isContextFree {{{ */
    /**
     * Returns true iff this rule can be part of a context-free grammar
     *
     * This rule can be part of a context free grammar if the left side of the production
     * consists of one non-terminal symbol.
     *
     * @return boolean True iff this rule can be part of a context-free grammar
     */
    public function isContextFree()
    {
        if (count($this->left) != 1) return false;
        if ($this->left[0]->isTerminal()) return false;
        return true;
    }
    /* }}} */
    /* isNullable {{{ */
    /**
     * A grammar rule is nullable if the right side of the production is empty
     *
     * @return boolean true iff there are no symbols in the right side
     */
    public function isNullable()
    {
        return $this->rightCount() == 0;
    }
    /* }}} */
    /* constructor {{{ */
    public function __construct()
    {
    }
    /* }}} */
    /* __toString {{{ */
    public function __toString()
    {
        $result = '';
        foreach ($this->left as $value) $result .= (string) $value;
        $result .= '->';
        foreach ($this->right as $value) $result .= (string) $value;

        return $result;
    }
    /* }}} */
    /* __equals {{{ */
    public function __equals($other)
    {
        if (!($other instanceof Structures_Grammar_Rule)) return false;
        if ($other->getReductionFunction() != $this->getReductionFunction()) return false;
        if ($other->getReductionFunctionSymbolmap() != $this->getReductionFunctionSymbolmap()) return false;
        $otherLeft = $other->getLeft();
        $otherRight = $other->getRight();
        if (count($otherLeft) != count($this->left)) return false;
        if (count($otherRight) != count($this->right)) return false;
        foreach ($this->left as $index => $symbol) {
            if (!array_key_exists($index, $otherLeft)) return false;
            if ($symbol != $otherLeft[$index]) return false;
        }
        foreach ($this->right as $index => $symbol) {
            if (!array_key_exists($index, $otherRight)) return false;
            if ($symbol != $otherRight[$index]) return false;
        }
        return true;
    }
    /* }}} */
}
?>

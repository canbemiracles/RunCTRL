<?php
namespace ApiBundle\DQL;
use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer;

class Time extends FunctionNode
{
    protected $time;
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return "TIME(" . $sqlWalker->walkArithmeticPrimary($this->time) . ")";
    }
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->time = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
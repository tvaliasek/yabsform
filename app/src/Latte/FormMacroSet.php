<?php


namespace YABSForm\Latte\Macros;


use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\PhpWriter;
use Nette\Bridges\FormsLatte\FormMacros;
use Nette\Forms\IControl;
use Nette\Utils\Html;
use Tracy\Debugger;

class FormMacroSet extends FormMacros
{
    public static function install(Compiler $compiler): void
    {
        Debugger::barDump('install');
        $me = new static($compiler);
        $me->addMacro('bsLabel', [$me, 'macroLabel']);
        $me->addMacro('bsInput', [$me, 'macroInput']);
    }

    /**
     * {label ...}
     */
    public function macroLabel(MacroNode $node, PhpWriter $writer)
    {
        if ($node->modifiers) {
            throw new CompileException('Modifiers are not allowed in ' . $node->getNotation());
        }
        $words = $node->tokenizer->fetchWords();
        if (!$words) {
            throw new CompileException('Missing name in ' . $node->getNotation());
        }
        $node->replaced = true;
        $name = array_shift($words);
        $result = $writer->write(
            (
                ($name[0] === '$')
                ? '$_input = is_object(%0.word) ? %0.word : end($this->global->formsStack)[%0.word]; if ($_label = $_input'
                : 'if ($_label = end($this->global->formsStack)[%0.word]'
            )
            . ') echo $_form->getRenderer()->renderLabel($_label'
            . ($node->tokenizer->isNext() ? '->addAttributes(%node.array)' : '') .')',
            $name//,
            //$words ? ('getLabelPart(' . implode(', ', array_map([$writer, 'formatWord'], $words)) . ')') : 'getLabel()'
        );

        Debugger::barDump($result);
        return $result;
    }

    /**
     * {input ...}
     */
    public function macroInput(MacroNode $node, PhpWriter $writer)
    {
        if ($node->modifiers) {
            throw new CompileException('Modifiers are not allowed in ' . $node->getNotation());
        }
        $words = $node->tokenizer->fetchWords();
        if (!$words) {
            throw new CompileException('Missing name in ' . $node->getNotation());
        }
        $node->replaced = true;
        $name = array_shift($words);
        return $writer->write(
            ($name[0] === '$' ? '$_input = is_object(%0.word) ? %0.word : end($this->global->formsStack)[%0.word]; echo $_input' : 'echo end($this->global->formsStack)[%0.word]')
            . '->%1.raw'
            . ($node->tokenizer->isNext() ? '->addAttributes(%node.array)' : '')
            . " /* line $node->startLine */",
            $name,
            $words ? 'getControlPart(' . implode(', ', array_map([$writer, 'formatWord'], $words)) . ')' : 'getControl()'
        );
    }

    public static function label(Html $label, IControl $control, bool $isPart): Html
    {
        return $label;
    }

    public static function input(Html $input, IControl $control, bool $isPart): Html
    {
        return $input;
    }
}
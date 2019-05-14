<?php


namespace YABSForm\Latte\Macros;


use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\PhpWriter;
use Nette\Bridges\FormsLatte\FormMacros;
use Nette\Forms\IControl;
use Nette\Utils\Html;

class FormMacroSet extends FormMacros
{
    public static function install(Compiler $compiler): void
    {
        $me = new static($compiler);
        $me->addMacro('bsLabel', [$me, 'macroBsLabel']);
        $me->addMacro('bsInput', [$me, 'macroBsInput']);
        $me->addMacro('bsPair', [$me, 'macroBsPair']);
    }

    /**
     * {bsLabel ...}
     * @param MacroNode $node
     * @param PhpWriter $writer
     * @return string
     * @throws CompileException
     */
    public function macroBsLabel(MacroNode $node, PhpWriter $writer)
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

        return $result;
    }

    public function macroBsPair(MacroNode $node, PhpWriter $writer): string
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
            (
            ($name[0] === '$')
                ? '$_pair = is_object(%0.word) ? %0.word : end($this->global->formsStack)[%0.word];'
                : '$_pair = end($this->global->formsStack)[%0.word];'
            )
            //. '->%1.raw'
            . 'echo $_form->getRenderer()->renderPair($_pair'
            . ($node->tokenizer->isNext() ? '->addAttributes(%node.array)' : '')
            . ") /* line $node->startLine */",
            $name //,
        // $words ? 'getControlPart(' . implode(', ', array_map([$writer, 'formatWord'], $words)) . ')' : 'getControl()'
        );
    }

    /**
     * {bsInput ...}
     * @param MacroNode $node
     * @param PhpWriter $writer
     * @return string
     * @throws CompileException
     */
    public function macroBsInput(MacroNode $node, PhpWriter $writer): string
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
            (
                ($name[0] === '$')
                    ? '$_input = is_object(%0.word) ? %0.word : end($this->global->formsStack)[%0.word];'
                    : '$_input = end($this->global->formsStack)[%0.word];'
            )
            //. '->%1.raw'
            . 'echo $_form->getRenderer()->renderControl($_input'
            . ($node->tokenizer->isNext() ? '->addAttributes(%node.array)' : '')
            . ") /* line $node->startLine */",
            $name //,
            // $words ? 'getControlPart(' . implode(', ', array_map([$writer, 'formatWord'], $words)) . ')' : 'getControl()'
        );
    }

    public static function bsLabel(Html $label, IControl $control, bool $isPart): Html
    {
        return $label;
    }

    public static function bsInput(Html $input, IControl $control, bool $isPart): Html
    {
        return $input;
    }
}
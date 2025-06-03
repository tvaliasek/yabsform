<?php


namespace YABSForm\Latte\Macros;


use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * Class FormMacroSet
 * Mostly copy-pasted from original nette forms macroset
 * @package YABSForm\Latte\Macros
 */
class FormMacroSet extends MacroSet
{
    public static function install(Compiler $compiler): void
    {
        $me = new static($compiler);
        $me->addMacro('bsLabel', [$me, 'macroBsLabel']);
        $me->addMacro('bsInput', [$me, 'macroBsInput']);
        $me->addMacro('bsPair', [$me, 'macroBsPair']);
        $me->addMacro('bsErrors', [$me, 'macroBsErrors']);
        $me->addMacro('bsOwnErrors', [$me, 'macroBsOwnErrors']);
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
            . ') echo $form->getRenderer()->renderLabel($_label)',
            $name
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
            . 'echo $form->getRenderer()->renderPair($_pair'
            . ") /* line $node->startLine */",
            $name
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
            . 'echo $form->getRenderer()->renderControl($_input'
            . ") /* line $node->startLine */",
            $name
        );
    }

    /**
     * {bsErrors ...}
     * @param MacroNode $node
     * @param PhpWriter $writer
     * @return string
     * @throws CompileException
     */
    public function macroBsErrors(MacroNode $node, PhpWriter $writer): string
    {
        if ($node->modifiers) {
            throw new CompileException('Modifiers are not allowed in ' . $node->getNotation());
        }
        $node->replaced = true;
        return $writer->write('echo $form->getRenderer()->renderErrors(null, false, $form);');
    }

    /**
     * {bsErrors ...}
     * @param MacroNode $node
     * @param PhpWriter $writer
     * @return string
     * @throws CompileException
     */
    public function macroBsOwnErrors(MacroNode $node, PhpWriter $writer): string
    {
        if ($node->modifiers) {
            throw new CompileException('Modifiers are not allowed in ' . $node->getNotation());
        }
        $node->replaced = true;
        return $writer->write('echo $form->getRenderer()->renderErrors(null, true, $form);');
    }
}
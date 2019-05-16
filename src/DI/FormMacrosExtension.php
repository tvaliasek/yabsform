<?php
declare(strict_types=1);

namespace YABSForm\DI;


use Nette\DI\CompilerExtension;

class FormMacrosExtension extends CompilerExtension
{

    public function beforeCompile(): void
    {
        $builder = $this->getContainerBuilder();
        $builder->getDefinition('latte.latteFactory')
            ->getResultDefinition()
            ->addSetup('?->onCompile[] = function($engine) { \YABSForm\Latte\Macros\FormMacroSet::install($engine->getCompiler()); }', ['@self']);
    }
}
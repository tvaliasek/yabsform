<?php declare(strict_types=1);


namespace YABSForm\DI;


use Nette;
use Nette\DI\CompilerExtension;
use YABSForm\Controls\CustomCheckbox;
use YABSForm\Factories\BootstrapFormFactory;

class FormFactoryExtension extends CompilerExtension
{
    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix('yabsform.formFactory'))
            ->setFactory(BootstrapFormFactory::class, []);
    }
}
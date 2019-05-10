<?php declare(strict_types=1);


namespace YABSForm\DI;


use Nette;
use Nette\DI\CompilerExtension;
use Tracy\Debugger;
use YABSForm\Controls\CustomCheckbox;

class FormControlsExtension extends CompilerExtension
{
    public function loadConfiguration(): void
    {
        // nothing
    }

    public function beforeCompile(): void
    {
        // nothing
    }

    public function afterCompile(Nette\PhpGenerator\ClassType $class): void
    {
        $init = $class->getMethod('initialize');
        $init->addBody(__CLASS__ . '::registerControls();');
    }

    public static function registerControls(): void
    {
        self::registerCustomCheckbox();

    }

    public static function registerCustomCheckbox(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomCheckbox',
            function ($form, $name, $label = null, bool $renderAsSwitch = false) {
                $form[$name] = new CustomCheckbox($label, $renderAsSwitch);
                return $form[$name];
            });
    }
}
<?php declare(strict_types=1);


namespace YABSForm\DI;


use Nette;
use Nette\DI\CompilerExtension;
use YABSForm\Controls\CustomCheckbox;
use YABSForm\Controls\CustomCheckboxList;
use YABSForm\Controls\CustomMultiSelect;
use YABSForm\Controls\CustomRadioList;
use YABSForm\Controls\CustomRange;
use YABSForm\Controls\CustomSelect;
use YABSForm\Controls\CustomUpload;

class FormControlsExtension extends CompilerExtension
{
    public function afterCompile(Nette\PhpGenerator\ClassType $class): void
    {
        $init = $class->getMethod('initialize');
        $init->addBody(__CLASS__ . '::registerControls();');
    }

    public static function registerControls(): void
    {
        self::registerCustomCheckbox();
        self::registerCustomCheckboxList();
        self::registerCustomRadioList();
        self::registerCustomSelect();
        self::registerCustomMultiSelect();
        self::registerCustomRange();
        self::registerCustomUpload();
    }

    public static function registerCustomCheckbox(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomCheckbox',
            function ($form, $name, $label = null) {
                $form[$name] = new CustomCheckbox($label);
                return $form[$name];
            });
    }

    public static function registerCustomCheckboxList(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomCheckboxList',
            function ($form, $name, $label = null, array $items = null) {
                $form[$name] = new CustomCheckboxList($label, $items);
                return $form[$name];
            });
    }

    public static function registerCustomRadioList(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomRadioList',
            function ($form, $name, $label = null, array $items = null) {
                $form[$name] = new CustomRadioList($label, $items);
                return $form[$name];
            });
    }

    public static function registerCustomSelect(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomSelect',
            function ($form, $name, $label = null, array $items = null) {
                $form[$name] = new CustomSelect($label, $items);
                return $form[$name];
            });
    }

    public static function registerCustomMultiSelect(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomMultiSelect',
            function ($form, $name, $label = null, array $items = null) {
                $form[$name] = new CustomMultiSelect($label, $items);
                return $form[$name];
            });
    }

    public static function registerCustomRange(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomRange',
            function ($form, $name, $label = null, float $min = 0, float $max = 100, float $step = 1) {
                $form[$name] = new CustomRange($label, $min, $max, $step);
                return $form[$name];
            });
    }

    public static function registerCustomUpload(): void
    {
        Nette\Forms\Container::extensionMethod('addCustomUpload',
            function ($form, $name, $label = null, bool $multiple = false) {
                $form[$name] = new CustomUpload($label, $multiple);
                return $form[$name];
            });
    }
}
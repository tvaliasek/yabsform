<?php declare(strict_types=1);


namespace YABSForm\Controls;


use Nette\Forms\Controls\Checkbox;
use Nette\Utils\Html;

class CustomCheckbox extends Checkbox
{
    /**
     * @var Html
     */
    protected $wrapper;

    /**
     * CustomCheckbox constructor.
     * @param string|object|null $label
     * @param bool $renderAsSwitch
     */
    public function __construct($label = null, bool $renderAsSwitch = false)
    {
        parent::__construct($label);
        $this->wrapper = Html::el('div')
            ->addAttributes(['class' => ($renderAsSwitch)
                ? 'custom-control custom-switch'
                : 'custom-control custom-checkbox'
            ]);
    }

    /**
     * Generates control's HTML element.
     */
    public function getControl(): Html
    {
        return $this->wrapper
            ->insert(0, $this->getControlPart())
            ->insert(1, $this->getLabelPart());
    }

}
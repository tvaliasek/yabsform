<?php declare(strict_types=1);


namespace YABSForm\Controls;


use Nette\Forms\Controls\Checkbox;
use Nette\Utils\Html;
use Tracy\Debugger;

class CustomCheckbox extends Checkbox
{
    /**
     * @var Html
     */
    protected $wrapper;

    /**
     * @var bool
     */
    protected $renderAsSwitch = false;

    /**
     * CustomCheckbox constructor.
     * @param string|object|null $label
     */
    public function __construct($label = null)
    {
        parent::__construct($label);
        $this->wrapper = Html::el('div')
            ->addAttributes([
                'class' => (('custom-control custom-' . (($this->renderAsSwitch) ? 'switch' : 'checkbox')))
            ]);
    }

    public function renderAsSwitch(bool $state = true)
    {
        $this->wrapper->addAttributes(
            ($state)
                ? ['class' => 'custom-control custom-switch']
                : ['class' => 'custom-control custom-checkbox']
        );
        $this->renderAsSwitch = $state;
        return $this;
    }

    /**
     * Generates control's HTML element.
     */
    public function getControl(): Html
    {
        $controlPart = $this->getControlPart();
        if ($this->hasErrors()) {
            $classNames = $controlPart->getAttribute('class');
            if (is_array($classNames)) {
                $classNames['is-invalid'] = true;
            } else {
                $classNames .= ' is-invalid';
            }
            $controlPart->setAttribute('class', $classNames);
        }
        return $this->wrapper
            ->insert(0, $controlPart)
            ->insert(1, $this->getLabelPart());
    }

}
<?php


namespace YABSForm\Controls;


use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

class CustomRange extends BaseControl
{

    /**
     * @var float
     */
    protected $min;

    /**
     * @var float
     */
    protected $max;

    /**
     * @var float
     */
    protected $step;

    /**
     * @param string|object $label
     * @param float $min
     * @param float $max
     * @param float $step
     */
    public function __construct($label = null, float $min = 0.0, float $max = 100.0, float $step = 1.0)
    {
        parent::__construct($label);
        $this->setOption('type', 'range');
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->value = (empty($this->value)) ? $min : $this->value;
    }

    /**
     * Generates control's HTML element.
     */
    public function getControl(): Html
    {
        $html = parent::getControl();
        $classNames = $html->getAttribute('class');
        $classNames['custom-range'] = true;
        $html->setAttribute('class', $classNames);
        $html->setAttribute('min', $this->min);
        $html->setAttribute('max', $this->max);
        $html->setAttribute('step', $this->step);
        $html->setAttribute('type', 'range');
        $html->setAttribute('value', (float) $this->getValue());
        return $html;
    }

}
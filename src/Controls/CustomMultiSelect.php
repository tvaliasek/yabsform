<?php


namespace YABSForm\Controls;


use Nette\Forms\Controls\MultiSelectBox;
use Nette\Utils\Html;

class CustomMultiSelect extends MultiSelectBox
{

    /**
     * Generates control's HTML element.
     */
    public function getControl(): Html
    {
        $html = parent::getControl();
        $classNames = $html->getAttribute('class');
        $classNames['custom-select'] = true;
        $html->setAttribute('class', $classNames);
        return $html;
    }
}
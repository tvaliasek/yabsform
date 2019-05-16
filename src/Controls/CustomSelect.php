<?php


namespace YABSForm\Controls;


use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Helpers;
use Nette\Utils\Html;
use Tracy\Debugger;

class CustomSelect extends SelectBox
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
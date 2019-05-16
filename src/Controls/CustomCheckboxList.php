<?php declare(strict_types=1);


namespace YABSForm\Controls;


use DiDom\Document;
use Nette\Forms\Controls\CheckboxList;
use Nette\Utils\Html;

class CustomCheckboxList extends CheckboxList
{

    protected $renderAsSwitches = false;

    /**
     * @param bool $state
     * @return CustomCheckboxList
     */
    public function renderAsSwitches(bool $state): self
    {
        $this->renderAsSwitches = $state;
        return $this;
    }

    /**
     * Generates control's HTML element.
     */
    public function getControl(): Html
    {
        $html = parent::getControl();
        $dom = new Document($html->getChildren()[0]);

        // fix input class names
        foreach ($dom->find('input') as $input) {
            $input->setAttribute('class', 'custom-control-input');
        }

        // fix labels html structure, class names and missing html ids
        foreach ($dom->find('label') as $index => $label) {
            $label->setAttribute('class', 'custom-control-label');
            $labelClassnames = ('custom-control custom-' . (($this->renderAsSwitches) ? 'switch' : 'checkbox'));
            if ($this->hasErrors()) {
                $labelClassnames .= ' is-invalid';
            }
            $label->parent()->setAttribute(
                'class',
                $labelClassnames
            );
            $input = $label->find('input')[0];
            $newLabel = clone $label;
            $inputHtmlId = ('__' . (str_replace('[]', '', ($input->getAttribute('name') . '-' . $index))));
            $input->setAttribute('id', $inputHtmlId);
            if ($this->hasErrors()) {
                $input->setAttribute('class', ($input->getAttribute('class') . ' is-invalid'));
            }
            $newLabel->setAttribute('for', $inputHtmlId);
            $newLabel->setInnerHtml($label->text());
            $label->parent()->setInnerHtml($input->html() . $newLabel->html());
        }

        $html->removeChildren();
        $el = Html::el('div');
        if ($this->hasErrors()) {
            $el->setAttribute('class', '__yabsform-is-invalid');
        }
        $html->addHtml($el->addHtml($dom->html()));
        return $html;
    }
}
<?php


namespace YABSForm\Controls;


use Nette\Forms\Controls\UploadControl;
use Nette\Utils\Html;

class CustomUpload extends UploadControl
{
    protected $browse = 'Browse';

    public function setBrowseCaption(string $caption): self
    {
        $this->browse = $caption;
        return $this;
    }

    public function getControl(): Html
    {
        $html = parent::getControl();
        $wrapper = Html::el('div');
        $classNames = [
            'custom-file' => true
        ];
        $wrapper->setAttribute('class', $classNames);
        $customLabel = $this->getLabelPart();
        $customLabel->setAttribute('data-browse', $this->translate($this->browse));
        $labelClassnames = $customLabel->getAttribute('class');
        $labelClassnames['custom-file-label'] = true;
        $customLabel->setAttribute('class', $labelClassnames);
        $controlClassnames = $html->getAttribute('class');
        $controlClassnames['custom-file-input'] = true;
        $controlClassnames['is-invalid'] = $this->hasErrors();
        $html->setAttribute('class', $controlClassnames);

        $wrapper->addHtml($html);
        $wrapper->addHtml($customLabel);
        return $wrapper;
    }

    /**
     * Bypasses label generation.
     */
    public function getLabel($caption = null): void
    {
    }

    public function getLabelPart(): Html
    {
        return parent::getLabel();
    }
}
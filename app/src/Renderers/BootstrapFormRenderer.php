<?php declare(strict_types=1);

namespace YABSForm\Renderers;

use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextBase;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Utils\Html;
use Nette\Utils\IHtmlString;
use YABSForm\Controls\CustomCheckbox;
use YABSForm\Controls\CustomCheckboxList;
use YABSForm\Controls\CustomRadioList;
use YABSForm\Controls\CustomUpload;

class BootstrapFormRenderer extends DefaultFormRenderer
{

    /** @var mixed[] */
    public $wrappers = [
        'form' => [
            'container' => null,
        ],
        'error' => [
            'container' => 'div class="alert alert-danger alert-dismissible"',
            'item' => 'p',
        ],
        'group' => [
            'container' => 'fieldset',
            'label' => 'legend',
            'description' => 'p',
        ],
        'controls' => [
            'container' => null,
        ],
        'pair' => [
            'container' => 'div class="form-group"',
            '.required' => 'required',
            '.optional' => null,
            '.odd' => null,
        ],
        'control' => [
            'container' => '',
            '.odd' => null,
            'description' => 'span class="form-text text-muted"',
            'requiredsuffix' => '',
            'errorcontainer' => 'span class="invalid-feedback"',
            'erroritem' => '',
            '.required' => 'required',
            '.text' => 'text',
            '.password' => 'text',
            '.file' => 'text',
            '.submit' => 'button',
            '.image' => 'imagebutton',
            '.button' => 'button',
            '.error' => 'is-invalid'
        ],
        'label' => [
            'container' => '',
            'suffix' => null,
            'requiredsuffix' => '',
        ],
        'hidden' => [
            'container' => 'div class="d-none"',
        ],
    ];

    /**
     * Provides complete form rendering.
     *
     * @param Form $form
     * @param string|null $mode 'begin', 'errors', 'ownerrors', 'body', 'end' or empty to render all
     * @return string
     */
    public function render(Form $form, string $mode = null): string
    {
        $usedPrimary = false;

        $form->getElementPrototype()->setNovalidate(true);

        foreach ($form->getControls() as $control) {

            switch (true) {
                case $control instanceof Button:
                    /** @var string|null $class */
                    $class = $control->getControlPrototype()->getAttribute('class');
                    if ($class === null || mb_strpos($class, 'btn') === false) {
                        $control->getControlPrototype()->addClass($usedPrimary === false ? 'btn btn-primary' : 'btn btn-secondary');
                        $usedPrimary = true;
                    }
                    break;
                case $control instanceof TextBase:
                case $control instanceof SelectBox:
                case $control instanceof MultiSelectBox:
                    $control->getControlPrototype()->addClass('form-control');
                    break;
                case $control instanceof CustomCheckbox:
                    $control->getSeparatorPrototype()->setName('div');
                    $control->getControlPrototype()->addClass('custom-control-input');
                    $control->getLabelPrototype()->addClass('custom-control-label');
                    break;
                case $control instanceof CustomCheckboxList:
                case $control instanceof CustomRadioList:
                    $control->getSeparatorPrototype()->setName('div');
                    break;
                case $control instanceof Checkbox:
                case $control instanceof CheckboxList:
                case $control instanceof RadioList:
                    $control->getSeparatorPrototype()->setName('div')->addClass('form-check');
                    $control->getControlPrototype()->addClass('form-check-input');
                    $control->getLabelPrototype()->addClass('form-check-label');
                    break;
            }
        }

        return parent::render($form, $mode);
    }

    /**
     * Renders 'control' part of visual row of controls.
     * @param IControl $control
     * @return Html
     */
    public function renderControl(IControl $control): Html
    {
        $body = $this->getWrapper('control container');
        if ($this->counter % 2) {
            $body->class($this->getValue('control .odd'), true);
        }

        $description = $control->getOption('description');
        if ($description instanceof IHtmlString) {
            $description = ' ' . $description;

        } elseif ($description != null) { // intentionally ==
            if ($control instanceof Nette\Forms\Controls\BaseControl) {
                $description = $control->translate($description);
            }
            $description = ' ' . $this->getWrapper('control description')->setText($description);

        } else {
            $description = '';
        }

        if ($control->isRequired()) {
            $description = $this->getValue('control requiredsuffix') . $description;
        }

        $control->setOption('rendered', true);
        $el = $control->getControl();
        if ($el instanceof Html) {
            if ($el->getName() === 'input') {
                $el->class($this->getValue("control .$el->type"), true);
            }
            $el->class($this->getValue('control .error'), $control->hasErrors());
        }

        if (
            $control instanceof CustomCheckbox ||
            $control instanceof CustomUpload
        ) {
            return $body->setHtml($el->addHtml($this->renderErrors($control)) . $description);
        }

        if (
            $control instanceof CustomCheckboxList
        ) {
            if ($control->hasErrors()) {
                $el->addAttributes(['class' => '']);
            }
            return $body->setHtml($el->addHtml($this->renderErrors($control)) . $description);
        }

        return $body->setHtml($el . $description . $this->renderErrors($control));
    }


}
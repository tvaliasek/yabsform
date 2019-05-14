<?php declare(strict_types=1);

namespace YABSForm\Renderers;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Forms\Controls\TextBase;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\InvalidArgumentException;
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
     * @var string|null
     */
    protected $submitClassnames;

    /**
     * @param string $cssClassnames
     * @return BootstrapFormRenderer
     */
    public function setSubmitButtonClassnames(string $cssClassnames): self
    {
        $this->submitClassnames = $cssClassnames;
        return $this;
    }

    /**
     * Provides complete form rendering.
     *
     * @param Form $form
     * @param string|null $mode 'begin', 'errors', 'ownerrors', 'body', 'end' or empty to render all
     * @return string
     */
    public function render(Form $form, string $mode = null): string
    {
        $form->getElementPrototype()->setNovalidate(true);
        return parent::render($form, $mode);
    }

    /**
     * Renders single visual row.
     */
    public function renderPair(IControl $control): string
    {
        $pair = $this->getWrapper('pair container');
        if (!($control instanceof Button)) {
            $pair->addHtml($this->renderLabel($control));
        }
        $pair->addHtml($this->renderControl($control));
        $pair->class($this->getValue($control->isRequired() ? 'pair .required' : 'pair .optional'), true);
        $pair->class($control->hasErrors() ? $this->getValue('pair .error') : null, true);
        $pair->class($control->getOption('class'), true);
        if (++$this->counter % 2) {
            $pair->class($this->getValue('pair .odd'), true);
        }
        $pair->id = $control->getOption('id');
        return $pair->render(0);
    }

    /**
     * Renders 'label' part of visual row of controls.
     * @param IControl $control
     * @return Html
     */
    public function renderLabel(IControl $control): Html
    {
        switch (true) {
            case $control instanceof CustomCheckbox:
                $control->getLabelPrototype()->addClass('custom-control-label');
                break;
            case $control instanceof CustomCheckboxList:
            case $control instanceof CustomRadioList:
                // skip
                break;
            case $control instanceof Checkbox:
            case $control instanceof CheckboxList:
            case $control instanceof RadioList:
                $control->getLabelPrototype()->addClass('form-check-label');
                break;
        }

        return parent::renderLabel($control);
    }

    protected function onButtonRender(IControl $control): IControl
    {
        /** @var string|null $class */
        $class = $control->getControlPrototype()->getAttribute('class');
        if ($class === null || mb_strpos($class, 'btn') === false) {
            $control->getControlPrototype()->addClass('btn');
        }
        if ($control instanceof SubmitButton) {
            if (!empty($this->submitClassnames)) {
                $control->getControlPrototype()->addClass($this->submitClassnames);
            }
        }

        return $control;
    }

    /**
     * Renders 'control' part of visual row of controls.
     * @param IControl $control
     * @return Html
     */
    public function renderControl(IControl $control): Html
    {
        switch (true) {
            case $control instanceof Button:
                $control = $this->onButtonRender($control);
                break;
            case $control instanceof TextBase:
            case $control instanceof SelectBox:
            case $control instanceof MultiSelectBox:
                $control->getControlPrototype()->addClass('form-control');
                break;
            case $control instanceof CustomCheckbox:
                $control->getSeparatorPrototype()->setName('div');
                $control->getControlPrototype()->addClass('custom-control-input');
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
                break;
        }

        return parent::renderControl($control);
    }

    /**
     * Renders single visual row of multiple controls.
     * @param Nette\Forms\IControl[] $controls
     */
    public function renderPairMulti(array $controls): string
    {
        $s = [];
        foreach ($controls as $control) {
            if (!$control instanceof IControl) {
                throw new InvalidArgumentException('Argument must be array of Nette\Forms\IControl instances.');
            }
            $description = $control->getOption('description');
            if ($description instanceof IHtmlString) {
                $description = ' ' . $description;

            } elseif ($description != null) { // intentionally ==
                if ($control instanceof BaseControl) {
                    $description = $control->translate($description);
                }
                $description = ' ' . $this->getWrapper('control description')->setText($description);

            } else {
                $description = '';
            }

            $control->setOption('rendered', true);

            /* added code to original function */
            if ($control instanceof Button) {
                $control = $this->onButtonRender($control);
            }
            /* added code to original function end */

            $el = $control->getControl();
            if ($el instanceof Html) {
                if ($el->getName() === 'input') {
                    $el->class($this->getValue("control .$el->type"), true);
                }
                $el->class($this->getValue('control .error'), $control->hasErrors());
            }
            $s[] = $el . $description;
        }
        $pair = $this->getWrapper('pair container');
        $pair->addHtml($this->renderLabel($control));
        $pair->addHtml($this->getWrapper('control container')->setHtml(implode(' ', $s)));
        return $pair->render(0);
    }
}
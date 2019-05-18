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
use YABSForm\RenderModes\RenderModeHorizontal;

class BootstrapFormRenderer extends DefaultFormRenderer
{

    /**
     * @var array
     */
    public $wrappers;

    /**
     * @var string|null
     */
    protected $submitClassnames;

    /**
     * @var string|null
     */
    protected $buttonClassnames;

    /**
     * @var bool
     */
    protected $dismissibleFormErrors = true;

    /**
     * @var string
     */
    protected $renderMode = self::HORIZONTAL_RENDER_MODE;

    const HORIZONTAL_RENDER_MODE = 'horizontal';

    /*
     * @todo doplnit další render modes, pokud je to vůbec potřeba
        VERTICAL_RENDER_MODE = 'vertical',
        INLINE_RENDER_MODE = 'inline';
    */

    /**
     * BootstrapFormRenderer constructor.
     * @param string $renderMode
     * @throws \InvalidArgumentException
     */
    public function __construct(string $renderMode = self::HORIZONTAL_RENDER_MODE)
    {
        $this->setRenderMode($renderMode);
    }

    /**
     * @param string $renderMode
     * @return BootstrapFormRenderer
     * @throws \InvalidArgumentException
     */
    public function setRenderMode(string $renderMode): self
    {
        if (!in_array($renderMode, [
            self::HORIZONTAL_RENDER_MODE
            /*, @todo
            self::VERTICAL_RENDER_MODE,
            self::INLINE_RENDER_MODE
            */
        ])) {
            throw new \InvalidArgumentException('Unknown render mode: ' . $renderMode . ', use BootstrapFormRenderer render mode constants.');
        }

        switch($renderMode) {
            case self::HORIZONTAL_RENDER_MODE:
                $this->wrappers = RenderModeHorizontal::$wrappers;
                break;
            /*
             * @todo
            case self::VERTICAL_RENDER_MODE:
                $this->wrappers = RenderModeHorizontal::$wrappers;
                break;
            case self::INLINE_RENDER_MODE:
                $this->wrappers = RenderModeHorizontal::$wrappers;
                break;
            */
        }
        return $this;
    }

    /**
     * Sets default css class names for submit inputs
     * @param string $cssClassnames
     * @return BootstrapFormRenderer
     */
    public function setSubmitButtonClassnames(string $cssClassnames): self
    {
        $this->submitClassnames = $cssClassnames;
        return $this;
    }

    /**
     * Sets default css class names for buttons other than submit inputs
     * @param string $cssClassnames
     * @return BootstrapFormRenderer
     */
    public function setButtonClassnames(string $cssClassnames): self
    {
        $this->buttonClassnames = $cssClassnames;
        return $this;
    }

    /**
     * @param bool $state
     * @return BootstrapFormRenderer
     */
    public function disableDismissibleFormErrors(bool $state = false): self
    {
        $this->dismissibleFormErrors = $state;
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
        return parent::render($form, $mode);
    }

    /**
     * Renders single visual row.
     * @param IControl $control
     * @return string
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

    /**
     * Called when buttons are rendered
     * @param IControl $control
     * @return IControl
     */
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
        } else if (!empty($this->buttonClassnames)) {
            $control->getControlPrototype()->addClass($this->buttonClassnames);
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
     * @param array $controls
     * @return string
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
        if (!empty($control)) {
            $pair->addHtml($this->renderLabel($control));
        }
        $pair->addHtml($this->getWrapper('control container')->setHtml(implode(' ', $s)));
        return $pair->render(0);
    }

    /**
     * @return Html
     */
    protected function getAlertDismissButton()
    {
        // intentionally A tag instead of BUTTON
        $button = Html::el('a',
            [
                'href' => 'javascript:void(0);',
                'class' => 'close',
                'data-dismiss' => 'alert',
                'aria-label' => 'Close',
                'role' => 'button'
            ]
        );
        $button->addHtml(
            Html::el('span',
                [
                    'aria-hidden' => 'true'
                ]
            )->setText(html_entity_decode('&times;'))
        );
        return $button;
    }

    /**
     * Renders validation errors (per form or per control).
     * @param IControl|null $control
     * @param bool $own
     * @param Form|null $form
     * @return string
     */
    public function renderErrors(IControl $control = null, bool $own = true, Form $form = null): string
    {
        if ($control) {
            // leave input errors unchanged
            return parent::renderErrors($control, $own);
        } else {
            $formInstance = ($form) ? $form : $this->form;
            $errors = ($own ? $formInstance->getOwnErrors() : $formInstance->getErrors());
            if (!$errors) {
                return '';
            }
            $container = $this->getWrapper('error container');
            $item = $this->getWrapper('error item');

            foreach ($errors as $error) {
                $item = clone $item;
                if ($error instanceof IHtmlString) {
                    $item->addHtml($error);
                } else {
                    $item->setText($error);
                }

                if ($this->dismissibleFormErrors) {
                    $classNames = $item->getAttribute('class');
                    if (is_array($classNames)) {
                        $classNames['alert-dismissible fade show'] = true;
                    } else {
                        $classNames .= ' alert-dismissible fade show';
                    }
                    $item->setAttribute('class', $classNames);
                    $item->addHtml($this->getAlertDismissButton());
                }

                $container->addHtml($item);
            }
            return "\n" . $container->render($control ? 1 : 0);
        }
    }
}
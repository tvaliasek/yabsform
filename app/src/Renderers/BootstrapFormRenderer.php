<?php declare(strict_types=1);

namespace YABSForm\Renderers;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextBase;
use Nette\Forms\Form;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Tracy\Debugger;
use YABSForm\Controls\CustomCheckbox;

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
            'description' => 'span class="form-text"',
            'requiredsuffix' => '',
            'errorcontainer' => 'span class="form-text"',
            'erroritem' => '',
            '.required' => 'required',
            '.text' => 'text',
            '.password' => 'text',
            '.file' => 'text',
            '.submit' => 'button',
            '.image' => 'imagebutton',
            '.button' => 'button',
        ],
        'label' => [
            'container' => '',
            'suffix' => null,
            'requiredsuffix' => '',
        ],
        'hidden' => [
            'container' => 'div',
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

            /*if (
                $control instanceof BaseControl
            ) {
                $control->getLabelPrototype()->addClass('col-form-label');
            }*/

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
}
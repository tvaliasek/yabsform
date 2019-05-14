<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use YABSForm\Factories\BootstrapFormFactory;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    /**
     * @inject
     * @var BootstrapFormFactory
     */
    public $formFactory;

    public function renderDefault(): void
    {

    }

    protected function createComponentBasicForm(): Nette\Application\UI\Form
    {
        $form = $this->formFactory->create();
        $form->getRenderer()->setSubmitButtonClassnames('btn-lg btn-primary');
        $form->addCheckbox('normal', 'Normal checkbox');
        $form->addCustomCheckbox('switch', 'Custom checkbox switch')
            ->renderAsSwitch(true);
        $form->addCustomCheckbox('custom', 'Custom checkbox')
            ->setRequired(true);
        $form->addCheckboxList('normalList', 'Normal checkbox list', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ]);
        $form->addCustomCheckboxList('customList', 'Custom checkbox list', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ]);
        $form->addCustomCheckboxList('customSwitchesList', 'Custom switches checkbox list', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ])->renderAsSwitches(true)
        ->setRequired();
        $form->addCustomRadioList('customRadioList', 'Custom radio list', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ])->setRequired();
        $form->addSelect('select', 'Normal select', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ])->setRequired();
        $form->addCustomSelect('customSelect', 'Custom select', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ])->setRequired();
        $form->addMultiSelect('multiselect', 'Normal multiselect', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ])->setRequired();
        $form->addCustomMultiSelect('customMultiselect', 'Custom multiselect', [
            'first' => 'first item',
            'second' => 'second item',
            'third' => 'third item'
        ])->setRequired();

        $form->addCustomUpload('customUpload', 'Custom upload')
            ->setBrowseCaption('Procházet')
            ->setRequired(true);

        $form->addCustomRange('customRange', 'Custom range', 0, 2, .5)
            ->setRequired(true)
            ->addRule(Nette\Application\UI\Form::MIN, 'Minimal value is 1.5', 1.5);

        $form->addSubmit('submit', 'Submit');
        return $form;
    }

    private function createTestForm(): Nette\Application\UI\Form
    {
        $form = $this->formFactory->create();
        $form->getRenderer()->setSubmitButtonClassnames('btn-lg btn-primary');
        $form->addText('text', 'Text input label')
            ->setRequired(true);
        $form->addSubmit('submit', 'Submit');
        return $form;
    }

    protected function createComponentNormalForm(): Nette\Application\UI\Form
    {
        return $this->createTestForm();
    }

    protected function createComponentManualForm(): Nette\Application\UI\Form
    {
        return $this->createTestForm();
    }

    protected function createComponentPairForm(): Nette\Application\UI\Form
    {
        return $this->createTestForm();
    }
}

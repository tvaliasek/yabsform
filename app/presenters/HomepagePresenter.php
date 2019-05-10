<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use YABSForm\Controls\CustomCheckbox;
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
        $form->addCheckbox('normal', 'Normal checkbox');
        $form->addCustomCheckbox('switch', 'Custom checkbox switch');
        $form->addCustomCheckbox('custom', 'Custom checkbox')
            ->setRequired(true);
        $form->addSubmit('submit', 'Submit');
        return $form;
    }
}

<?php declare(strict_types=1);

namespace YABSForm\Factories;

use Nette\Application\UI\Form;
use YABSForm\Renderers\BootstrapFormRenderer;

class BootstrapFormFactory
{

    public function create(): Form
    {
        $form = new Form();
        $form->setRenderer(new BootstrapFormRenderer());
        return $form;
    }
}
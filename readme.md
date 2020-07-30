YABSForm - Nette 3 Bootstrap form renderer with custom controls support
=================

Yet another Bootstrap 4 form renderer and custom form components for [Nette framework](https://nette.org).
Inspired by [contributte/forms](https://github.com/contributte/forms), [nextras/forms](https://github.com/nextras/forms) and [Kdyby/BootstrapFormRenderer](https://github.com/Kdyby/BootstrapFormRenderer).

Todo: 
 - write tests
 
Requirements
------------

- PHP 7.3

Installation
------------
Install with composer:

	composer require tvaliasek/yabsform
	
Register extensions in your *.neon config.

    extensions:
        yabsform.controls: YABSForm\DI\FormControlsExtension
        yabsform.formFactory: YABSForm\DI\FormFactoryExtension
        yabsform.formMacros: YABSForm\DI\FormMacrosExtension
        
Usage
----------------

The simplest way is to use provided form factory, which creates 
Form instance and sets renderer to BootstrapFormRenderer.

	/* example in presenter */
	
	/**
    * @inject
    * @var \YABSForm\Factories\BootstrapFormFactory
    */
    public $formFactory;
    
    protected function createComponentExampleForm(): Form
    {
        $form = $this->formFactory->create();
        /* ... add controls, callbacks etc. */
        return $form;
    }
    
    /* example in component or service */
  
    /**
    * @var \YABSForm\Factories\BootstrapFormFactory
    */
    private $formFactory;
    
    public function __construct(\YABSForm\Factories\BootstrapFormFactory $formFactory)
    {
        $this->formFactory = $formFactory
    }

### Custom methods in renderer ###

Renderer provide few methods whose allow common modifications of rendered output.

    $renderer = $form->getRenderer();
    
    // Sets default css class names for submit inputs
    $renderer->setSubmitButtonClassnames('btn-lg btn-primary');
    
    // Sets default css class names for buttons other than submit inputs
    $renderer->setButtonClassnames('btn-secondary');
    
    // Disables dismissibility of form errors presented as bootstrap alerts
    $renderer->disableDismissibleFormErrors()
    
    // Set globally size of controls
    $renderer->setControlSize('lg')

    // Set globally columns for labels and for controls (in vertical render mode)
    $renderer->setLabelColumns(12, null, null, 4, null)
    $renderer->setControlColumns(12, null, null, 8, null)

    
### Custom form macros ###

This package also provides few custom macros for more control over form layout.
All of them are unpaired and does not accept more parameters.

    {* in latte template *}
    {form exampleForm}
        {* renders form errors *}
        {bsErrors}
        {* renders form own errors *}
        {bsOwnErrors}
        
        {* renders whole form-group with input, feedback and label *}
        {bsPair exampleInput}
        
        {* renders only label*}
        {bsLabel exampleInput}
        
        {* renders only input *}
        {bsInput exampleInput}
    {/form}
    
### Custom Bootstrap form elements ###

Available is:
 - CustomCheckbox - renderable also as switch
 - CustomCheckboxList - renderable also as switches
 - CustomMultiSelect
 - CustomRadioList
 - CustomRange
 - CustomSelect
 - CustomUpload - see [bootstrap docs](https://getbootstrap.com/docs/4.3/components/forms/#file-browser) for displaying names of selected files
 - DatePicker and DateTimePicker is in separated repo here [Nextras Forms Fork](https://github.com/tvaliasek/nextras-forms-fork)
 
For properly displayed invalid feedback message, you should include this additional style:
    
    <style>
        .__yabsform-is-invalid ~ .invalid-feedback,
        .custom-range.is-invalid ~ .invalid-feedback,
        .custom-file.is-invalid ~ .invalid-feedback,
        .custom-control.is-invalid ~ .invalid-feedback{
            display: block;
        }
    </style>
    
Example of usage in form:

    $form->addCustomCheckbox('switch', 'Custom checkbox switch')
        ->renderAsSwitch(true);
    $form->addCustomCheckbox('custom', 'Custom checkbox');
    
    $form->addCustomCheckboxList('customList', 'Custom checkbox list', [
        'first' => 'first item',
        'second' => 'second item',
        'third' => 'third item'
    ]);
    $form->addCustomCheckboxList('customSwitchesList', 'Custom switches checkbox list', [
        'first' => 'first item',
        'second' => 'second item',
        'third' => 'third item'
    ])->renderAsSwitches(true);
    
    $form->addCustomRadioList('customRadioList', 'Custom radio list', [
        'first' => 'first item',
        'second' => 'second item',
        'third' => 'third item'
    ]);
    
    $form->addCustomSelect('customSelect', 'Custom select', [
        'first' => 'first item',
        'second' => 'second item',
        'third' => 'third item'
    ]);
    
    $form->addCustomMultiSelect('customMultiselect', 'Custom multiselect', [
        'first' => 'first item',
        'second' => 'second item',
        'third' => 'third item'
    ]);
    
    $form->addCustomUpload('customUpload', 'Custom upload')
        ->setBrowseCaption('Procházet');
    
    $form->addCustomRange('customRange', 'Custom range', 0, 2, .5)
        ->setRequired(true);
        

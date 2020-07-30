<?php


namespace YABSForm\RenderModes;


class RenderModeVertical
{
    /** @var mixed[] */
    public static $wrappers = [
        'form' => [
            'container' => null,
        ],
        'error' => [
            'container' => 'div',
            'item' => 'p class="alert alert-danger"',
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
            'container' => 'div class="form-group row"',
            '.required' => 'required',
            '.optional' => null,
            '.odd' => null,
        ],
        'control' => [
            'container' => 'div',
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
        ]
    ];
}
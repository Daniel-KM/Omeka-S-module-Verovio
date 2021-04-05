<?php
namespace Verovio\Form;

use Omeka\Form\Element\PropertySelect;
use Laminas\Form\Fieldset;

class SettingsFieldset extends Fieldset
{
    protected $label = 'Verovio MEI viewer'; // @translate

    public function init()
    {
        $this
            ->add([
                'name' => 'verovio_source_property',
                'type' => PropertySelect::class,
                'options' => [
                    'label' => 'Property used for external MEI', // @translate
                    'info' => 'The property supplying the MEI file via URL, for example "dcterms:hasFormat" or "dcterms:isFormatOf".', // @translate
                    'empty_option' => '',
                    'term_as_value' => true,
                ],
                'attributes' => [
                    'id' => 'verovio_source_property',
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Select a property…', // @translate
                ],
            ])
        ;
    }
}

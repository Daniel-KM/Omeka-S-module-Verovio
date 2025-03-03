<?php declare(strict_types=1);

namespace Verovio\Form;

use Common\Form\Element as CommonElement;
use Laminas\Form\Fieldset;

class SettingsFieldset extends Fieldset
{
    protected $label = 'Verovio MEI viewer'; // @translate

    public function init(): void
    {
        $this
            ->setAttribute('id', 'verovio')
            ->add([
                'name' => 'verovio_source_property',
                'type' => CommonElement\OptionalPropertySelect::class,
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

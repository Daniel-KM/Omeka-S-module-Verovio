<?php
namespace Verovio\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;

class SiteSettingsFieldset extends Fieldset
{
    protected $label = 'Verovio MEI viewer'; // @translate

    public function init()
    {
        $this
            ->add([
                'name' => 'verovio_template',
                'type' => Element\Radio::class,
                'options' => [
                    'label' => 'Verovio template', // @translate
                    'value_options' => [
                        'app' => 'App (simple viewer)', // @translate
                        'custom' => 'Custom (via theme)', // @translate
                    ],
                ],
                'attributes' => [
                    'id' => 'verovio_template',
                ],
            ])
        ;
    }
}

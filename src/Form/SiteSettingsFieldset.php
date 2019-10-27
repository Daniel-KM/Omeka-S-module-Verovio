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
                        // Same options than the block.
                        'common/verovio' => 'App (simple viewer)', // @translate
                        'common/verovio-mei-viewer' => 'Official (Bootstrap 3)', // @translate
                        'common/verovio-toolkit' => 'Custom (via theme)', // @translate
                    ],
                ],
                'attributes' => [
                    'id' => 'verovio_template',
                ],
            ])
        ;
    }
}

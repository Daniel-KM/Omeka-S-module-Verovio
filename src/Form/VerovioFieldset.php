<?php declare(strict_types=1);

namespace Verovio\Form;

use Laminas\Form\Element;
use Laminas\Form\Fieldset;

class VerovioFieldset extends Fieldset
{
    public function init(): void
    {
        $this
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][heading]',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'Block title', // @translate
                ],
                'attributes' => [
                    'id' => 'verovio-heading',
                    'required' => false,
                ],
            ])
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][source]',
                'type' => Element\Url::class,
                'options' => [
                    'label' => 'Source', // @translate
                    'info' => 'The url to display. Note: media attached to items are automatically rendered via common blocks, in particular "Media".', // @translate
                ],
                'attributes' => [
                    'id' => 'verovio-source',
                    'required' => true,
                ],
            ])
        ;
    }
}

<?php declare(strict_types=1);

namespace Verovio\Form;

use Common\Form\Element as CommonElement;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;

class VerovioFieldset extends Fieldset
{
    public function init(): void
    {
        $versions = include dirname(__DIR__, 2) . '/data/player/versions.php';

        $this
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
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][variant]',
                'type' => CommonElement\OptionalSelect::class,
                'options' => [
                    'label' => 'Toolkit variant', // @translate
                    'info' => ' ',
                    'documentation' => 'https://book.verovio.org/installing-or-building-from-sources/javascript-and-webassembly.html',
                    'value_options' => [
                        'wasm' => 'Wasm (recommended)', // @translate
                        'hum' => 'Humdrum', // @translate
                        'asm' => 'Asm (to support old browsers)', // @translate
                    ],
                    'empty_option' => '',
                ],
                'attributes' => [
                    'id' => 'verovio-variant',
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Select a variant…', // @translate
                ],
            ])
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][version]',
                'type' => CommonElement\OptionalSelect::class,
                'options' => [
                    'label' => 'Version', // @translate
                    'value_options' => array_combine($versions, $versions),
                    'empty_option' => '',
                ],
                'attributes' => [
                    'id' => 'verovio-version',
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Select a version…', // @translate
                ],
            ])
        ;
    }
}

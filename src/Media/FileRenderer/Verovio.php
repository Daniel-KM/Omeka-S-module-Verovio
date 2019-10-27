<?php
namespace Verovio\Media\FileRenderer;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Zend\View\Renderer\PhpRenderer;

class Verovio implements RendererInterface
{
    /**
     * The default partial view script.
     */
    const PARTIAL_NAME = 'common/renderer/verovio';

    /**
     * @var array
     */
    protected $defaultOptions = [
        'attributes' => 'allowfullscreen="allowfullscreen" style="height: 600px; height: 70vh; border: 1px solid lightgray;"',
        'template' => 'app',
    ];

    /**
     * Render a xml-mei file via verovio library.
     *
     * @todo Factorize with the view helper.
     *
     * @param PhpRenderer $view,
     * @param MediaRepresentation $media
     * @param array $options These options are managed for sites:
     *   - template: the partial to use
     *   - attributes: set the attributes to add
     * @return string
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        // Omeka 1.2.0 doesn't support $view->status().
        $isPublic = $view->params()->fromRoute('__SITE__');
        if ($isPublic) {
            $siteSetting = $view->plugin('siteSetting');
            $options['attributes'] = isset($options['attributes'])
                ? $options['attributes']
                : $siteSetting('verovio_attributes', $this->defaultOptions['attributes']);
            $template = isset($options['template'])
                ? $options['template']
                : $siteSetting('verovio_template', $this->defaultOptions['template']);
        } else {
            $options['attributes'] = $this->defaultOptions['attributes'];
            $template = $this->defaultOptions['template'];
        }

        $templates = [
            'app' => 'common/renderer/verovio',
            'web' => 'common/renderer/verovio-mei-viewer',
            'custom' => 'common/renderer/verovio-toolkit',
        ];
        $template = isset($templates[$template])
            ? $templates[$template]
            : self::PARTIAL_NAME;
        unset($options['template']);

        return $view->partial($template, [
            'media' => $media,
            'options' => $options,
        ]);
    }
}

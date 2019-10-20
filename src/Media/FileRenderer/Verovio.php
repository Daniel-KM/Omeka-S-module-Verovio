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
     * These options are used only when the player is called outside of a site
     * or when the site settings are not set.
     *
     * @var array
     */
    protected $defaultOptions = [
        'attributes' => 'allowfullscreen="allowfullscreen" style="height: 600px; height: 70vh"',
    ];

    /**
     * Render a xml-mei file via verovio library.
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
        $isAdmin = $view->params()->fromRoute('__ADMIN__');
        if ($isAdmin) {
            $options['attributes'] = $this->defaultOptions['attributes'];
        } else {
            $options['attributes'] = isset($options['attributes'])
                ? $options['attributes']
                : $view->siteSetting('verovio_attributes', $this->defaultOptions['attributes']);
        }

        $template = isset($options['template']) ? $options['template'] : self::PARTIAL_NAME;
        unset($options['template']);
        return $view->partial($template, [
            'media' => $media,
            'options' => $options,
        ]);
    }
}

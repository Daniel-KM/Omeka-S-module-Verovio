<?php declare(strict_types=1);

namespace Verovio\Media\FileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;

/**
 * @todo Factorize with the view helper.
 * @see \Verovio\Media\FileRenderer\Verovio
 * @see \Verovio\View\Helper\Verovio
 */
class Verovio implements RendererInterface
{
    /**
     * The default partial view script.
     */
    const PARTIAL_NAME = 'common/verovio';

    /**
     * @var array
     */
    protected $defaultOptions = [
        'variant' => 'wasm',
        'version' => 'local',
        'attributes' => 'allowfullscreen="allowfullscreen" style="height: 600px; height: 70vh; border: 1px solid lightgray;"',
        'template' => self::PARTIAL_NAME,
    ];

    /**
     * Render a xml-mei file via verovio library.
     *
     * @param PhpRenderer $view,
     * @param MediaRepresentation $media
     * @param array $options These options are managed for sites:
     * - template (string): the partial to use
     * - source (string): It must contains source url if resource is not set.
     * - variant (string): the variant to use for some templates
     * - version (string): the version to use for some templates
     * - attributes (array): set the attributes to add
     * @return string
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = []): string
    {
        $status = $view->status();
        if ($status->isSiteRequest()) {
            $siteSetting = $view->plugin('siteSetting');
            $template ??= $siteSetting('verovio_template', $this->defaultOptions['template']);
            $options['variant'] ??= $siteSetting('verovio_variant', $this->defaultOptions['variant']);
            $options['version'] ??= $siteSetting('verovio_version', $this->defaultOptions['version']);
            $options['attributes'] = $options['attributes'] ?? $this->defaultOptions['attributes'];
        } else {
            $template = $this->defaultOptions['template'];
            $options['variant'] = $this->defaultOptions['variant'];
            $options['version'] = $this->defaultOptions['version'];
            $options['attributes'] = $this->defaultOptions['attributes'];
        }

        unset($options['template']);

        $vars = ['resource' => $media]
            + $options
            + [
                'source' => null,
                'heading' => null,
                'variant' => null,
                'version' => null,
                'attributes' => [],
            ];

        // For compatibility with old themes.
        $vars['options'] = $options;

        return $view->partial($template, $vars);
    }
}

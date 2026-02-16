<?php declare(strict_types=1);

namespace Verovio\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

/**
 * @todo Factorize with the view helper.
 * @see \Verovio\Media\FileRenderer\Verovio
 * @see \Verovio\View\Helper\Verovio
 */
class Verovio extends AbstractHelper
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
     * Get the Verovio Viewer for the provided resource.
     *
     * Proxies to {@link render()}.
     *
     * @param AbstractResourceEntityRepresentation|null $resource
     * @param array $options Managed options:
     * - template (string)
     * - source (string): It is used when set in place of resource
     * - heading (string)
     * - variant (string): the variant to use for some templates
     * - version (string): the version to use for some templates
     * - attributes (array): set the attributes to add
     * @return string Html string corresponding to the viewer.
     */
    public function __invoke(?AbstractResourceEntityRepresentation $resource, $options = []): string
    {
        if (isset($options['source'])) {
            return $this->render($resource, $options);
        }

        if (empty($resource)) {
            return '';
        }

        if (is_array($resource)) {
            return '';
        }

        $view = $this->getView();

        // Determine the url of the source from a field in metadata. No check.
        $sourceProperty = $view->setting('verovio_source_property');
        if ($sourceProperty) {
            $urlSource = $resource->value($sourceProperty);
            if ($urlSource) {
                // Manage the case where the url is saved as an uri or a text.
                $options['source'] = $urlSource->uri() ?: $urlSource->value();
                return $this->render($resource, $options);
            }
        }

        $resourceName = $resource->resourceName();
        if ($resourceName === 'item') {
            $medias = $resource->media();
            // Get the media that is readable by the viewer.
            foreach ($medias as $media) {
                if ($media->renderer() === 'verovio') {
                    $options['source'] = $media->originalUrl();
                    return $this->render($resource, $options);
                }
            }
            return '';
        }

        $media = $resource->primaryMedia();
        if ($media && $media->renderer() !== 'verovio') {
            $options['source'] = $media->originalUrl();
            return $this->render($resource, $options);
        }

        return '';
    }

    /**
     * Render a verovio viewer for a resource, according to options.
     *
     * @param AbstractResourceEntityRepresentation $resource
     * @param array $options These options are managed for sites:
     * - template (string): the partial to use
     * - source (string): It is used when set in place of resource
     * - heading (string)
     * - variant (string)
     * - version (string)
     * - attributes (array): set the attributes to add
     * @return string
     */
    protected function render(?AbstractResourceEntityRepresentation $resource, array $options = []): string
    {
        $view = $this->getView();

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

        $vars = ['resource' => $resource]
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

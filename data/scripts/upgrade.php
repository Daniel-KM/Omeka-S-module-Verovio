<?php declare(strict_types=1);

namespace Verovio;

use Common\Stdlib\PsrMessage;

/**
 * @var Module $this
 * @var \Laminas\ServiceManager\ServiceLocatorInterface $services
 * @var string $newVersion
 * @var string $oldVersion
 *
 * @var \Omeka\Api\Manager $api
 * @var \Omeka\View\Helper\Url $url
 * @var \Laminas\Log\Logger $logger
 * @var \Omeka\Settings\Settings $settings
 * @var \Laminas\I18n\View\Helper\Translate $translate
 * @var \Doctrine\DBAL\Connection $connection
 * @var \Laminas\Mvc\I18n\Translator $translator
 * @var \Doctrine\ORM\EntityManager $entityManager
 * @var \Omeka\Settings\SiteSettings $siteSettings
 * @var \Omeka\Mvc\Controller\Plugin\Messenger $messenger
 */
$plugins = $services->get('ControllerPluginManager');
$api = $plugins->get('api');
$logger = $services->get('Omeka\Logger');
$settings = $services->get('Omeka\Settings');
$translate = $plugins->get('translate');
$translator = $services->get('MvcTranslator');
$connection = $services->get('Omeka\Connection');
$messenger = $plugins->get('messenger');
$siteSettings = $services->get('Omeka\Settings\Site');
$entityManager = $services->get('Omeka\EntityManager');

if (!method_exists($this, 'checkModuleActiveVersion') || !$this->checkModuleActiveVersion('Common', '3.4.79')) {
    $message = new \Omeka\Stdlib\Message(
        $translate('The module %1$s should be upgraded to version %2$s or later.'), // @translate
        'Common', '3.4.79'
    );
    throw new \Omeka\Module\Exception\ModuleCannotInstallException((string) $message);
}

// Always update whitelist to ensure new media types and extensions are
// authorized when support is added in newer versions.
$this->updateWhitelist();

if (version_compare($oldVersion, '3.4.8', '<')) {

    /**
     * Migrate blocks of this module to new blocks of Omeka S v4.1.
     *
     * Replace filled settting "heading" by a specific block "Heading".
     * Move setting template to block layout template.
     *
     * @see \Omeka\Db\Migrations\MigrateBlockLayoutData
     */

    $pageRepository = $entityManager->getRepository(\Omeka\Entity\SitePage::class);

    $viewHelpers = $services->get('ViewHelperManager');
    $escape = $viewHelpers->get('escapeHtml');
    $hasBlockPlus = $this->isModuleActive('BlockPlus');

    $newTemplates = [
        'verovio' => 'verovio',
        'verovio-toolkit' => 'verovio-toolkit',
        'verovio-mei-viewer' => 'verovio-toolkit-bootstrap-3',
        'verovio-viewer' => 'verovio-toolkit-bootstrap-4',
    ];

    $pagesUpdated = [];
    $pagesUpdated2 = [];
    foreach ($pageRepository->findAll() as $page) {
        $pageSlug = $page->getSlug();
        $siteSlug = $page->getSite()->getSlug();
        $position = 0;
        foreach ($page->getBlocks() as $block) {
            $block->setPosition(++$position);
            $layout = $block->getLayout();
            if ($layout !== 'verovio') {
                continue;
            }
            $data = $block->getData() ?: [];

            $heading = $data['heading'] ?? '';
            if (strlen($heading)) {
                $b = new \Omeka\Entity\SitePageBlock();
                $b->setPage($page);
                $b->setPosition(++$position);
                if ($hasBlockPlus) {
                    $b->setLayout('heading');
                    $b->setData([
                        'text' => $heading,
                        'level' => 2,
                    ]);
                } else {
                    $b->setLayout('html');
                    $b->setData([
                        'html' => '<h2>' . $escape($heading) . '</h2>',
                    ]);
                }
                $entityManager->persist($b);
                $block->setPosition(++$position);
                $pagesUpdated[$siteSlug][$pageSlug] = $pageSlug;
            }
            unset($data['heading']);
            $data['variant'] = 'wasm';
            $data['version'] = 'local';

            $template = $data['template'] ?? null;
            if ($template) {
                $layoutData = $block->getLayoutData();
                $layoutData['template_name'] = $newTemplates[pathinfo($template, PATHINFO_FILENAME)]
                    ?? pathinfo($template, PATHINFO_FILENAME);
                $block->setLayoutData($layoutData);
                $pagesUpdated2[$siteSlug][$pageSlug] = $pageSlug;
            }
            unset($data['template']);

            $block->setData($data);
        }
    }

    $entityManager->flush();
    $entityManager->clear();

    if ($pagesUpdated) {
        $result = array_map('array_values', $pagesUpdated);
        $message = new PsrMessage(
            'The setting "heading" was removed from block Verovio. New blocks "Heading" or "Html" were prepended to all blocks that had a filled heading. You may check pages for styles: {json}', // @translate
            ['json' => json_encode($result, 448)]
        );
        $messenger->addWarning($message);
        $logger->warn($message->getMessage(), $message->getContext());
    }

    if ($pagesUpdated2) {
        $result = array_map('array_values', $pagesUpdated2);
        $message = new PsrMessage(
            'The setting "template" was moved to the new block layout settings available since Omeka S v4.1. You may check pages for styles: {json}', // @translate
            ['json' => json_encode($result, 448)]
        );
        $messenger->addWarning($message);
        $logger->warn($message->getMessage(), $message->getContext());

        $message = new PsrMessage(
            'The template files for the block Verovio should be moved from "view/common/block-layout" to "view/common/block-template" in your themes. You may check your themes for pages: {json}', // @translate
            ['json' => json_encode($result, 448)]
        );
        $messenger->addError($message);
        $logger->warn($message->getMessage(), $message->getContext());
    }

    $siteSettings = $services->get('Omeka\Settings\Site');
    $siteIds = $api->search('sites', [], ['returnScalar' => 'id'])->getContent();
    foreach ($siteIds as $siteId) {
        $siteSettings->setTargetId($siteId);
        $siteTemplate = basename($siteSettings->get('verovio_template', 'common/verovio') ?: 'common/verovio');
        $siteSettings->set('verovio_template', 'common/' . ($newTemplates[$siteTemplate] ?? $siteTemplate));
    }

    $message = new PsrMessage(
        'A new option allows to use the version of Verovio provided online.' // @translate
    );
    $messenger->addWarning($message);
}

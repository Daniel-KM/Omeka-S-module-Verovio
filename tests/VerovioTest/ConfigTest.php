<?php declare(strict_types=1);

namespace VerovioTest;

use CommonTest\AbstractHttpControllerTestCase;
use Verovio\Media\FileRenderer\Verovio as VerovioRenderer;
use Verovio\Site\BlockLayout\Verovio as VerovioBlockLayout;
use Verovio\View\Helper\Verovio as VerovioViewHelper;

/**
 * Tests for Verovio module configuration and service registration.
 */
class ConfigTest extends AbstractHttpControllerTestCase
{
    use VerovioTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loginAdmin();
    }

    public function testModuleIsActive(): void
    {
        $services = $this->getServiceLocator();
        $moduleManager = $services->get('Omeka\ModuleManager');
        $module = $moduleManager->getModule('Verovio');
        $this->assertNotNull($module, 'Module Verovio should be registered.');
        $this->assertEquals('active', $module->getState(), 'Module Verovio should be active.');
    }

    public function testConfigHasSettings(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $this->assertArrayHasKey('verovio', $config);
        $this->assertArrayHasKey('settings', $config['verovio']);
        $this->assertArrayHasKey('site_settings', $config['verovio']);
        $this->assertArrayHasKey('block_settings', $config['verovio']);
    }

    public function testDefaultSettingsValues(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $settings = $config['verovio']['settings'];
        $this->assertArrayHasKey('verovio_source_property', $settings);
        $this->assertNull($settings['verovio_source_property']);

        $siteSettings = $config['verovio']['site_settings'];
        $this->assertEquals('common/verovio', $siteSettings['verovio_template']);
        $this->assertEquals('wasm', $siteSettings['verovio_variant']);
        $this->assertEquals('local', $siteSettings['verovio_version']);

        $blockSettings = $config['verovio']['block_settings']['verovio'];
        $this->assertEquals('', $blockSettings['source']);
        $this->assertEquals('wasm', $blockSettings['variant']);
        $this->assertEquals('local', $blockSettings['version']);
    }

    public function testFileRenderersAreRegistered(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $renderers = $config['file_renderers']['invokables'];
        $expected = [
            'verovio',
            'application/vnd.mei+xml',
            'application/vnd.recordare.musicxml',
            'mei',
            'musicxml',
            'mxl',
        ];
        foreach ($expected as $name) {
            $this->assertArrayHasKey($name, $renderers, "Renderer '$name' should be registered.");
            $this->assertEquals(VerovioRenderer::class, $renderers[$name], "Renderer '$name' should map to VerovioRenderer.");
        }
    }

    public function testViewHelperIsRegistered(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $helpers = $config['view_helpers']['invokables'];
        $this->assertArrayHasKey('verovio', $helpers);
        $this->assertEquals(VerovioViewHelper::class, $helpers['verovio']);
    }

    public function testBlockLayoutIsRegistered(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $layouts = $config['block_layouts']['invokables'];
        $this->assertArrayHasKey('verovio', $layouts);
        $this->assertEquals(VerovioBlockLayout::class, $layouts['verovio']);
    }

    public function testFormElementsAreRegistered(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $formElements = $config['form_elements']['invokables'];
        $this->assertArrayHasKey(\Verovio\Form\SettingsFieldset::class, $formElements);
        $this->assertArrayHasKey(\Verovio\Form\SiteSettingsFieldset::class, $formElements);
        $this->assertArrayHasKey(\Verovio\Form\VerovioFieldset::class, $formElements);
    }

    public function testBlockTemplatesAreRegistered(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $this->assertArrayHasKey('block_templates', $config);
        $this->assertArrayHasKey('verovio', $config['block_templates']);

        $templates = $config['block_templates']['verovio'];
        $this->assertArrayHasKey('verovio-toolkit', $templates);
        $this->assertArrayHasKey('verovio-toolkit-bootstrap-3', $templates);
        $this->assertArrayHasKey('verovio-toolkit-bootstrap-4', $templates);
    }

    public function testViewTemplatePathIsRegistered(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $templatePaths = $config['view_manager']['template_path_stack'];
        $moduleViewPath = realpath(dirname(__DIR__, 2) . '/view');
        $found = false;
        foreach ($templatePaths as $path) {
            if (realpath($path) === $moduleViewPath) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Module view path should be in template_path_stack.');
    }

    public function testTranslatorIsConfigured(): void
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $this->assertArrayHasKey('translator', $config);
        $patterns = $config['translator']['translation_file_patterns'];
        $moduleLanguagePath = realpath(dirname(__DIR__, 2) . '/language');
        $found = false;
        foreach ($patterns as $pattern) {
            if (isset($pattern['base_dir']) && realpath($pattern['base_dir']) === $moduleLanguagePath) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Module language path should be in translation_file_patterns.');
    }
}

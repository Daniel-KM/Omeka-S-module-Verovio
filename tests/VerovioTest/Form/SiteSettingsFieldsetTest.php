<?php declare(strict_types=1);

namespace VerovioTest\Form;

use CommonTest\AbstractHttpControllerTestCase;
use Verovio\Form\SiteSettingsFieldset;
use VerovioTest\VerovioTestTrait;

/**
 * Tests for the Verovio site settings fieldset.
 */
class SiteSettingsFieldsetTest extends AbstractHttpControllerTestCase
{
    use VerovioTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loginAdmin();
    }

    public function testFieldsetIsInstantiable(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);
        $this->assertInstanceOf(SiteSettingsFieldset::class, $fieldset);
    }

    public function testFieldsetHasTemplateElement(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);
        $this->assertTrue($fieldset->has('verovio_template'));
    }

    public function testFieldsetHasVariantElement(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);
        $this->assertTrue($fieldset->has('verovio_variant'));
    }

    public function testFieldsetHasVersionElement(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);
        $this->assertTrue($fieldset->has('verovio_version'));
    }

    public function testFieldsetLabel(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);
        $this->assertEquals('Verovio MEI viewer', $fieldset->getLabel());
    }

    public function testFieldsetId(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);
        $this->assertEquals('verovio', $fieldset->getAttribute('id'));
    }

    public function testTemplateElementHasExpectedValueOptions(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);

        $element = $fieldset->get('verovio_template');
        $valueOptions = $element->getValueOptions();
        $this->assertArrayHasKey('common/verovio', $valueOptions);
        $this->assertArrayHasKey('common/verovio-toolkit', $valueOptions);
        $this->assertArrayHasKey('common/verovio-toolkit-bootstrap-5', $valueOptions);
    }

    public function testVariantElementHasExpectedValueOptions(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);

        $element = $fieldset->get('verovio_variant');
        $valueOptions = $element->getValueOptions();
        $this->assertArrayHasKey('wasm', $valueOptions);
        $this->assertArrayHasKey('hum', $valueOptions);
        $this->assertArrayHasKey('asm', $valueOptions);
    }

    public function testVersionElementHasLocalOption(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SiteSettingsFieldset::class);

        $element = $fieldset->get('verovio_version');
        $valueOptions = $element->getValueOptions();
        $this->assertArrayHasKey('local', $valueOptions);
        $this->assertArrayHasKey('latest', $valueOptions);
    }
}

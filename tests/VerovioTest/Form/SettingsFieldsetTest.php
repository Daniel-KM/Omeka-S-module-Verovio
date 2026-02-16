<?php declare(strict_types=1);

namespace VerovioTest\Form;

use CommonTest\AbstractHttpControllerTestCase;
use Verovio\Form\SettingsFieldset;
use VerovioTest\VerovioTestTrait;

/**
 * Tests for the Verovio settings fieldset.
 */
class SettingsFieldsetTest extends AbstractHttpControllerTestCase
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
        $fieldset = $formElementManager->get(SettingsFieldset::class);
        $this->assertInstanceOf(SettingsFieldset::class, $fieldset);
    }

    public function testFieldsetHasSourcePropertyElement(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SettingsFieldset::class);
        $this->assertTrue($fieldset->has('verovio_source_property'));
    }

    public function testFieldsetLabel(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SettingsFieldset::class);
        $this->assertEquals('Verovio MEI viewer', $fieldset->getLabel());
    }

    public function testFieldsetId(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(SettingsFieldset::class);
        $this->assertEquals('verovio', $fieldset->getAttribute('id'));
    }
}

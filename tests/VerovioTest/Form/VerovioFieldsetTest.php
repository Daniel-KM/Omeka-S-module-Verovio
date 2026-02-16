<?php declare(strict_types=1);

namespace VerovioTest\Form;

use CommonTest\AbstractHttpControllerTestCase;
use Verovio\Form\VerovioFieldset;
use VerovioTest\VerovioTestTrait;

/**
 * Tests for the Verovio block fieldset.
 */
class VerovioFieldsetTest extends AbstractHttpControllerTestCase
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
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $this->assertInstanceOf(VerovioFieldset::class, $fieldset);
    }

    public function testFieldsetHasSourceElement(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $this->assertTrue($fieldset->has('o:block[__blockIndex__][o:data][source]'));
    }

    public function testFieldsetHasVariantElement(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $this->assertTrue($fieldset->has('o:block[__blockIndex__][o:data][variant]'));
    }

    public function testFieldsetHasVersionElement(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $this->assertTrue($fieldset->has('o:block[__blockIndex__][o:data][version]'));
    }

    public function testSourceElementIsUrlType(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $element = $fieldset->get('o:block[__blockIndex__][o:data][source]');
        $this->assertInstanceOf(\Laminas\Form\Element\Url::class, $element);
    }

    public function testSourceElementIsRequired(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $element = $fieldset->get('o:block[__blockIndex__][o:data][source]');
        $this->assertEquals('true', $element->getAttribute('required'));
    }

    public function testVariantElementHasExpectedValueOptions(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $element = $fieldset->get('o:block[__blockIndex__][o:data][variant]');
        $valueOptions = $element->getValueOptions();
        $this->assertArrayHasKey('wasm', $valueOptions);
        $this->assertArrayHasKey('hum', $valueOptions);
        $this->assertArrayHasKey('asm', $valueOptions);
    }

    public function testVersionElementHasLocalOption(): void
    {
        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');
        $fieldset = $formElementManager->get(VerovioFieldset::class);
        $element = $fieldset->get('o:block[__blockIndex__][o:data][version]');
        $valueOptions = $element->getValueOptions();
        $this->assertArrayHasKey('local', $valueOptions);
    }
}

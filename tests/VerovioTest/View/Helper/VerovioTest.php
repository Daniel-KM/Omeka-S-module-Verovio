<?php declare(strict_types=1);

namespace VerovioTest\View\Helper;

use CommonTest\AbstractHttpControllerTestCase;
use Verovio\View\Helper\Verovio;
use VerovioTest\VerovioTestTrait;

/**
 * Tests for the Verovio view helper.
 */
class VerovioTest extends AbstractHttpControllerTestCase
{
    use VerovioTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loginAdmin();
    }

    public function testViewHelperIsRegistered(): void
    {
        $services = $this->getServiceLocator();
        $viewHelperManager = $services->get('ViewHelperManager');
        $this->assertTrue($viewHelperManager->has('verovio'));
    }

    public function testViewHelperIsInstantiable(): void
    {
        $services = $this->getServiceLocator();
        $viewHelperManager = $services->get('ViewHelperManager');
        $helper = $viewHelperManager->get('verovio');
        $this->assertInstanceOf(Verovio::class, $helper);
    }

    public function testDefaultPartialName(): void
    {
        $this->assertEquals('common/verovio', Verovio::PARTIAL_NAME);
    }

    public function testHelperExtendsAbstractHelper(): void
    {
        $helper = new Verovio();
        $this->assertInstanceOf(\Laminas\View\Helper\AbstractHelper::class, $helper);
    }

    public function testHelperIsInvokable(): void
    {
        $this->assertTrue(is_callable(new Verovio()));
    }
}

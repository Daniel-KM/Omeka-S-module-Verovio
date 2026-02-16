<?php declare(strict_types=1);

namespace VerovioTest\Site\BlockLayout;

use CommonTest\AbstractHttpControllerTestCase;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Omeka\Site\BlockLayout\TemplateableBlockLayoutInterface;
use Verovio\Site\BlockLayout\Verovio;
use VerovioTest\VerovioTestTrait;

/**
 * Tests for the Verovio block layout.
 */
class VerovioTest extends AbstractHttpControllerTestCase
{
    use VerovioTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loginAdmin();
    }

    public function testBlockLayoutIsRegistered(): void
    {
        $services = $this->getServiceLocator();
        $blockLayoutManager = $services->get('Omeka\BlockLayoutManager');
        $this->assertTrue($blockLayoutManager->has('verovio'));
    }

    public function testBlockLayoutIsInstantiable(): void
    {
        $services = $this->getServiceLocator();
        $blockLayoutManager = $services->get('Omeka\BlockLayoutManager');
        $layout = $blockLayoutManager->get('verovio');
        $this->assertInstanceOf(Verovio::class, $layout);
    }

    public function testBlockLayoutExtendsAbstractBlockLayout(): void
    {
        $layout = new Verovio();
        $this->assertInstanceOf(AbstractBlockLayout::class, $layout);
    }

    public function testBlockLayoutImplementsTemplateableInterface(): void
    {
        $layout = new Verovio();
        $this->assertInstanceOf(TemplateableBlockLayoutInterface::class, $layout);
    }

    public function testBlockLayoutLabel(): void
    {
        $layout = new Verovio();
        $this->assertEquals('Verovio viewer', $layout->getLabel());
    }

    public function testDefaultPartialName(): void
    {
        $this->assertEquals('common/block-layout/verovio', Verovio::PARTIAL_NAME);
    }
}

<?php declare(strict_types=1);

namespace VerovioTest\Media\FileRenderer;

use CommonTest\AbstractHttpControllerTestCase;
use Omeka\Media\FileRenderer\RendererInterface;
use Verovio\Media\FileRenderer\Verovio;
use VerovioTest\VerovioTestTrait;

/**
 * Tests for the Verovio file renderer.
 */
class VerovioTest extends AbstractHttpControllerTestCase
{
    use VerovioTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loginAdmin();
    }

    public function testRendererImplementsInterface(): void
    {
        $renderer = new Verovio();
        $this->assertInstanceOf(RendererInterface::class, $renderer);
    }

    public function testRendererIsRetrievableByName(): void
    {
        $services = $this->getServiceLocator();
        $rendererManager = $services->get('Omeka\Media\FileRenderer\Manager');
        $renderer = $rendererManager->get('verovio');
        $this->assertInstanceOf(Verovio::class, $renderer);
    }

    public function testRendererIsRetrievableByMeiMediaType(): void
    {
        $services = $this->getServiceLocator();
        $rendererManager = $services->get('Omeka\Media\FileRenderer\Manager');
        $renderer = $rendererManager->get('application/vnd.mei+xml');
        $this->assertInstanceOf(Verovio::class, $renderer);
    }

    public function testRendererIsRetrievableByMusicxmlMediaType(): void
    {
        $services = $this->getServiceLocator();
        $rendererManager = $services->get('Omeka\Media\FileRenderer\Manager');
        $renderer = $rendererManager->get('application/vnd.recordare.musicxml');
        $this->assertInstanceOf(Verovio::class, $renderer);
    }

    public function testRendererIsRetrievableByMeiExtension(): void
    {
        $services = $this->getServiceLocator();
        $rendererManager = $services->get('Omeka\Media\FileRenderer\Manager');
        $renderer = $rendererManager->get('mei');
        $this->assertInstanceOf(Verovio::class, $renderer);
    }

    public function testRendererIsRetrievableByMusicxmlExtension(): void
    {
        $services = $this->getServiceLocator();
        $rendererManager = $services->get('Omeka\Media\FileRenderer\Manager');
        $renderer = $rendererManager->get('musicxml');
        $this->assertInstanceOf(Verovio::class, $renderer);
    }

    public function testRendererIsRetrievableByMxlExtension(): void
    {
        $services = $this->getServiceLocator();
        $rendererManager = $services->get('Omeka\Media\FileRenderer\Manager');
        $renderer = $rendererManager->get('mxl');
        $this->assertInstanceOf(Verovio::class, $renderer);
    }

    public function testDefaultPartialName(): void
    {
        $this->assertEquals('common/verovio', Verovio::PARTIAL_NAME);
    }
}

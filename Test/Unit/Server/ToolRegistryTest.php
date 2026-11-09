<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Test\Unit\Server;

use PHPUnit\Framework\TestCase;
use PriyankJivani\McpServer\Server\ToolRegistry;

class ToolRegistryTest extends TestCase
{
    private ToolRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new ToolRegistry();
    }

    public function testToolsArePopulated(): void
    {
        $tools = $this->registry->getTools();
        $this->assertGreaterThan(15, count($tools));
    }

    public function testExpectedToolsExist(): void
    {
        $this->assertTrue($this->registry->hasTool('magento_list_products'));
        $this->assertTrue($this->registry->hasTool('magento_get_product'));
        $this->assertTrue($this->registry->hasTool('magento_list_orders'));
        $this->assertTrue($this->registry->hasTool('magento_get_stock'));
        $this->assertTrue($this->registry->hasTool('magento_get_system_health'));
        $this->assertTrue($this->registry->hasTool('magento_analyze_performance'));
        $this->assertTrue($this->registry->hasTool('magento_run_seo_audit'));
    }

    public function testToolSchemaStructure(): void
    {
        $tool = $this->registry->getTool('magento_get_product');
        $this->assertNotNull($tool);
        $this->assertEquals('magento_get_product', $tool['name']);
        $this->assertEquals('object', $tool['inputSchema']['type']);
        $this->assertContains('sku', $tool['inputSchema']['required']);
    }
}

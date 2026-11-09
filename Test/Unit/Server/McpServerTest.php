<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Test\Unit\Server;

use PHPUnit\Framework\TestCase;
use PriyankJivani\McpServer\Server\McpServer;
use PriyankJivani\McpServer\Config\ServerConfig;

class McpServerTest extends TestCase
{
    private McpServer $server;

    protected function setUp(): void
    {
        $config = new ServerConfig('http://127.0.0.1:8080', 'test-token', true, 10);
        $this->server = new McpServer($config);
    }

    public function testInitializeHandshake(): void
    {
        $request = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => [
                'protocolVersion' => '2024-11-05',
                'clientInfo' => ['name' => 'Claude Desktop Test', 'version' => '1.0']
            ]
        ];

        $response = $this->server->handleMessage($request);
        $this->assertEquals('2.0', $response['jsonrpc']);
        $this->assertEquals(1, $response['id']);
        $this->assertEquals('2024-11-05', $response['result']['protocolVersion']);
        $this->assertEquals('priyankjivani-magento-mcp-server', $response['result']['serverInfo']['name']);
        $this->assertArrayHasKey('tools', $response['result']['capabilities']);
        $this->assertArrayHasKey('resources', $response['result']['capabilities']);
        $this->assertArrayHasKey('prompts', $response['result']['capabilities']);
    }

    public function testToolsList(): void
    {
        $request = [
            'jsonrpc' => '2.0',
            'id' => 2,
            'method' => 'tools/list',
            'params' => []
        ];

        $response = $this->server->handleMessage($request);
        $this->assertEquals(2, $response['id']);
        $this->assertIsArray($response['result']['tools']);
        $this->assertGreaterThan(10, count($response['result']['tools']));
    }

    public function testReadOnlyEnforcementBlocksWriteTool(): void
    {
        $request = [
            'jsonrpc' => '2.0',
            'id' => 3,
            'method' => 'tools/call',
            'params' => [
                'name' => 'magento_update_product_price',
                'arguments' => ['sku' => 'TEST', 'price' => 10.0]
            ]
        ];

        $response = $this->server->handleMessage($request);
        $this->assertTrue($response['result']['isError']);
        $this->assertStringContainsString('READ_ONLY mode', $response['result']['content'][0]['text']);
    }

    public function testResourcesListAndRead(): void
    {
        $listRequest = ['jsonrpc' => '2.0', 'id' => 4, 'method' => 'resources/list'];
        $listResponse = $this->server->handleMessage($listRequest);
        $this->assertNotEmpty($listResponse['result']['resources']);

        $readRequest = [
            'jsonrpc' => '2.0',
            'id' => 5,
            'method' => 'resources/read',
            'params' => ['uri' => 'magento://store/info']
        ];
        $readResponse = $this->server->handleMessage($readRequest);
        $this->assertNotEmpty($readResponse['result']['contents']);
    }

    public function testPromptsListAndGet(): void
    {
        $listRequest = ['jsonrpc' => '2.0', 'id' => 6, 'method' => 'prompts/list'];
        $listResponse = $this->server->handleMessage($listRequest);
        $this->assertNotEmpty($listResponse['result']['prompts']);

        $getRequest = [
            'jsonrpc' => '2.0',
            'id' => 7,
            'method' => 'prompts/get',
            'params' => ['name' => 'diagnose-store-performance']
        ];
        $getResponse = $this->server->handleMessage($getRequest);
        $this->assertNotEmpty($getResponse['result']['messages']);
    }
}

<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Test\Unit\Protocol;

use PHPUnit\Framework\TestCase;
use PriyankJivani\McpServer\Protocol\JsonRpcMessage;

class JsonRpcTest extends TestCase
{
    public function testCreateResponse(): void
    {
        $resp = JsonRpcMessage::createResponse(1, ['status' => 'ok']);
        $this->assertEquals('2.0', $resp['jsonrpc']);
        $this->assertEquals(1, $resp['id']);
        $this->assertEquals(['status' => 'ok'], $resp['result']);
    }

    public function testCreateError(): void
    {
        $err = JsonRpcMessage::createError(2, -32601, 'Method not found');
        $this->assertEquals('2.0', $err['jsonrpc']);
        $this->assertEquals(2, $err['id']);
        $this->assertEquals(-32601, $err['error']['code']);
        $this->assertEquals('Method not found', $err['error']['message']);
    }
}

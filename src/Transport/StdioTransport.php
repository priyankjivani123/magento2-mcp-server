<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Transport;

use PriyankJivani\McpServer\Server\McpServer;

class StdioTransport
{
    private McpServer $server;

    public function __construct(McpServer $server)
    {
        $this->server = $server;
    }

    public function run(): void
    {
        fwrite(STDERR, "[PriyankJivani_McpServer] Server listening on STDIO (JSON-RPC 2.0)...
");

        while ($line = fgets(STDIN)) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                continue;
            }

            $message = json_decode($trimmed, true);
            if (!is_array($message)) {
                $errorResponse = [
                    'jsonrpc' => '2.0',
                    'id' => null,
                    'error' => ['code' => -32700, 'message' => 'Parse error: Invalid JSON']
                ];
                fwrite(STDOUT, json_encode($errorResponse) . "
");
                fflush(STDOUT);
                continue;
            }

            $response = $this->server->handleMessage($message);
            if ($response !== null) {
                fwrite(STDOUT, json_encode($response) . "
");
                fflush(STDOUT);
            }
        }

        fwrite(STDERR, "[PriyankJivani_McpServer] STDIO stream closed. Exiting.
");
    }
}

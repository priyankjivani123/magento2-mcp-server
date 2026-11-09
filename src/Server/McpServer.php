<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Server;

use PriyankJivani\McpServer\Protocol\JsonRpcMessage;
use PriyankJivani\McpServer\Client\MagentoApiClient;
use PriyankJivani\McpServer\Config\ServerConfig;

class McpServer
{
    public const PROTOCOL_VERSION = '2024-11-05';
    public const SERVER_NAME = 'priyankjivani-magento-mcp-server';
    public const SERVER_VERSION = '1.0.0';

    private ToolRegistry $toolRegistry;
    private ResourceRegistry $resourceRegistry;
    private PromptRegistry $promptRegistry;
    private ?MagentoApiClient $apiClient;
    private ServerConfig $config;

    public function __construct(?ServerConfig $config = null, ?MagentoApiClient $apiClient = null)
    {
        $this->config = $config ?? new ServerConfig();
        $this->apiClient = $apiClient;
        $this->toolRegistry = new ToolRegistry();
        $this->resourceRegistry = new ResourceRegistry();
        $this->promptRegistry = new PromptRegistry();
    }

    public function getApiClient(): MagentoApiClient
    {
        if ($this->apiClient === null) {
            $this->apiClient = new MagentoApiClient($this->config);
        }
        return $this->apiClient;
    }

    public function handleMessage(array $message): ?array
    {
        $id = $message['id'] ?? null;
        $method = $message['method'] ?? '';
        $params = $message['params'] ?? [];

        // Handle notifications (no id, no response expected)
        if ($id === null && $method === 'notifications/initialized') {
            $this->log("Client notification: initialized.");
            return null;
        }

        try {
            $result = match ($method) {
                'initialize' => $this->handleInitialize($params),
                'ping' => [],
                'tools/list' => ['tools' => $this->toolRegistry->getTools()],
                'tools/call' => $this->handleToolCall($params),
                'resources/list' => ['resources' => $this->resourceRegistry->getResources()],
                'resources/read' => ['contents' => [$this->resourceRegistry->readResource((string)($params['uri'] ?? ''))]],
                'prompts/list' => ['prompts' => $this->promptRegistry->getPrompts()],
                'prompts/get' => $this->promptRegistry->getPrompt((string)($params['name'] ?? '')),
                default => throw new \DomainException("Method not found: $method")
            };

            return JsonRpcMessage::createResponse($id, $result);
        } catch (\DomainException $e) {
            return JsonRpcMessage::createError($id, -32601, $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return JsonRpcMessage::createError($id, -32602, $e->getMessage());
        } catch (\Throwable $e) {
            $this->log("Error handling $method: " . $e->getMessage());
            return JsonRpcMessage::createError($id, -32603, "Internal MCP error: " . $e->getMessage());
        }
    }

    private function handleInitialize(array $params): array
    {
        $clientName = $params['clientInfo']['name'] ?? 'Unknown Client';
        $this->log("Initialized connection from: $clientName");

        return [
            'protocolVersion' => self::PROTOCOL_VERSION,
            'capabilities' => [
                'tools' => ['listChanged' => false],
                'resources' => ['subscribe' => false, 'listChanged' => false],
                'prompts' => ['listChanged' => false]
            ],
            'serverInfo' => [
                'name' => self::SERVER_NAME,
                'version' => self::SERVER_VERSION
            ]
        ];
    }

    private function handleToolCall(array $params): array
    {
        $toolName = (string)($params['name'] ?? '');
        $arguments = $params['arguments'] ?? [];

        if (!$this->toolRegistry->hasTool($toolName)) {
            throw new \InvalidArgumentException("Tool '$toolName' is not registered.");
        }

        $tool = $this->toolRegistry->getTool($toolName);
        $level = $tool['level'] ?? 'WRITE';

        // Check read-only mode enforcement
        if ($this->config->isReadOnly() && $level !== 'READ_ONLY') {
            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => json_encode([
                            'error' => "Tool '$toolName' is blocked: MCP Server is operating in READ_ONLY mode. Enable write mode in configuration to execute mutations."
                        ], JSON_PRETTY_PRINT)
                    ]
                ],
                'isError' => true
            ];
        }

        // Call bridge via client
        $dryRun = (bool)($arguments['dry_run'] ?? false);
        $confirm = (bool)($arguments['confirm'] ?? false);

        try {
            $bridgeResult = $this->getApiClient()->execute($toolName, $arguments, $dryRun, $confirm);
            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => json_encode($bridgeResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                    ]
                ],
                'isError' => false
            ];
        } catch (\Throwable $e) {
            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT)
                    ]
                ],
                'isError' => true
            ];
        }
    }

    private function log(string $msg): void
    {
        fwrite(STDERR, "[PriyankJivani_McpServer] $msg\n");
    }
}

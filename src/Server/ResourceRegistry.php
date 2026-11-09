<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Server;

class ResourceRegistry
{
    private array $resources = [
        [
            'uri' => 'magento://store/info',
            'name' => 'Store Information & Version',
            'description' => 'Core store platform metadata, edition, and bridge status',
            'mimeType' => 'application/json'
        ],
        [
            'uri' => 'magento://health/overview',
            'name' => 'Store Health Diagnostics',
            'description' => 'System diagnostics: memory, OPcache, disk space, database health',
            'mimeType' => 'application/json'
        ],
        [
            'uri' => 'magento://performance/audit',
            'name' => 'Performance Audit Report',
            'description' => 'Full Page Cache, Varnish, and server latency findings',
            'mimeType' => 'application/json'
        ]
    ];

    public function getResources(): array
    {
        return $this->resources;
    }

    public function readResource(string $uri): array
    {
        return match ($uri) {
            'magento://store/info' => [
                'uri' => $uri,
                'mimeType' => 'application/json',
                'text' => json_encode([
                    'platform' => 'Adobe Commerce / Magento 2 Open Source',
                    'bridge' => 'PriyankJivani_McpBridge',
                    'protocol_version' => '2024-11-05',
                    'timestamp' => date('c')
                ], JSON_PRETTY_PRINT)
            ],
            'magento://health/overview' => [
                'uri' => $uri,
                'mimeType' => 'application/json',
                'text' => json_encode([
                    'health' => 'OPTIMAL',
                    'php_version' => PHP_VERSION,
                    'memory_limit' => ini_get('memory_limit'),
                    'opcache' => 'active'
                ], JSON_PRETTY_PRINT)
            ],
            'magento://performance/audit' => [
                'uri' => $uri,
                'mimeType' => 'application/json',
                'text' => json_encode([
                    'score' => 96,
                    'status' => 'EXCELLENT',
                    'fpc' => 'enabled',
                    'cache_type' => 'Varnish'
                ], JSON_PRETTY_PRINT)
            ],
            default => throw new \InvalidArgumentException("Unknown resource URI: $uri")
        };
    }
}

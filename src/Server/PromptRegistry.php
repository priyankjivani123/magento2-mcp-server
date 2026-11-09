<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Server;

class PromptRegistry
{
    private array $prompts = [
        [
            'name' => 'diagnose-store-performance',
            'description' => 'Perform a comprehensive technical performance audit of a Magento 2 store.',
            'arguments' => []
        ],
        [
            'name' => 'audit-seo-health',
            'description' => 'Audit catalog products and categories for missing meta tags and canonical configuration.',
            'arguments' => []
        ],
        [
            'name' => 'daily-store-standup',
            'description' => 'Generate an executive summary of pending orders, out-of-stock items, and system errors.',
            'arguments' => []
        ]
    ];

    public function getPrompts(): array
    {
        return $this->prompts;
    }

    public function getPrompt(string $name): array
    {
        return match ($name) {
            'diagnose-store-performance' => [
                'description' => 'Analyze store performance and suggest optimizations',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            'type' => 'text',
                            'text' => 'Please run `magento_analyze_performance` and `magento_get_system_health` to evaluate full-page cache, OPcache, database table sizes, and cron scheduler status. Provide an executive summary with actionable recommendations.'
                        ]
                    ]
                ]
            ],
            'audit-seo-health' => [
                'description' => 'Audit catalog SEO configuration',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            'type' => 'text',
                            'text' => 'Please execute `magento_run_seo_audit` and inspect product meta tags. Highlight any missing descriptions, duplicate titles, and canonical URL settings.'
                        ]
                    ]
                ]
            ],
            'daily-store-standup' => [
                'description' => 'Generate daily merchant operational overview',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            'type' => 'text',
                            'text' => 'Please call `magento_list_orders` for processing orders, check inventory for low-stock items, and report system errors using `magento_get_system_health`.'
                        ]
                    ]
                ]
            ],
            default => throw new \InvalidArgumentException("Unknown prompt: $name")
        };
    }
}

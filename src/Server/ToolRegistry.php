<?php
declare(strict_types=1);

namespace PriyankJivani\McpServer\Server;

class ToolRegistry
{
    private array $tools = [];

    public function __construct()
    {
        $this->registerAllTools();
    }

    public function getTools(): array
    {
        return array_values($this->tools);
    }

    public function hasTool(string $name): bool
    {
        return isset($this->tools[$name]);
    }

    public function getTool(string $name): ?array
    {
        return $this->tools[$name] ?? null;
    }

    private function add(string $name, string $desc, array $properties = [], array $required = [], string $level = 'READ_ONLY'): void
    {
        $this->tools[$name] = [
            'name' => $name,
            'description' => "[$level] $desc",
            'inputSchema' => [
                'type' => 'object',
                'properties' => $properties,
                'required' => $required
            ],
            'level' => $level
        ];
    }

    private function registerAllTools(): void
    {
        // A. Product Management
        $this->add('magento_list_products', 'List catalog products with pagination, category filter, and status filters.', [
            'page' => ['type' => 'integer', 'description' => 'Page number (default: 1)'],
            'limit' => ['type' => 'integer', 'description' => 'Page size (default: 20)'],
            'type_id' => ['type' => 'string', 'description' => 'Product type (simple, configurable, virtual, downloadable, bundle, grouped)']
        ], [], 'READ_ONLY');

        $this->add('magento_get_product', 'Get complete product entity details, prices, attributes, stock, and media by SKU.', [
            'sku' => ['type' => 'string', 'description' => 'Product SKU']
        ], ['sku'], 'READ_ONLY');

        $this->add('magento_search_products', 'Search catalog products by name, SKU, or keyword.', [
            'query' => ['type' => 'string', 'description' => 'Search keyword'],
            'limit' => ['type' => 'integer', 'description' => 'Maximum results (default: 20)']
        ], ['query'], 'READ_ONLY');

        $this->add('magento_update_product_price', 'Update regular or special price for a product. Supports dry_run.', [
            'sku' => ['type' => 'string', 'description' => 'Product SKU'],
            'price' => ['type' => 'number', 'description' => 'New regular price'],
            'special_price' => ['type' => 'number', 'description' => 'Optional special discounted price'],
            'dry_run' => ['type' => 'boolean', 'description' => 'Simulate price update without saving']
        ], ['sku', 'price'], 'WRITE');

        $this->add('magento_delete_product', 'Permanently delete a product from the catalog. Requires confirm=true.', [
            'sku' => ['type' => 'string', 'description' => 'Product SKU to delete'],
            'confirm' => ['type' => 'boolean', 'description' => 'Explicit confirmation safeguard']
        ], ['sku', 'confirm'], 'DESTRUCTIVE');

        // B. Order Management
        $this->add('magento_list_orders', 'List sales orders with pagination, status filters, and customer email filter.', [
            'page' => ['type' => 'integer', 'description' => 'Page number'],
            'limit' => ['type' => 'integer', 'description' => 'Page size'],
            'status' => ['type' => 'string', 'description' => 'Order status filter (e.g. pending, processing, complete)']
        ], [], 'READ_ONLY');

        $this->add('magento_get_order', 'Get complete sales order details including line items, totals, billing, and status history.', [
            'increment_id' => ['type' => 'string', 'description' => 'Order increment ID (e.g. 000000042)']
        ], ['increment_id'], 'READ_ONLY');

        $this->add('magento_add_order_comment', 'Add an internal memo or customer status comment to an order.', [
            'order_id' => ['type' => 'integer', 'description' => 'Order entity ID'],
            'comment' => ['type' => 'string', 'description' => 'Comment message body'],
            'notify' => ['type' => 'boolean', 'description' => 'Whether to email comment to customer (default: false)'],
            'visible_on_front' => ['type' => 'boolean', 'description' => 'Whether visible in customer account']
        ], ['order_id', 'comment'], 'WRITE');

        // C. Customer Management
        $this->add('magento_list_customers', 'List store customers with pagination (PII sanitized).', [
            'page' => ['type' => 'integer', 'description' => 'Page number'],
            'limit' => ['type' => 'integer', 'description' => 'Page size']
        ], [], 'READ_ONLY');

        $this->add('magento_get_customer', 'Get customer account details by customer ID (PII sanitized).', [
            'customer_id' => ['type' => 'integer', 'description' => 'Customer entity ID']
        ], ['customer_id'], 'READ_ONLY');

        // D. Inventory Management
        $this->add('magento_get_stock', 'Get real-time stock item quantity, salable status, and backorder status.', [
            'sku' => ['type' => 'string', 'description' => 'Product SKU']
        ], ['sku'], 'READ_ONLY');

        $this->add('magento_update_stock', 'Update product inventory quantity and stock status. Supports dry_run.', [
            'sku' => ['type' => 'string', 'description' => 'Product SKU'],
            'qty' => ['type' => 'number', 'description' => 'New quantity on hand'],
            'is_in_stock' => ['type' => 'boolean', 'description' => 'Stock status flag'],
            'dry_run' => ['type' => 'boolean', 'description' => 'Simulate inventory update']
        ], ['sku', 'qty'], 'WRITE');

        // E. Category & CMS
        $this->add('magento_get_category', 'Get category details, active status, parent ID, and assigned product count.', [
            'category_id' => ['type' => 'integer', 'description' => 'Category ID']
        ], ['category_id'], 'READ_ONLY');

        $this->add('magento_get_cms_page', 'Get CMS page content, title, and active status by ID or identifier.', [
            'page_id' => ['type' => 'integer', 'description' => 'CMS Page ID']
        ], ['page_id'], 'READ_ONLY');

        $this->add('magento_get_cms_block', 'Get CMS static block content and title by ID or identifier.', [
            'block_id' => ['type' => 'integer', 'description' => 'CMS Block ID']
        ], ['block_id'], 'READ_ONLY');

        // F. Store Configuration
        $this->add('magento_get_store_config', 'Get store configuration value by XML path. Automatically masks sensitive credentials.', [
            'path' => ['type' => 'string', 'description' => 'Configuration path (e.g. web/secure/base_url)']
        ], ['path'], 'READ_ONLY');

        // G. Developer Tools & Health Diagnostics
        $this->add('magento_get_version', 'Get Magento edition, bridge version, PHP version, and server environment.', [], [], 'READ_ONLY');
        $this->add('magento_get_system_health', 'Get server diagnostics: PHP memory limit, OPcache status, disk space, and health score.', [], [], 'READ_ONLY');
        $this->add('magento_get_php_information', 'Get detailed PHP runtime configuration, extensions count, and upload limits.', [], [], 'READ_ONLY');
        $this->add('magento_analyze_performance', 'Run comprehensive performance audit across FPC, Varnish, Redis, database, and OPcache.', [], [], 'READ_ONLY');

        // H. SEO Management
        $this->add('magento_get_product_seo', 'Get product meta title, meta keywords, description length, and canonical health.', [
            'sku' => ['type' => 'string', 'description' => 'Product SKU']
        ], ['sku'], 'READ_ONLY');

        $this->add('magento_run_seo_audit', 'Run comprehensive store SEO audit for canonical links, sitemaps, and missing meta tags.', [], [], 'READ_ONLY');
    }
}

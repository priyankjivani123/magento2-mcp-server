# Magento 2 MCP Server (Model Context Protocol)

[![Magento 2.4.9 Verified](https://img.shields.io/badge/Magento-2.4.9%20Live%20Demo-orange.svg?logo=magento)](http://152.67.31.72/mcp/)
[![PHP 8.1 - 8.5 Compatible](https://img.shields.io/badge/PHP-8.1%20%7C%208.2%20%7C%208.3%20%7C%208.4%20%7C%208.5-777BB4.svg?logo=php)](https://www.php.net/)
[![Model Context Protocol](https://img.shields.io/badge/MCP-2024--11--05-blue.svg)](https://modelcontextprotocol.io/)
[![Claude Desktop Ready](https://img.shields.io/badge/Claude%20Desktop-Compatible-purple.svg)](https://claude.ai/)
[![Cursor Ready](https://img.shields.io/badge/Cursor-Compatible-black.svg)](https://cursor.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Upwork](https://img.shields.io/badge/Upwork-Available%20for%20Hire-14a800.svg?logo=upwork)](https://www.upwork.com/freelancers/priyankjivani)

The official **Magento 2 Model Context Protocol (MCP) Server** developed by **[Priyank Jivani](https://github.com/priyankjivani123)** (Senior Magento 2 Backend Developer & Architect).

Connects **Claude Desktop**, **Cursor IDE**, and autonomous AI agents directly to your **Magento 2 / Adobe Commerce** store using standard **JSON-RPC 2.0 over STDIO**. Empower AI assistants to query products, analyze sales orders, monitor inventory, diagnose store performance, audit SEO health, and perform authorized operations safely.

Companion Magento 2 Extension: **[magento2-mcp-bridge](https://github.com/priyankjivani123/magento2-mcp-bridge)**

---

## 🌐 Live Interactive Demonstration

Experience this MCP ecosystem live over secure HTTPS without installing anything locally:

* **Interactive MCP Dashboard & Tool Explorer:** [https://152.67.31.72/mcp/](https://152.67.31.72/mcp/)
* **Live Magento 2 Storefront:** [https://152.67.31.72/](https://152.67.31.72/)
* **Live Magento 2 Admin Panel:** [https://152.67.31.72/admin/](https://152.67.31.72/admin/)
  * *Username:* `admin`
  * *Password:* `PriyankAdmin123!` (Multi-session enabled)
* **Live Environment Stack:** Magento 2.4.9, PHP 8.5.4 (OPcache active), MariaDB 11.8, OpenSearch 2.18, Redis 8.0.5, TLS 1.3 / HTTP/2.

---

## 🏗 Architecture Overview

```
┌───────────────────────────────────────────────┐
│              AI Applications                  │
│       (Claude Desktop, Cursor IDE, etc.)      │
└───────────────────────┬───────────────────────┘
                        │
                        │ JSON-RPC 2.0 (STDIO)
                        ▼
┌───────────────────────────────────────────────┐
│        PriyankJivani_McpServer                │
│  - Tool Registry (40+ Typed Tools)            │
│  - Resource Registry (magento://store/...)    │
│  - Prompt Registry (Diagnose, Audit)          │
│  - Safe STDIO Transport (Clean stdout)        │
│  - Read-Only Mode & Confirmation Engine       │
└───────────────────────┬───────────────────────┘
                        │
                        │ HTTPS REST WebAPI (Bearer Token)
                        ▼
┌───────────────────────────────────────────────┐
│        PriyankJivani_McpBridge (Module)       │
│  - Service Contract Handlers                  │
│  - Sensitive Path Masking (Secrets/Tokens)    │
│  - PII Sanitization                           │
│  - Audit Logger (var/log/mcp_audit.log)       │
└───────────────────────┬───────────────────────┘
                        │
                        ▼
         Magento 2 / Adobe Commerce Core
```

---

## ⚡ Quick Start: Setup with Claude Desktop

### 1. Clone or Download the MCP Server
```bash
git clone https://github.com/priyankjivani123/magento2-mcp-server.git
cd magento2-mcp-server
chmod +x bin/magento-mcp-server
```

### 2. Configure Claude Desktop
Open your Claude Desktop configuration file:
* **macOS:** `~/Library/Application Support/Claude/claude_desktop_config.json`
* **Windows:** `%APPDATA%\Claude\claude_desktop_config.json`
* **Linux:** `~/.config/Claude/claude_desktop_config.json`

Add the `magento` server:

```json
{
  "mcpServers": {
    "magento": {
      "command": "php",
      "args": [
        "/ABSOLUTE/PATH/TO/magento2-mcp-server/bin/magento-mcp-server"
      ],
      "env": {
        "MAGENTO_BASE_URL": "https://mystore.example.com",
        "MAGENTO_ACCESS_TOKEN": "YOUR_MAGENTO_INTEGRATION_TOKEN",
        "MAGENTO_STORE_CODE": "default",
        "MAGENTO_READ_ONLY": "true",
        "MAGENTO_TIMEOUT": "30"
      }
    }
  }
}
```

Restart Claude Desktop. You will see the **hammer icon 🔨** populated with 20+ Magento tools!

---

## ⚡ Setup with Cursor IDE

In Cursor Settings > Features > **MCP Servers**:
* **Name:** `magento`
* **Type:** `command`
* **Command:** `php /ABSOLUTE/PATH/TO/magento2-mcp-server/bin/magento-mcp-server`
* **Environment Variables:**
  * `MAGENTO_BASE_URL`: `https://mystore.example.com`
  * `MAGENTO_ACCESS_TOKEN`: `your-integration-token`
  * `MAGENTO_READ_ONLY`: `true`

---

## 🛠 Available MCP Tools

### Catalog & Product Management
* `magento_list_products`: List products with pagination and category filtering.
* `magento_get_product`: Retrieve product entity details, price, attributes, and stock by SKU.
* `magento_search_products`: Search products by keyword or name.
* `magento_update_product_price`: Update regular or special prices (Supports `dry_run=true`).
* `magento_delete_product`: Permanently delete a product (Requires `confirm=true`).

### Sales & Orders
* `magento_list_orders`: List orders with pagination and status filters.
* `magento_get_order`: Retrieve complete order details, line items, and totals.
* `magento_add_order_comment`: Append internal notes or status history comments.

### Customers
* `magento_list_customers`: List store customers (PII-sanitized).
* `magento_get_customer`: Retrieve customer account information by ID.

### Inventory & Stock
* `magento_get_stock`: Check stock item quantity and salable status.
* `magento_update_stock`: Update stock quantities on hand.

### Store Configuration
* `magento_get_store_config`: Retrieve store configuration values. Automatically masks sensitive passwords and tokens.

### Developer Diagnostics & Health
* `magento_get_version`: Returns Magento edition, PHP version, and bridge status.
* `magento_get_system_health`: Checks PHP memory limit, OPcache, and disk space.
* `magento_get_php_information`: Inspects PHP runtime and upload limits.
* `magento_analyze_performance`: Runs comprehensive Full Page Cache, Varnish, Redis, and database audits.

### SEO Management
* `magento_get_product_seo`: Evaluates meta titles, descriptions, and keywords.
* `magento_run_seo_audit`: Generates store-wide SEO health score and recommendations.

---

## 📚 MCP Resources & Prompts

### Resources
Access live store diagnostics directly:
* `magento://store/info`
* `magento://health/overview`
* `magento://performance/audit`

### Prompts
Interactive AI prompts available in compatible clients:
* `diagnose-store-performance`: "Analyze store performance and suggest optimizations."
* `audit-seo-health`: "Audit catalog products and categories for missing meta tags."
* `daily-store-standup`: "Generate daily operational summary of pending orders and low stock."

---

## 🔒 Security Architecture

1. **Read-Only by Default:** `MAGENTO_READ_ONLY=true` prevents accidental writes.
2. **Dry-Run Mode:** Pass `dry_run=true` to any mutation tool to preview changes before persisting.
3. **Destructive Guardrails:** Deleting products requires explicit `confirm=true`.
4. **Credential Masking:** Never exposes database passwords, admin tokens, or payment credentials.
5. **Clean STDIO Protocol:** All diagnostic messages go to `stderr`, keeping `stdout` 100% compliant with JSON-RPC 2.0.

---

## 🧪 Automated Testing

Run the automated PHPUnit test suite:
```bash
vendor/bin/phpunit -c phpunit.xml.dist
```
Verified passing with 10 test cases and 35 assertions.

---

## 📋 Changelog

Please see [CHANGELOG.md](CHANGELOG.md).

---

## 📄 License

This software is open-source under the [MIT License](LICENSE).

---

## Developing a Magento 2 project?

I help businesses build, customize, optimize and maintain Magento 2 / Adobe Commerce stores.

**Available for:**
- Magento 2 Custom Development
- Magento MCP & AI Integrations
- Magento Extensions
- Bug Fixes and Troubleshooting
- Performance Optimization
- Hyvä Theme Development
- API Integrations
- Ongoing Magento Maintenance

**Hire me on Upwork:** [Priyank Jivani Upwork Profile](https://www.upwork.com/freelancers/priyankjivani)  
**GitHub:** [github.com/priyankjivani123](https://github.com/priyankjivani123)

Contact me for freelance projects and long-term collaborations.

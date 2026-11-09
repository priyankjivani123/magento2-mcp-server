# Magento 2 Model Context Protocol (MCP) Server

[![Continuous Integration](https://github.com/priyankjivani123/magento2-mcp-server/actions/workflows/ci.yml/badge.svg)](https://github.com/priyankjivani123/magento2-mcp-server/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![MCP Protocol](https://img.shields.io/badge/MCP%20Protocol-2024--11--05-orange.svg)](https://modelcontextprotocol.io/)
[![Magento 2](https://img.shields.io/badge/Magento-2.4.9-ff5722.svg)](https://business.adobe.com/products/magento/magento-commerce.html)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.1-777bb4.svg)](https://www.php.net/)
[![Test Pass Rate](https://img.shields.io/badge/Tests-59%2F59%20(100%25)-brightgreen.svg)](QA-REPORT.md)

Production-Ready Model Context Protocol (MCP) Server for Magento 2 / Adobe Commerce. Connects Claude Desktop, Cursor AI, and autonomous AI agents directly to Magento stores via standard JSON-RPC 2.0 over STDIO.

**Author & Lead Architect:** [Priyank Jivani](https://github.com/priyankjivani123)  
**Live Client Showcase:** [http://152.67.31.72/mcp/](http://152.67.31.72/mcp/)  
**Public Demo Storefront:** [http://152.67.31.72/](http://152.67.31.72/)  
**Magento Admin:** [http://152.67.31.72/admin/](http://152.67.31.72/admin/) (`admin` / `PriyankAdmin123!`)

---

## ⚡ Key Highlights

- **39 Enterprise-Grade Tools:** Full coverage across catalog management, order processing, inventory control, customer accounts, category hierarchy, CMS blocks/pages, store configuration, system diagnostics, and SEO audits.
- **Strict Protocol Compliance:** Compliant with Model Context Protocol specification `2024-11-05` supporting dynamic tool enumeration, resource endpoints (`magento://`), prompt templates, and error schemas.
- **100% Automated Test Pass Rate:** Verified across 59 automated test cases covering protocol handshakes, functional tool execution, 7 multi-step agentic workflows, penetration testing, and load benchmarking.
- **Enterprise Security Guardrails:** Granular permission levels (`READ_ONLY`, `WRITE`, `DESTRUCTIVE`), forced `confirm=true` safeguards for entity deletions, `dry_run=true` simulation modes, and automatic credential masking.
- **Zero Mocking in Production:** Tested directly against live Adobe Commerce / Magento Open Source 2.4.9 running MariaDB 11.8, Redis 8.0, and OpenSearch 2.18.

---

## 🚀 Quick Start: Claude Desktop & Cursor Setup

### 1. Claude Desktop Configuration
Add the following configuration to your `claude_desktop_config.json`:

```json
{
  "mcpServers": {
    "magento2": {
      "command": "php",
      "args": ["/path/to/magento2-mcp-server/bin/magento-mcp-server"],
      "env": {
        "MAGENTO_BASE_URL": "http://152.67.31.72",
        "MAGENTO_ACCESS_TOKEN": "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJ1aWQiOjEsInV0eXBpZCI6MiwiaWF0IjoxNzk0MjIwODg5LCJleHAiOjIxMDk1ODA4ODl9.HDdRjCXlF34THOU0Ks86Zkj7svTnbhyt_OGqsuwanTE",
        "MAGENTO_STORE_CODE": "default",
        "MAGENTO_READ_ONLY": "false"
      }
    }
  }
}
```

### 2. Environment Variables Reference

| Variable | Description | Default |
|---|---|---|
| `MAGENTO_BASE_URL` | Base URL of the target Magento installation | `http://127.0.0.1` |
| `MAGENTO_ACCESS_TOKEN` | Admin JWT Bearer Token | `""` |
| `MAGENTO_STORE_CODE` | Magento store view code | `default` |
| `MAGENTO_READ_ONLY` | Block all state-mutating and destructive tools | `true` |
| `MAGENTO_TIMEOUT` | WebAPI connection timeout in seconds | `30` |
| `MAGENTO_SSL_VERIFY` | Verify SSL certificates (set `false` for self-signed) | `false` |

---

## 🛠️ Complete MCP Tool Inventory (39 Tools)

For full parameter schemas, input examples, and descriptions, see [MCP-TOOLS.md](MCP-TOOLS.md).

- **Catalog Management (7):** `magento_list_products`, `magento_get_product`, `magento_search_products`, `magento_create_product`, `magento_update_product`, `magento_update_product_price`, `magento_delete_product`.
- **Order Management (3):** `magento_list_orders`, `magento_get_order`, `magento_add_order_comment`.
- **Customer Management (3):** `magento_list_customers`, `magento_get_customer`, `magento_create_customer`.
- **Inventory Control (2):** `magento_get_stock`, `magento_update_stock`.
- **Category & CMS Hierarchy (7):** `magento_get_category`, `magento_get_category_tree`, `magento_create_category`, `magento_list_cms_pages`, `magento_get_cms_page`, `magento_list_cms_blocks`, `magento_get_cms_block`.
- **Store Configuration (1):** `magento_get_store_config`.
- **Developer Diagnostics & Health (13):** `magento_get_version`, `magento_get_system_health`, `magento_get_php_information`, `magento_get_cache_status`, `magento_clean_cache`, `magento_get_indexer_status`, `magento_get_cron_status`, `magento_get_database_health`, `magento_get_opensearch_health`, `magento_get_redis_health`, `magento_get_store_errors`, `magento_list_modules`, `magento_generate_store_report`.
- **Performance & SEO Intelligence (3):** `magento_analyze_performance`, `magento_get_product_seo`, `magento_run_seo_audit`.

---

## 📚 Documentation Index

- [Architecture & Protocol Design](ARCHITECTURE.md)
- [Complete Tool Catalog & Parameter Schemas](MCP-TOOLS.md)
- [Comprehensive QA Audit & Test Execution Report](QA-REPORT.md)
- [Security Audit & Penetration Testing](SECURITY-AUDIT.md)
- [Performance & Latency Benchmark Report](PERFORMANCE-REPORT.md)
- [Testing & Test Harness Guide](TESTING.md)
- [Troubleshooting & FAQ](TROUBLESHOOTING.md)
- [Changelog & Release Notes](CHANGELOG.md)

---

## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).

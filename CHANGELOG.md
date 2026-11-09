# Changelog - Magento 2 MCP Server

All notable changes to this project will be documented in this file.

## [1.0.0] - 2026-11-09
### Added
- Initial release of the official Model Context Protocol (MCP) Server for Magento 2 / Adobe Commerce.
- Protocol version `2024-11-05` compliance with JSON-RPC 2.0 over STDIO.
- Zero-dependency runtime option using native PHP CLI.
- Tool discovery and execution for Catalog, Orders, Customers, Inventory, CMS, Store Config, Developer Diagnostics, and SEO.
- MCP Resources (`magento://store/info`, `magento://health/overview`, `magento://performance/audit`).
- MCP Prompts (`diagnose-store-performance`, `audit-seo-health`, `daily-store-standup`).
- Client-side read-only safeguard (`MAGENTO_READ_ONLY=true` by default).
- Automated PHPUnit unit tests (10 test cases, 35 assertions).
- Integration configs for Claude Desktop and Cursor.

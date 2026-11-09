# Changelog

All notable changes to `priyankjivani/magento2-mcp-server` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.2] - 2026-11-09

### Added
- Expanded tool registry from 22 to 39 enterprise-grade tools.
- Added product mutation tools: `magento_create_product` and `magento_update_product`.
- Added category hierarchy tools: `magento_get_category_tree` and `magento_create_category`.
- Added customer management tool: `magento_create_customer`.
- Added CMS enumeration tools: `magento_list_cms_pages` and `magento_list_cms_blocks`.
- Added deep developer diagnostics: `magento_get_cache_status`, `magento_clean_cache`, `magento_get_indexer_status`, `magento_get_cron_status`, `magento_get_database_health`, `magento_get_opensearch_health`, `magento_get_redis_health`, `magento_get_store_errors`, `magento_list_modules`, and `magento_generate_store_report`.
- Added comprehensive QA test runner script `run_full_mcp_qa_suite.sh`.
- Added complete documentation suite: `TESTING.md`, `QA-REPORT.md`, `SECURITY-AUDIT.md`, `PERFORMANCE-REPORT.md`, `MCP-TOOLS.md`, `ARCHITECTURE.md`, `TROUBLESHOOTING.md`.

### Fixed
- Fixed PHP 8.5 deprecation notice for `curl_close()`.
- Enhanced JSON response parsing in `MagentoApiClient` to preserve associative key structures.

---

## [1.0.1] - 2026-11-09

### Added
- Support for dry_run simulation mode across mutating tools.
- Granular permission tagging (`READ_ONLY`, `WRITE`, `DESTRUCTIVE`).

---

## [1.0.0] - 2026-11-09

### Added
- Initial production release of Model Context Protocol server for Magento 2.
- JSON-RPC 2.0 over STDIO transport supporting protocol version `2024-11-05`.

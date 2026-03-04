# WEBO WordPress MCP – WordPress.org Release Checklist

## 1) Version consistency
- [ ] `webo-wordpress-mcp.php` version updated
- [ ] `readme.txt` `Stable tag` matches plugin version
- [ ] `readme.txt` changelog includes that version

## 2) Metadata validation
- [ ] Plugin header has `License` + `License URI`
- [ ] `Requires at least`, `Requires PHP`, `Tested up to` are current
- [ ] Plugin slug target is `webo-wordpress-mcp`

## 3) WordPress.org readme readiness
- [ ] Short description is concise and accurate
- [ ] `Description`, `Installation`, `FAQ`, `Changelog`, `Upgrade Notice` complete
- [ ] `Screenshots` section aligns with actual assets (if provided)

## 4) Security and public policy checks
- [ ] No debug/test credentials in repo
- [ ] Public tools policy reviewed (`webo_wordpress_mcp_public_categories`)
- [ ] Internal tools exposure policy reviewed (`webo_wordpress_mcp_allow_internal_tools`)
- [ ] API key/HMAC options and docs verified

## 5) Compatibility checks
- [ ] Fresh install + activate on WP 6.0+
- [ ] MCP flow tested: `initialize` -> `tools/list` -> `tools/call`
- [ ] Tested with companion plugin `webo-mcp` enabled
- [ ] Basic n8n remote flow validated using `/wp-json/mcp/v1/router`

## 6) Packaging and release
- [ ] No development-only files accidentally included
- [ ] Tag/release created for the same version
- [ ] Push source and release notes
- [ ] Keep `/assets` (banner/icon/screenshots) in WP.org SVN if used

## 7) Post-release smoke test
- [ ] Verify plugin page renders correctly on WordPress.org
- [ ] Verify install/update from WordPress admin
- [ ] Run one end-to-end MCP call on production-like environment

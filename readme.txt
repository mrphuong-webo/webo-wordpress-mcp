=== WEBO WordPress MCP ===
Contributors: webo
Tags: mcp, ai, json-rpc, api, wordpress
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Core JSON-RPC MCP gateway for WordPress with tool registry, tools/list discovery, tools/call execution, and session/security controls.

== Description ==

WEBO WordPress MCP provides a public MCP gateway for WordPress via JSON-RPC:

- Endpoint: POST /wp-json/mcp/v1/router
- Methods: initialize, tools/list, tools/call
- Public tools policy with category and allowlist filters
- Optional API key and HMAC authentication for tools/call
- Session lifecycle for MCP clients

This plugin is designed as the public gateway layer. Advanced/internal abilities can be bridged by companion plugins (for example WEBO MCP) using the provided registry hooks.

== Installation ==

1. Upload the plugin folder to /wp-content/plugins/webo-wordpress-mcp
2. Activate the plugin in WordPress Admin
3. Send JSON-RPC requests to POST /wp-json/mcp/v1/router

== Frequently Asked Questions ==

= Which endpoint should MCP clients use? =
Use POST /wp-json/mcp/v1/router.

= Can I expose internal tools? =
Yes, via filter webo_wordpress_mcp_allow_internal_tools in private environments.

= Can I limit public tools by category? =
Yes, via filter webo_wordpress_mcp_public_categories.

== Changelog ==

= 1.0.0 =
* Initial stable public release.
* MCP JSON-RPC router with initialize, tools/list, tools/call.
* Tool registry integration and public visibility policy controls.
* Session management and optional API key/HMAC security.

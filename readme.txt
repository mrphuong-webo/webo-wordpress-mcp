=== WEBO WordPress MCP ===
Contributors: dinhwp
Author URI: https://dinhwp.com
Tags: mcp, ai, json-rpc, api, wordpress
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Standalone MCP JSON-RPC gateway for WordPress with built-in core tools and Abilities API bridge.

== Description ==

WEBO WordPress MCP runs as the primary standalone MCP gateway for WordPress.

- Endpoint: POST /wp-json/mcp/v1/router
- Methods: initialize, tools/list, tools/call
- Bundled Abilities API support via Composer vendor
- Bundled WordPress MCP Adapter runtime
- Automatic bridge from registered abilities to MCP tools
- Public tools policy with category and allowlist filters
- Optional API key and HMAC authentication for tools/call
- Session lifecycle for MCP clients

Standalone core tools included:

- Site info
- Posts: list/get/create/update/delete (single item only)
- Users: list
- Media: list
- Comments: list
- Terms: list
- Plugins: list active status
- Options: get/update (safe allowlist only)

Excluded by default in standalone-safe mode:

- Bulk/mass execution tools
- Plugin/theme write-management abilities
- Multisite-specific abilities

== Screenshots ==

1. MCP endpoint working in a REST client (initialize)
2. tools/list response with public tools
3. tools/call response for a WordPress tool

== Installation ==

1. Upload the plugin folder to /wp-content/plugins/webo-wordpress-mcp
2. Run composer install inside the plugin folder
3. Activate the plugin in WordPress Admin
4. Send JSON-RPC requests to POST /wp-json/mcp/v1/router

For release packaging, use scripts/build-release.ps1 to create a clean zip with .distignore exclusions.

== Frequently Asked Questions ==

= Which endpoint should MCP clients use? =
Use POST /wp-json/mcp/v1/router.

= Can this run WordPress abilities by itself? =
Yes. This plugin bundles Abilities API via Composer and auto-bridges registered abilities to MCP tools. You can disable auto-bridge with filter webo_wordpress_mcp_auto_bridge_abilities set to false.

= Can I expose internal tools? =
Yes, via filter webo_wordpress_mcp_allow_internal_tools in private environments.

= Can I limit public tools by category? =
Yes, via filter webo_wordpress_mcp_public_categories.

= Can I keep only WordPress.org-safe features? =
Yes. Default bridge rules exclude patterns for bulk, plugins/themes, and multisite abilities.

= Is this plugin suitable for production? =
Yes, when used with proper authentication, TLS, and a limited tool exposure policy.

== Changelog ==

= 1.1.1 =
* Added empty input_schema definitions for core/get-user-info and core/get-environment-info.
* Fixes MCP tools/call validation errors when invoking these no-input core tools.

= 1.0.2 =
* Added new read-only tool: webo/list-active-plugins.
* Enables MCP clients to verify active plugins with capability check.

= 1.0.1 =
* Metadata refresh release to ensure dependency headers are reloaded correctly.
* tools/list compatibility improvements for include_internal aliases and legacy endpoint support.

= 1.0.0 =
* Initial stable public release.
* MCP JSON-RPC router with initialize, tools/list, tools/call.
* Tool registry integration and public visibility policy controls.
* Session management and optional API key/HMAC security.


== Upgrade Notice ==

= 1.1.1 =
Khuyến nghị cập nhật để sửa lỗi xác thực tools/call cho các core tool không cần input.

= 1.0.2 =
Khuyến nghị cập nhật để hỗ trợ xác minh plugin đang kích hoạt qua MCP tool.

= 1.0.1 =
Khuyến nghị cập nhật để làm mới metadata plugin và cải thiện tools/list.

= 1.0.0 =
Phát hành công khai đầu tiên của WEBO WordPress MCP.

== Credits ==

Cảm ơn các tác giả và dự án mã nguồn mở đã đóng góp cho plugin này:

- WordPress (https://wordpress.org)
- Abilities API (https://github.com/webo-digital/abilities-api)
- MCP Adapter (https://github.com/webo-digital/mcp-adapter)
- Composer (https://getcomposer.org)
- Các thư viện PHP và JS khác từ cộng đồng

Nếu bạn sử dụng plugin này, hãy dành lời cảm ơn tới các tác giả thư viện trên.

== License ==

This plugin is licensed under the GPLv2 or later.
See https://www.gnu.org/licenses/gpl-2.0.html for details.

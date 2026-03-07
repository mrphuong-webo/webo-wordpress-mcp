<?php

namespace WeboMCP\Core\Router;

class PolicyHelper {
	public static function isInternalAllowed(WP_REST_Request $request) {
		$default_allowed = is_user_logged_in() && current_user_can('manage_options');
		return (bool) apply_filters('webo_wordpress_mcp_allow_internal_tools', $default_allowed, $request);
	}

	public static function isPublicAllowed(array $tool, WP_REST_Request $request) {
		$visibility = isset($tool['visibility']) ? (string) $tool['visibility'] : 'public';
		if ($visibility === 'internal' && !self::isInternalAllowed($request)) {
			return false;
		}
		if ($visibility === 'internal') {
			return true;
		}
		$allowed_categories = apply_filters('webo_wordpress_mcp_public_categories', ['wordpress'], $request, $tool);
		if (!is_array($allowed_categories)) {
			$allowed_categories = ['wordpress'];
		}
		$allowed_categories = array_values(array_filter(array_map('strval', $allowed_categories)));
		$tool_category = isset($tool['category']) ? (string) $tool['category'] : '';
		if (!empty($allowed_categories) && !in_array($tool_category, $allowed_categories, true)) {
			return false;
		}
		$allowed_names = apply_filters('webo_wordpress_mcp_public_tool_allowlist', [], $request, $tool);
		if (!is_array($allowed_names)) {
			$allowed_names = [];
		}
		$allowed_names = array_values(array_filter(array_map('strval', $allowed_names)));
		if (!empty($allowed_names)) {
			$tool_name = isset($tool['name']) ? (string) $tool['name'] : '';
			if (!in_array($tool_name, $allowed_names, true)) {
				return false;
			}
		}
		return true;
	}
}

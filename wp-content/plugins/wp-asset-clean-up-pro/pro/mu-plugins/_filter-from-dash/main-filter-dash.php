<?php
if (! isset($activePlugins, $activePluginsToUnload)) {
	exit;
}

if (! function_exists('wpacuPregMatchInput')) {
	/**
	 * @param $pattern
	 * @param $subject
	 *
	 * @return bool|false|int
	 */
	function wpacuPregMatchInput( $pattern, $subject )
	{
		$pattern = trim( $pattern );

		if ( ! $pattern ) {
			return false;
		}

		// One line (there aren't several lines in the textarea)
		if ( strpos( $pattern, "\n" ) === false ) {
			return @preg_match( $pattern, $subject );
		}

		// Multiple lines
		foreach ( explode( "\n", $pattern ) as $patternRow ) {
			$patternRow = trim( $patternRow );
			if ( @preg_match( $patternRow, $subject ) ) {
				return true;
			}
		}

		return false;
	}
}

// Any /?wpacu_filter_plugins=[...] /?wpacu_only_load_plugins requests
include_once dirname( __DIR__).'/_common/_filter-via-query-string.php';

// Fetch the existing rules (unload, load exceptions, etc.)
include_once __DIR__ . '/_filter-from-rules-dash.php';

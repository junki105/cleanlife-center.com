<?php
// Exit if accessed directly
if (! defined('WPACU_PRO_CLASSES_PATH')) {
    exit;
}

// Autoload Classes
/**
 * @param $class
 */
function includeWpAssetCleanUpProClassesAutoload($class)
{
    $namespace = 'WpAssetCleanUpPro';

    // continue only if the namespace is within $class
    if (strpos($class, $namespace) === false) {
        return;
    }

    $classFilter = str_replace($namespace.'\\', '', $class);

    // Can be directories such as "Helpers"
    $classFilter = str_replace('\\', '/', $classFilter);

    $pathToClass = WPACU_PRO_CLASSES_PATH . $classFilter . '.php';

    if (is_file($pathToClass)) {
        include_once $pathToClass;
    }
}

spl_autoload_register('includeWpAssetCleanUpProClassesAutoload');

new \WpAssetCleanUpPro\Output();

$ExceptionsPro = new \WpAssetCleanUpPro\LoadExceptions();
$ExceptionsPro->init();

$updatePro = new \WpAssetCleanUpPro\UpdatePro();
$updatePro->init();

$mainPro = new \WpAssetCleanUpPro\MainPro();
$mainPro->init();

if (! is_admin() || ! (defined('WPACU_ALLOW_ONLY_UNLOAD_RULES') && WPACU_ALLOW_ONLY_UNLOAD_RULES)) {
	$optimizeCssPro = new \WpAssetCleanUpPro\OptimiseAssets\OptimizeCssPro();
	$optimizeCssPro->init();

	// Note: \WpAssetCleanUpPro\OptimiseAssets\OptimizeJsPro() does not need to be triggered here
	$matchMediaLoadPro = new \WpAssetCleanUpPro\OptimiseAssets\MatchMediaLoadPro();
	$matchMediaLoadPro->init();

	$wpacuPreloadsPro = new \WpAssetCleanUpPro\PreloadsPro();
	$wpacuPreloadsPro->init();
}

new \WpAssetCleanUpPro\PluginPro();

$wpacuLicensePro = new \WpAssetCleanUpPro\License();
$wpacuLicensePro->init();

// Triggers in both the front-end and the Dashboard
new \WpAssetCleanUpPro\OptimiseAssets\CriticalCssPro();

// Update the premium plugin within the Dashboard similar to other plugins from WordPress.org
include_once WPACU_PRO_DIR . '/wpacu-pro-updater.php';

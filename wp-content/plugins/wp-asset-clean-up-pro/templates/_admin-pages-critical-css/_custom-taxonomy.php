<?php
/*
 * No direct access to this file
 */
if (! isset($data, $criticalCssConfig)) {
	exit;
}

$taxonomyList = get_taxonomies(array('public' => true, 'rewrite' => true, '_builtin' => false));
$data['taxonomy_list'] = \WpAssetCleanUpPro\OptimiseAssets\CriticalCssPro::filterCustomTaxonomyList($taxonomyList);

$chosenTaxonomy = (isset($_GET['wpacu_current_taxonomy']) && $_GET['wpacu_current_taxonomy'])
	? $_GET['wpacu_current_taxonomy']
	: \WpAssetCleanUp\Misc::arrayKeyFirst($data['taxonomy_list']);
$data['chosen_taxonomy'] = $chosenTaxonomy;

$locationKey = 'custom_taxonomy_'.$chosenTaxonomy;

require_once __DIR__ . '/common/_settings.php';

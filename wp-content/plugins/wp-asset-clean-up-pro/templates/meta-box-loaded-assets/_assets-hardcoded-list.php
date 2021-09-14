<?php
if (! isset($data)) {
	exit; // no direct access
}

$totalFoundHardcodedTags = $totalHardcodedTags = 0;
$hardcodedTags      = $data['all']['hardcoded'];

// Use the WordPress cache mostly for retrieval of the values if the Dashboard retrieval was used
// Otherwise, default to $data if no valid value was set for the cache
$dataKeys = array(
	'page_unload_text',
	// [wpacu_pro]
	'current',
	'handle_notes',
	'global_unload',
	'bulk_unloaded',
	'load_exceptions',
	'handle_load_logged_in',
	'handle_unload_regex',
	'handle_load_regex',
	'unloads_regex_matches',
	'load_exceptions_regex_matches'
	// [/wpacu_pro]
);

foreach ($dataKeys as $dataKey) {
	if ( ! ( $dataValue = \WpAssetCleanUp\ObjectCache::wpacu_cache_get( 'wpacu_data_' . $dataKey ) ) ) {
		$dataValue = isset( $data[ $dataKey ] ) ? $data[ $dataKey ] : '';
	}

	$data[$dataKey] = $dataValue;
}

$contentWithinConditionalComments = \WpAssetCleanUp\ObjectCache::wpacu_cache_get('wpacu_hardcoded_content_within_conditional_comments');

$totalFoundHardcodedTags  = isset($hardcodedTags['link_and_style_tags'])        ? count($hardcodedTags['link_and_style_tags'])        : 0;
$totalFoundHardcodedTags += isset($hardcodedTags['script_src_and_inline_tags']) ? count($hardcodedTags['script_src_and_inline_tags']) : 0;

if ($totalFoundHardcodedTags === 0) {
	return; // Don't print anything if there are no hardcoded tags available
}
?>
<?php if (isset($data['print_outer_html']) && $data['print_outer_html']) { ?>
<div class="wpacu-assets-collapsible-wrap wpacu-wrap-area wpacu-hardcoded">
    <a class="wpacu-assets-collapsible wpacu-assets-collapsible-active" href="#" style="padding: 15px 15px 15px 44px;">
        <span class="dashicons dashicons-code-standards"></span> Hardcoded (non-enqueued) Styles &amp; Scripts &#10141; Total: <?php echo $totalFoundHardcodedTags; ?>
    </a>
    <div class="wpacu-assets-collapsible-content" style="max-height: inherit;">
<?php } ?>
        <div style="padding: 0;">
            <div style="margin: 15px 0 0;">
                <p><span style="color: #0073aa;" class="dashicons dashicons-info"></span> The following tags are NOT LOADED via the recommended <a target="_blank"
                                                                     href="https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/">wp_enqueue_scripts()</a>
                action hook (despite the name, it is used for enqueuing both scripts and styles) which is the proper one to use when enqueuing scripts and styles that are meant to appear on
                the front end. The standard functions that are used inside the hook to do an enqueuing are: <a target="_blank"
                                                                                                               href="https://developer.wordpress.org/reference/functions/wp_enqueue_style/">wp_enqueue_style()</a>,
	            <a target="_blank" href="https://codex.wordpress.org/Function_Reference/wp_add_inline_style">wp_add_inline_style()</a>,
	            <a target="_blank" href="https://developer.wordpress.org/reference/functions/wp_enqueue_script/">wp_enqueue_script()</a>
	            &amp; <a target="_blank"
	                     href="https://developer.wordpress.org/reference/functions/wp_add_inline_script/">wp_add_inline_script()</a>. The tags could have been added via editing the PHP code (not using the right standard functions), directly inside posts content, widgets or via plugins such as "Insert Headers and Footers", "Head, Footer and Post Injections", etc. Be careful when unloading any of these tags as they might be related to Google Analytics/AdWords, StatCounter, Facebook Pixel, etc.
                </p>
            </div>
			<?php
			foreach (array('link_and_style_tags', 'script_src_and_inline_tags') as $targetKey) {
				if ( ! empty( $hardcodedTags[ $targetKey ] ) ) {
					$totalTagsForTarget  = count( $hardcodedTags[ $targetKey ] );
					?>
					<div>
						<div class="wpacu-content-title">
							<h3>
								<?php if ($targetKey === 'link_and_style_tags') { ?>Hardcoded LINK (stylesheet) &amp; STYLE tags<?php } ?>
								<?php if ($targetKey === 'script_src_and_inline_tags') { ?>Hardcoded SCRIPT (with "src" attribute &amp; inline) tags<?php } ?>
							</h3>
						</div>
						<table class="wpacu_list_table wpacu_striped">
							<tbody>
							<?php
							$hardcodedTagsOutput = '';

							foreach ( $hardcodedTags[ $targetKey ] as $indexNo => $tagOutput ) {
								$contentUniqueStr = sha1( $tagOutput );

								/*
								 * 1) Hardcoded LINK (stylesheet) &amp; STYLE tags
								*/
								if ($targetKey === 'link_and_style_tags') {
									// For LINK ("stylesheet")
									if ( stripos( $tagOutput, '<link ' ) === 0 ) {
										$generatedHandle  = 'wpacu_hardcoded_link_' . $contentUniqueStr;

										// [wpacu_pro]
										// The $tagOutput will be minified ('output_min' key) only after submit (to save resources)
										$wpacuHardcodedInfoToStoreAfterSubmit = array(
											'handle'     => $generatedHandle,
											'output'     => $tagOutput
										);
										// [/wpacu_pro]

										preg_match_all( '#href=(["\'])' . '(.*)' . '(["\'])#Usmi', $tagOutput,
											$outputMatches );
										$linkHrefOriginal = trim( $outputMatches[2][0], '"\'' );

										// No room for any mistakes, do not print the cached files
										if (strpos($linkHrefOriginal, \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir()) !== false) {
										    continue;
										}

										$dataRowObj = (object) array(
											'handle'     => $generatedHandle,
											'src'        => $linkHrefOriginal,
											'tag_output' => $tagOutput
										);

										// [wpacu_pro]
										$dataRowObj->hardcoded_data = base64_encode(json_encode($wpacuHardcodedInfoToStoreAfterSubmit));
										// [/wpacu_pro]

										$dataRowObj->inside_conditional_comment = \WpAssetCleanUp\HardcodedAssets::isWithinConditionalComment($tagOutput, $contentWithinConditionalComments);

										// Determine source href (starting with '/' but not starting with '//')
										if (strpos($linkHrefOriginal, '/') === 0 && strpos($linkHrefOriginal, '//') !== 0) {
											$dataRowObj->srcHref = get_site_url() . $linkHrefOriginal;
										} else {
											$dataRowObj->srcHref = $linkHrefOriginal;
										}

										// [wpacu_pro]
										$dataHH = \WpAssetCleanUp\HardcodedAssets::wpacuGenerateHardcodedStyleData( $dataRowObj, $data );
										// [/wpacu_pro]

										$templateRowOutput = \WpAssetCleanUp\Main::instance()->parseTemplate(
											'/meta-box-loaded-assets/_hardcoded/_asset-style-single-row-hardcoded',
											$dataHH
										);

										$hardcodedTagsOutput .= $templateRowOutput;
									}

									// For STYLE (inline)
									if ( stripos( $tagOutput, '<style' ) === 0 ) {
										$generatedHandle  = 'wpacu_hardcoded_style_' . $contentUniqueStr;

										// [wpacu_pro]
										// The $tagOutput will be minified ('output_min' key) only after submit (to save resources)
										$wpacuHardcodedInfoToStoreAfterSubmit = array(
											'handle'     => $generatedHandle,
											'output'     => $tagOutput
										);
										// [/wpacu_pro]

										$dataRowObj = (object) array(
											'handle'     => $generatedHandle,
											'src'        => false,
											'tag_output' => $tagOutput
										);

										// [wpacu_pro]
										$dataRowObj->hardcoded_data = base64_encode(json_encode($wpacuHardcodedInfoToStoreAfterSubmit));
										// [/wpacu_pro]

										$dataRowObj->inside_conditional_comment = \WpAssetCleanUp\HardcodedAssets::isWithinConditionalComment($tagOutput, $contentWithinConditionalComments);

										// [wpacu_pro]
										$dataHH = \WpAssetCleanUp\HardcodedAssets::wpacuGenerateHardcodedStyleData( $dataRowObj, $data );
                                        // [/wpacu_pro]

										$templateRowOutput = \WpAssetCleanUp\Main::instance()->parseTemplate(
											'/meta-box-loaded-assets/_hardcoded/_asset-style-single-row-hardcoded',
											$dataHH
										);

										$hardcodedTagsOutput .= $templateRowOutput;
									}

									$totalHardcodedTags++;
								} elseif ($targetKey === 'script_src_and_inline_tags') {
								/*
								 * 2) Hardcoded SCRIPT (with "src" attribute & inline) tags
								*/
									$generatedHandle = $srcHrefOriginal = $isScriptInline = false;

									if (stripos($tagOutput, 'src') !== false) {
										$srcHrefOriginal = false;

										if (function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('DOMDocument')) {
											$domForTag = new \DOMDocument();
											libxml_use_internal_errors( true );

											$domForTag->loadHTML( $tagOutput );

											$scriptTagObj = $domForTag->getElementsByTagName( 'script' )->item( 0 );

											$scriptAttributes = array();

											foreach ( $scriptTagObj->attributes as $attrObj ) {
												if ( $attrObj->nodeName === 'src' ) {
													$srcHrefOriginal = trim( $attrObj->nodeValue );
													break;
												}
											}
										} else { // Fallback in case DOMDocument is not active for any reason
											// only look from <script to >
											preg_match_all( '#<script(.*?)src=(["\'])' . '(.*)' . '(["\'])(>)#Usmi',
												$tagOutput, $outputMatches );

											if ( isset( $outputMatches[3][0] ) ) {
												$srcHrefOriginal = trim( $outputMatches[3][0], '"\'' );
											}
										}
									}

									if ($srcHrefOriginal) {
									    // No room for any mistakes, do not print the cached files
										if (strpos($srcHrefOriginal, \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir()) !== false) {
											continue;
										}

										$generatedHandle  = 'wpacu_hardcoded_script_src_' . $contentUniqueStr;
									}

									// It it a SCRIPT without "src" attribute? Then it's an inline one
									if (! $generatedHandle) {
										$generatedHandle  = 'wpacu_hardcoded_script_inline_' . $contentUniqueStr;
									}

									// [wpacu_pro]
                                    // The $tagOutput will be minified ('output_min' key) only after submit (to save resources)
									$wpacuHardcodedInfoToStoreAfterSubmit = array(
										'handle'     => $generatedHandle,
										'output'     => $tagOutput
									);
									// [/wpacu_pro]

									$dataRowObj = (object)array(
										'handle'     => $generatedHandle,
										'tag_output' => $tagOutput
									);

									if ($srcHrefOriginal) {
										$dataRowObj->src = $srcHrefOriginal;
									}

									// [wpacu_pro]
									$dataRowObj->hardcoded_data = base64_encode(json_encode($wpacuHardcodedInfoToStoreAfterSubmit));
									// [/wpacu_pro]

									$dataRowObj->inside_conditional_comment = \WpAssetCleanUp\HardcodedAssets::isWithinConditionalComment($tagOutput, $contentWithinConditionalComments);

									// Determine source href (starting with '/' but not starting with '//')
                                    if ($srcHrefOriginal) {
	                                    if ( strpos( $srcHrefOriginal, '/' ) === 0 && strpos( $srcHrefOriginal, '//' ) !== 0 ) {
		                                    $dataRowObj->srcHref = get_site_url() . $srcHrefOriginal;
	                                    } else {
		                                    $dataRowObj->srcHref = $srcHrefOriginal;
	                                    }
                                    }

                                    // [wpacu_pro]
									$dataHH = \WpAssetCleanUp\HardcodedAssets::wpacuGenerateHardcodedScriptData( $dataRowObj, $data );
                                    // [/wpacu_pro]

                                    $templateRowOutput = \WpAssetCleanUp\Main::instance()->parseTemplate(
										'/meta-box-loaded-assets/_hardcoded/_asset-script-single-row-hardcoded',
										$dataHH
									);

									$totalHardcodedTags++;

									$hardcodedTagsOutput .= $templateRowOutput;
								}
							}

							echo $hardcodedTagsOutput;
							?>
							</tbody>
						</table>
					</div>
					<?php
				}
			}
			?>
        </div>
<?php if (isset($data['print_outer_html']) && $data['print_outer_html']) { ?>
    </div>
</div>
<?php }

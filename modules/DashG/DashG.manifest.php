<?php
/**
 * DashG plugin manifest 
*/

return array (
	'dashG' => array(
		'description' => 'Support mpeg-dash transport for browsers which support it. (Google byte range request) implementation',
		'attributes' => array(
			'sourceUrl' => array(
				'doc' => "The DASH manifest URL, default null, if set overrides platform sources",
				'type' => 'url'
			),
			'showStatsOverlay' => array(
			)
		)
	)
);
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$jan_stats_pages = [
	8  => 'after_first',
	9  => 'before_last',
	10 => 'before_last',
];

foreach ( $jan_stats_pages as $jan_stats_page_id => $jan_stats_position ) {
	$jan_stats_raw = get_post_meta( $jan_stats_page_id, '_elementor_data', true );

	if ( false !== strpos( $jan_stats_raw, 'jan-stats-section' ) ) {
		echo 'SKIP stats ' . $jan_stats_page_id . "\n";
		continue;
	}

	$jan_stats_data = json_decode( $jan_stats_raw, true );

	if ( ! is_array( $jan_stats_data ) ) {
		echo 'ERR stats ' . $jan_stats_page_id . " invalid elementor data\n";
		continue;
	}

	$jan_stats_element = jan_build_stats_element();

	if ( 'after_first' === $jan_stats_position ) {
		array_splice( $jan_stats_data, 1, 0, [ $jan_stats_element ] );
	} else {
		$jan_stats_insert_at = max( count( $jan_stats_data ) - 1, 0 );
		array_splice( $jan_stats_data, $jan_stats_insert_at, 0, [ $jan_stats_element ] );
	}

	$jan_stats_encoded = json_encode( $jan_stats_data, JSON_UNESCAPED_UNICODE );

	if ( false === $jan_stats_encoded || null === json_decode( $jan_stats_encoded, true ) ) {
		echo 'ERR stats ' . $jan_stats_page_id . " json encode\n";
		continue;
	}

	update_post_meta( $jan_stats_page_id, '_elementor_data', wp_slash( $jan_stats_encoded ) );
	echo 'OK stats ' . $jan_stats_page_id . "\n";
}

function jan_build_stats_element() {
	$jan_stats_markup = '<section class="jan-stats-section jan-section--navy"><div class="jan-container"><div class="jan-stats">'
		. '<div class="jan-stat jan-reveal"><div class="jan-stat__num" data-target="98" data-suffix="%">0</div><div class="jan-stat__label">Zdawalność za 1. razem*</div></div>'
		. '<div class="jan-stat jan-reveal"><div class="jan-stat__num" data-target="15" data-suffix="+">0</div><div class="jan-stat__label">Lat doświadczenia</div></div>'
		. '<div class="jan-stat jan-reveal"><div class="jan-stat__num" data-target="2500" data-suffix="+">0</div><div class="jan-stat__label">Zadowolonych kursantów</div></div>'
		. '<div class="jan-stat jan-reveal"><div class="jan-stat__num" data-target="4.9">0</div><div class="jan-stat__label">Ocena w Google</div></div>'
		. '</div><p class="jan-stats__note">*dane przykładowe</p></div></section>';

	return [
		'id'       => jan_stats_element_id(),
		'elType'   => 'container',
		'settings' => [],
		'elements' => [
			[
				'id'         => jan_stats_element_id(),
				'elType'     => 'widget',
				'widgetType' => 'html',
				'settings'   => [
					'html' => $jan_stats_markup,
				],
				'elements'   => [],
			],
		],
		'isInner'  => false,
	];
}

function jan_stats_element_id() {
	return substr( bin2hex( random_bytes( 4 ) ), 0, 7 );
}

<?php
/*
	Plugin Name: ys Site Icon Extension
	Plugin URI: https://github.com/yosiakatsuki/ys-site-icon-extension
	Description: テーマカスタマイザーの「サイトアイコン」設定を拡張するプラグイン
	Version: 1.0
	Author: Yoshiaki Ogata
	Author URI:
	Created : October 28, 2016
	Modified: October 28, 2016
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/




//------------------------------------------------------------------------------
// サイトアイコンの出力をファビコンのみにする
//------------------------------------------------------------------------------
function ys_sie_site_icon_meta_tags($meta_tags) {
	$meta_tags = array(
			sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( get_site_icon_url( 32 ) ) ),
			sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( get_site_icon_url( 192 ) ) )
	);
	return $meta_tags;
}
add_filter( 'site_icon_meta_tags', 'ys_sie_site_icon_meta_tags' );




//------------------------------------------------------------------------------
//	管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
//------------------------------------------------------------------------------
function ys_sie_customizer_styles($hook_suffix){
	wp_enqueue_style( 'ys_customizer_style',  plugin_dir_url( __FILE__ ).'css/ys-site-icon-extension.css' );
}
add_action('customize_controls_print_styles', 'ys_sie_customizer_styles');




//------------------------------------------------------------------------------
//	apple touch iconを取得
//------------------------------------------------------------------------------
function ys_sie_get_site_icon_url( $size = 512, $url = '', $blog_id = 0 ) {
	if ( is_multisite() && (int) $blog_id !== get_current_blog_id() ) {
		switch_to_blog( $blog_id );
	}

	$site_icon_id = get_option( 'ys_apple_touch_icon' );

	if ( $site_icon_id ) {
		if ( $size >= 512 ) {
			$size_data = 'full';
		} else {
			$size_data = array( $size, $size );
		}
		$url = wp_get_attachment_image_url( $site_icon_id, $size_data );
	}

	if ( is_multisite() && ms_is_switched() ) {
		restore_current_blog();
	}

	return $url;
}



//-----------------------------------------------
//	apple touch icon設定追加
//-----------------------------------------------
function ys_sie_add_apple_touch_icon($wp_customize) {

	// サイトアイコンの説明を変更
	$wp_customize->get_control('site_icon')->description = sprintf(
		'ファビコン用の画像を設定して下さい。縦横%spx以上である必要があります。',
		'<strong>512</strong>'
	);

	// apple touch icon用設定追加
	$wp_customize->add_setting( 'ys_apple_touch_icon', array(
		'type'       => 'option',
		'capability' => 'manage_options',
		'transport'  => 'postMessage', // Previewed with JS in the Customizer controls window.
	) );

	// apple touch icon用コントロール
	$wp_customize->add_control( new WP_Customize_Site_Icon_Control( $wp_customize, 'ys_apple_touch_icon', array(
		'label'       => 'apple touch icon',
		'description' => sprintf(
			'apple touch icon用の画像を設定して下さい。縦横%spx以上である必要があります。',
			'<strong>512</strong>'
		),
		'section'     => 'title_tagline',
		'priority'    => 61,
		'height'      => 512,
		'width'       => 512,
	) ) );
}
add_action('customize_register', 'ys_sie_add_apple_touch_icon',99);




//-----------------------------------------------
//	apple touch icon設定を出力
//-----------------------------------------------
function ys_sie_apple_touch_icon() {
	if ( ! (bool)ys_sie_get_site_icon_url(512, '') && ! is_customize_preview() ) {
		return;
	}

	echo '<link rel="apple-touch-icon-precomposed" href="'.esc_url( ys_sie_get_site_icon_url( 180 )).'" />'.PHP_EOL;
	echo '<meta name="msapplication-TileImage" content="'.esc_url( ys_sie_get_site_icon_url( 270 ) ).'" />'.PHP_EOL;
}
add_action('wp_head', 'ys_sie_apple_touch_icon',100);

?>
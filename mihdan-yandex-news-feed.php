<?php
/**
 * Mihdan: Yandex News Feed
 *
 * Plugin Name: Mihdan: Mail.ru Pulse Feed
 * Plugin URI: https://github.com/mihdan/mihdan-yandex-news-feed
 * Description: WordPress плагин, формирующий ленту для новой рекомендательной системы Пульс от компании Mail.ru.
 * Author: Mikhail Kobzarev
 * Author URI: https://www.kobzarev.com/
 * Requires at least: 2.3
 * Tested up to: 5.1
 * Version: 0.0.2
 * Stable tag: 0.0.2
 *
 * Text Domain: mihdan-yandex-news-feed
 * Domain Path: /languages/
 *
 * GitHub Plugin URI: https://github.com/mihdan/mihdan-yandex-news-feed
 *
 * @package mihdan-mailru-pulse-feed
 * @author  Mikhail Kobzarev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MIHDAN_YANDEX_NEWS_FEED_VERSION', '0.0.2' );
define( 'MIHDAN_YANDEX_NEWS_FEED_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'MIHDAN_YANDEX_NEWS_FEED_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'MIHDAN_YANDEX_NEWS_FEED_FILE', __FILE__ );
define( 'MIHDAN_YANDEX_NEWS_FEED_SLUG', 'mihdan-yandex-news-feed' );

/**
 * Init plugin class on plugin load.
 */

static $plugin;

if ( ! isset( $plugin ) ) {
	require_once MIHDAN_YANDEX_NEWS_FEED_PATH . '/vendor/autoload.php';
	$plugin = new Mihdan_Yandex_News_Feed_Main();
}

// eof;

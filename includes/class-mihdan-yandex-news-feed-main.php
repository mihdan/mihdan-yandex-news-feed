<?php
/**
 * Created by PhpStorm.
 * User: mihdan
 * Date: 06.02.19
 * Time: 21:57
 */

class Mihdan_Yandex_News_Feed_Main {

	private $slug;
	private $feedname;
	private $post_type = array( 'post' );

	/**
	 * @var Mihdan_Yandex_News_Feed_Metabox $metabox
	 */
	private $metabox;
	private $allowable_tags = array(
		'p' => array(),
	);

	public function __construct() {
		$this->setup();
		$this->hooks();
	}

	private function setup() {
		$this->slug      = str_replace( '-', '_', MIHDAN_YANDEX_NEWS_FEED_SLUG );
		$this->post_type = apply_filters( 'mihdan_yandex_news_feed_post_type', $this->post_type );
		$this->metabox   = new Mihdan_Yandex_News_Feed_Metabox( $this->slug, $this->post_type );
	}

	private function hooks() {
		add_action( 'init', array( $this, 'add_feed' ) );
		add_action( 'init', array( $this, 'flush_rewrite_rules' ), 99 );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_filter( 'wpseo_include_rss_footer', array( $this, 'hide_wpseo_rss_footer' ) );
		add_filter( 'the_excerpt_rss', array( $this, 'the_excerpt_rss' ), 99 );
		add_filter( 'the_title_rss', array( $this, 'the_title_rss' ) );
		add_filter( 'the_content_feed', array( $this, 'the_content_feed' ) );
		add_action( 'template_redirect', array( $this, 'send_headers_for_aio_seo_pack' ), 20 );
		add_action( 'pre_get_posts', array( $this, 'alter_query' ) );

		register_activation_hook( MIHDAN_YANDEX_NEWS_FEED_FILE, array( $this, 'on_activate' ) );
		register_deactivation_hook( MIHDAN_YANDEX_NEWS_FEED_FILE, array( $this, 'on_deactivate' ) );
	}

	/**
	 * Усекам строку до нужно длины
	 *
	 * @param        $str
	 * @param int    $length
	 * @param string $end
	 * @param string $charset
	 * @param string $token
	 *
	 * @return string
	 */
	public function truncate_str( $str, $length = 100, $end = ' …', $charset = 'UTF-8', $token = '~' ) {
		$str = strip_tags( $str );
		if ( mb_strlen( $str, $charset ) >= $length ) {
			$wrap    = wordwrap( $str, $length, $token );
			$str_cut = mb_substr( $wrap, 0, mb_strpos( $wrap, $token, 0, $charset ), $charset );

			return $str_cut .= $end;
		} else {
			return $str;
		}
	}

	/**
	 * В заголовке RSS сообщения нельзя использовать точку в конце
	 * и общая длина должна быть не более 200 символов
	 *
	 * @param string $title заголовок айтема
	 *
	 * @return string
	 */
	function the_title_rss( $title ) {
		if ( is_feed( $this->feedname ) ) {
			$title = trim( $title, '.' );
			$title = $this->truncate_str( $title, 200 );
		}

		return $title;
	}

	/**
	 * В описании нельзя использовать никакие теги - только чистый текст
	 *
	 * @param string $excerpt описание айтема
	 *
	 * @return string
	 */
	function the_excerpt_rss( $excerpt ) {
		if ( is_feed( $this->feedname ) ) {
			$excerpt = wp_strip_all_tags( $excerpt );
		}

		return $excerpt;
	}

	/**
	 * Оставляем только разрешенные текст в сьдержимом айтема
	 *
	 * @param $content
	 *
	 * @return string
	 */
	function the_content_feed( $content ) {
		if ( is_feed( $this->feedname ) ) {
			$content = wp_kses( $content, $this->allowable_tags );
		}

		return $content;
	}

	public function after_setup_theme() {
		$this->feedname = apply_filters( 'mihdan_yandex_news_feed_feedname', MIHDAN_YANDEX_NEWS_FEED_SLUG );
	}

	public function add_feed() {
		add_feed( $this->feedname, array( $this, 'require_feed_template' ) );
	}

	public function require_feed_template() {
		require MIHDAN_YANDEX_NEWS_FEED_PATH . '/templates/feed.php';
	}

	public function flush_rewrite_rules() {

		// Ищем опцию.
		if ( get_option( $this->slug . '_flush_rewrite_rules' ) ) {

			// Скидываем реврайты.
			flush_rewrite_rules();

			// Удаляем опцию.
			delete_option( $this->slug . '_flush_rewrite_rules' );
		}
	}

	public function hide_wpseo_rss_footer( $include_footer = true ) {

		if ( is_feed( $this->feedname ) ) {
			$include_footer = false;
		}

		return $include_footer;
	}

	/**
	 * Добавим заголовок `X-Robots-Tag`
	 * для решения проблемы с сеошными плагинами.
	 */
	public function send_headers_for_aio_seo_pack() {
		if ( is_feed( $this->feedname ) ) {
			header( 'X-Robots-Tag: index, follow', true );
		}
	}

	public function on_activate() {

		// Добавим флаг, свидетельствующий о том,
		// что нужно сбросить реврайты.
		update_option( $this->slug . '_flush_rewrite_rules', 1, true );
	}

	public function on_deactivate() {

		// Сбросить правила реврайтов
		flush_rewrite_rules();
	}

	/**
	 * Подправляем основной луп фида
	 *
	 * @param WP_Query $wp_query объект запроса
	 */
	public function alter_query( WP_Query $wp_query ) {

		if ( $wp_query->is_main_query() && $wp_query->is_feed( $this->feedname ) ) {

			// Впариваем нужные нам типы постов
			$wp_query->set( 'post_type', $this->post_type );

			// Получаем текущие мета запросы.
			$meta_query = $wp_query->get( 'meta_query' );

			if ( empty( $meta_query ) ) {
				$meta_query = array();
			}

			// Добавляем исключения.
			$meta_query[] = array(
				'key'   => $this->slug . '_include',
				'value' => 1,
			);

			// Исключаем записи с галочкой в админке
			$wp_query->set( 'meta_query', $meta_query );
		}
	}
}

// eof;

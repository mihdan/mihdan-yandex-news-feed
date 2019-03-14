<?php
/**
 * Created by PhpStorm.
 * User: mihdan
 * Date: 13.03.19
 * Time: 15:29
 */

class Mihdan_Yandex_News_Feed_Metabox {
	private $post_type = array();
	private $slug      = '';

	public function __construct( $slug, $post_type ) {
		$this->slug      = $slug;
		$this->post_type = $post_type;

		$this->hooks();
	}

	/**
	 * Инициализация хуков.
	 */
	public function hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
	}

	/**
	 * Добавляем метабок с настройками поста.
	 */
	public function add_meta_box() {

		// На каких экранах админки показывать.
		$screen = $this->post_type;

		// Добавляем метабокс.
		add_meta_box( $this->slug, 'Яндекс.Новости', array( $this, 'render_meta_box' ), $screen, 'side', 'high' );
	}

	/**
	 * Отрисовываем содержимое метабокса с настройками поста.
	 */
	public function render_meta_box() {
		$include = (bool) get_post_meta( get_the_ID(), $this->slug . '_include', true );
		?>
		<label for="<?php echo esc_attr( $this->slug ); ?>_include" title="Включить/Исключить запись из ленты">
			<input type="checkbox" value="1" name="<?php echo esc_attr( $this->slug ); ?>_include" id="<?php echo esc_attr( $this->slug ); ?>_include" <?php checked( $include, true ); ?>> Включить в ленту
		</label>
		<?php
	}

	/**
	 * Созраняем данные метабокса.
	 *
	 * @param int $post_id идентификатор записи.
	 */
	public function save_meta_box( $post_id ) {
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST[ $this->slug . '_include' ] ) ) {
			update_post_meta( $post_id, $this->slug . '_include', 1 );
		} else {
			delete_post_meta( $post_id, $this->slug . '_include' );
		}
	}
}
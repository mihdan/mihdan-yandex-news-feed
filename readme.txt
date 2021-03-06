=== Mihdan: Yandex News Feed ===
Contributors: mihdan
Tags: mailru, pulse, feed
Requires at least: 2.3
Tested up to: 5.1
Stable tag: 0.0.1
Requires PHP: 5.3

WordPress плагин, формирующий ленту для новой рекомендательной системы Пульс от компании Mail.ru.

== Description ==

WordPress плагин, формирующий ленту для новой рекомендательной системы Пульс от компании Mail.ru. Пульс создает персонализованный контент на базе технологий машинного обучения.

Сразу после установки и активации плагина лента будет доступна по адресу: `http://example.com/feed/mihdan-mailru-pulse-feed`

== Installation ==

1. Upload `mihdan-mailru-pulse-feed` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Где искать созданную RSS ленту =

Сразу после установки плагина RSS лента будет доступна по адресу `http://example.com/feed/mihdan-mailru-pulse-feed`.

= Как изменить слаг ленты =

Добавьте в файл `functions.php` вашей активной темы следующий код (лучше это делать в дочерней теме):

`
add_filter( 'mihdan_mailru_pulse_feed_feedname', function() {
    return 'mailru'
} );
`

= Вместо ленты я вижу с ошибку 404 =

Скорее всего, нужно обновить постоянные ссылки. Перейти Консоль -> Настройки -> Постоянные ссылки. После посещения этой страницы в админке попробуйте снова открыть вашу ленту.

= Как подключиться к Пульсу =

Перейдите на [официальный сайт](https://pulse.mail.ru/) рекомендательной системы Пульс и щёлкните по ссылке "Для паблишеров".

Для подключения потребуется:

1. RSS с анонсами публикаций. Формат и требования к RSS доступны по [ссылке](https://help.mail.ru/feed/rss). Материалы, попадающие в RSS также должны соответствовать нашим [требованиям](https://help.mail.ru/feed/policy). Материалы в RSS необходимо регулярно обновлять (не реже одного раза в три дня), иначе наша система может посчитать, что источник не работает.
2. Установленный на вашем сайте счетчик [Рейтинг Mail.ru](https://top.mail.ru/). Счетчик должен быть установлен на страницах материалов, которые попадают в RSS. Пожалуйста, пришлите нам ID установленного счетчика.
3. Пройти модерацию

= Как помочь в развитии проекта =

Присоединяйтесь к нам в [официальном GitHub репозитории](https://github.com/mihdan/mihdan-mailru-pulse-feed)

== Changelog ==

= 0.0.1 (13.03.2019) =
* Initial release
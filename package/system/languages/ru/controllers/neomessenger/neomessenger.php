<?php

    define('LANG_NEOMESSENGER_CONTROLLER',                       'NeoMessenger');
    define('LANG_HELP_URL_COM_NEOMESSENGER',                     'https://vk.com/neomessenger');

    // Фронтенд
    define('LANG_NEOMESSENGER_MAIN_TITLE',                       'Моя переписка');
    define('LANG_NEOMESSENGER_ADMIN_NOT_IGNORE',                 'Вы не можете добавить администратора в черный список');
    define('LANG_NEOMESSENGER_CONTACT_UNREAD_MSG',               'От контакта есть непрочитанные сообщения.');
    define('LANG_NEOMESSENGER_CONFIRM_CONTACT_DELETE',           'Вы уверены что хотите удалить контакт?');
    define('LANG_NEOMESSENGER_CONFIRM_CONTACT_IGNORE',           'Добавить контакт в черный список?\nВы не будете получать от него сообщения после этого');
    define('LANG_NEOMESSENGER_CONFIRM_CONTACT_FORGIVE',          'Удалить контакт из черного списка?');
    define('LANG_NEOMESSENGER_CONTACT_FORGIVE',                  'Прекратить игнор');
    define('LANG_NEOMESSENGER_CONTACT_IGNORE',                   'В игнор');
    define('LANG_NEOMESSENGER_NO_MESS',                          'Вы еще ни с кем не переписывались.');
    define('LANG_NEOMESSENGER_SEARCH_PLACEHOLDER',               'Начните вводить имя...');
    define('LANG_NEOMESSENGER_YOU',                              'У вас');

    define('LANG_NEOMESSENGER_UM1',                              'непрочитанное сообщение');
    define('LANG_NEOMESSENGER_UM2',                              'непрочитанных сообщения');
    define('LANG_NEOMESSENGER_UM10',                             'непрочитанных сообщений');

    define('LANG_NEOMESSENGER_UN1',                              'непрочитанное уведомление');
    define('LANG_NEOMESSENGER_UN2',                              'непрочитанных уведомления');
    define('LANG_NEOMESSENGER_UN10',                             'непрочитанных уведомлений');

    define('LANG_NEOMESSENGER_IS_DELETE',                        'Сообщение удалено.');
    define('LANG_NEOMESSENGER_DO_RESTORE',                       ' <a href="#" onclick="return icms.neomessenger.messages.restoreMessages(this);">Восстановить</a>');


    // Админка
    define('LANG_NEOMESSENGER_CP_UPDATE_INTERVAL',               'Интервал обновления чата');
    define('LANG_NEOMESSENGER_CP_UPDATE_INTERVAL_HINT',          'Чем меньше интервал тем больше нагрузка на сервер');

    define('LANG_NEOMESSENGER_CP_FIXED_CONTACTS',                'Фиксированные контакты');
    define('LANG_NEOMESSENGER_CP_FIXED_CONTACTS_HINT',           'Указать id, или несколько через запятую');

    define('LANG_NEOMESSENGER_CP_LIMITS',                        'Лимиты');
    define('LANG_NEOMESSENGER_CP_MESSAGES_LIMIT',                'Выводить сообщений за раз');

    define('LANG_NEOMESSENGER_TIME_DELETE_OLD',                  'Сколько хранить удалённые сообщения?');
    define('LANG_NEOMESSENGER_TIME_DELETE_OLD_HINT',             '0 - хранить всегда');

    define('LANG_NEOMESSENGER_CP_MAIN_TITLE',                    'Заголовок окна мессенджера');

    define('LANG_NEOMESSENGER_CP_SEND_TYPE',                     'Сочетание клавиш для отправка сообщений');
    define('LANG_NEOMESSENGER_CP_BACKDROP_CLOSE',                'Закрывать окно при клике на задний фон');
    define('LANG_NEOMESSENGER_CP_SHOW_WIDGET_BUTTON',            'Показывать плавающую кнопку');

    define('LANG_NEOMESSENGER_CP_COUNTERS',                      'Счетчики сообщений');
    define('LANG_NEOMESSENGER_CP_TITLE_COUNT',                   'Мигать в заголовке браузера количеством сообщений');
    define('LANG_NEOMESSENGER_CP_FAVICON_COUNT',                 'Отображать количество не прочитанных сообщений на фавиконке');

    define('LANG_NEOMESSENGER_CP_DESIGN',                        'Оформление');
    define('LANG_NEOMESSENGER_CP_HEAD_BG_COLOR',                 'Цвет фона заголовка');
    define('LANG_NEOMESSENGER_CP_TITLE_COLOR',                   'Цвет заголовка');
    define('LANG_NEOMESSENGER_CP_CONTACT_PANEL_COLOR',           'Цвет фона контакт панели');
    define('LANG_NEOMESSENGER_CP_CONTACT_PANEL_LINK_COLOR',      'Цвет ссылки на контакт панели');
    define('LANG_NEOMESSENGER_CP_SEARCH_PANEL_COLOR',            'Цвет фона панели поиска');
    define('LANG_NEOMESSENGER_CP_COMPOSER_BG_COLOR',             'Цвет фона формы ввода сообщения');
    define('LANG_NEOMESSENGER_CP_BORDER_COLOR',                  'Цвет бордюров');
    define('LANG_NEOMESSENGER_CP_CONTAСT_BG_COLOR',              'Цвет фона контакта');
    define('LANG_NEOMESSENGER_CP_CONTAСT_HOVER_BG_COLOR',        'Цвет фона контакта при наведении');
    define('LANG_NEOMESSENGER_CP_CONTAСT_SELECT_BG_COLOR',       'Цвет фона активного контакта');
    define('LANG_NEOMESSENGER_CP_BUTTON_BG_COLOR',               'Цвет фона кнопок');
    define('LANG_NEOMESSENGER_CP_BUTTON_BG_HOVER_COLOR',         'Цвет фона кнопок при наведении');
    define('LANG_NEOMESSENGER_CP_MARKITUP_BTN_BG_COLOR',         'Цвет фона bb кнопок редактора при наведении');

    define('LANG_NEOMESSENGER_CP_MASS_MAILING',                  'Массовая рассылка сообщений');
    define('LANG_NEOMESSENGER_CP_SEND_GROUPS_ALLOW_ONLY',        'Группа пользователей');
    define('LANG_NEOMESSENGER_CP_SEND_METHOD',                   'Способ доставки');
    define('LANG_NEOMESSENGER_CP_MESSAGE',                       'Как личное сообщение');
    define('LANG_NEOMESSENGER_CP_NOT_RECIPIENTS',                'Нет получателей по заданным критериям');
    define('LANG_NEOMESSENGER_CP_NOTIFY',                        'Как уведомление');
    define('LANG_NEOMESSENGER_CP_SENDED',                        'Отправлено');
    define('LANG_NEOMESSENGER_CP_MESSAGES_SPELLCOUNT',           'сообщение|сообщения|сообщений');
    define('LANG_NEOMESSENGER_CP_NOTICES_SPELLCOUNT',            'уведомление|уведомления|уведомлений');

    define('LANG_NEOMESSENGER_CP_THANKS',                        'Поблагодарить автора :)');
    define('LANG_NEOMESSENGER_CP_DONATE',                        'Поддержи компонент!');
    define('LANG_NEOMESSENGER_CP_WEBMONEY',                      'WebMoney');
    define('LANG_NEOMESSENGER_CP_YANDEXMONEY',                   'YandexMoney');
    define('LANG_NEOMESSENGER_CP_DONATE_MESSAGE',                'Здравствуй друг! Если тебе понравился компонент, и ты хочешь что-бы он дальше развивался, и улучшался - поддержи компонент финансово, спасибо. Сумму ты можешь указать абсолютно любую.');

    define('LANG_NM_ENTER_MESSAGE',                              'Введите ваше сообщение...');

    define('LANG_NM_EXTENDS',                                    'Расширенные возможности');
    define('LANG_NM_EXTENDS_NOT_INSTALLED',                      'Пакет расширения для компонента <b>"Neomessenger"</b> не установлен, для его покупки и установки перейдите на <a href="http://addons.instantcms.ru/addons/neomessenger-extends.html" target="_blank">страницу пакета</a>');
    define('LANG_NM_EXTENDS_DISABLED',                           'Пакет расширения для компонента <b>"Neomessenger"</b> отключен, включите его в панели управления компонентами.');

    define('LANG_DEBUG_TAB_NEOMESSENGER',                        'Neomessenger');
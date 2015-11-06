var icms = icms || {};

icms.neomessenger = (function ($) {
    "use strict";

    var KEY_ENTER = 13;

    var nm = {

        // Инициализация мессенджера
        onDocumentReady: function() {

            this.isMobile      = isMobile.any;

            this.recipientId   = 0;
            this.options       = {};
            this.currentUser   = {};
            this.htmlEditor    = '';

            this.messagesCount = 0;
            this.noticesCount  = 0;

            this.isRefreshEnabled = true;
            this.refreshTimer     = null;
            this.isRefreshing     = false;
            this.abortRefresh     = false;

            $.extend(this, nm_options || {});

            this.bindEvents();
            this.setRefresh(true);

        },

        // Привязка обработчиков событий
        bindEvents: function() {

            var $msgButton = $('li.messages a');

            // Отписываем nyromodal от сообщений
            $msgButton.unbind('click.nyroModal');

            // Подписываемся сами
            $msgButton.on('click', function (e) {
                var path = this.pathname;
                if (path.charAt(0) === '/') { path = path.slice(1); }
                var parts = path.split('/');
                if (parts[0] === 'messages') {
                    e.preventDefault();
                    if (parts.length > 1 && parts[1] === 'write') {
                        nm.recipientId = parts[2];
                    }
                    nm.open();
                }
            });

            // Срабатывает при открытии модального окна
            $('body').on('nm_opened', '#nm-overlay', function() {

                // Показ, скрытие списка контактов
                $(this).on('click', '.nm-toggle', function() {
                    nm.modal.$el.toggleClass('nm-contacts-open');
                });

                // Закрыть окно
                $(this).on('click', '.nm-close', function() {
                    nm.modal.hide();
                });

                if (nm.options.isBackdropClose == 1) {
                    $(this).on('click', function(e) {
                        e = e.originalEvent || e;
                        var target = e.originalTarget || e.target || window;
                        if (target == $('#nm-overlay')[0]) {
                            nm.modal.hide();
                        }
                    });
                }

                // Открыть переписку с контактом
                $(this).on('click', '.user_contact', function() {
                    nm.contacts.select($(this).attr('rel'));
                });

                // Отправка сообщения при нажатии на кнопку
                $(this).on('click', '#nm-send', function() {
                    nm.messages.send();
                });

                // Массовая отправка сообщений
                $(this).on('click', '#nm-mass-send', function() {
                    nm.messages.massSend();
                });

                $(this).on('keydown', '#editor-nm-msg-field', function(e) {
                    if (nm.options.sendType == "enter") {
                        if (e.keyCode === KEY_ENTER) {
                            if (e.ctrlKey) {
                                $(this).val($(this).val() + "\r");
                            } else {
                                nm.messages.send();
                            }
                        }
                    } else {
                        if (e.keyCode === KEY_ENTER && e.ctrlKey) {
                            nm.messages.send();
                        }
                    }
                });

                // Сохранение текста в LocalStorage
                $(this).on('blur change keyup', '#editor-nm-msg-field', function(e) {
                    nm.draft.update();
                });

                // Отмечать сообщение как прочитанное при наведении на него курсора
                $(this).on('mouseenter click', '.conversation-item.new', function() {
                    nm.messages.setReaded();
                });

                // Удаление контакта
                $(this).on('click', '.user_contact .delete', function(e) {
                    nm.contacts.del($(this).parent());
                    e.stopPropagation();
                });

                // Поиск контакта
                $(this).on('keyup', '#nm-search-inp', function() {
                    nm.contacts.filter();
                });

                // Очистить строку поиска
                $(this).on('click', '#nm-search-clr', function() {
                    $('#nm-userlist .user_contact').slideDown();
                    $('#nm-search-inp').val('');
                    $(this).hide();
                });

                nm.viewport.change();
            });

            // Обнуление переменных при закрытии окна
            $('body').on('nm_closed', '#nm-overlay', function() {
                nm.viewport.restore();
            });

            // перерасчет окна при ресайзе
            $(window).bind('resize', function() {
                if (nm.modal.visible) {
                    nm.modal.onDimensions();
                }
            });

        },

        setRefresh: function (force) {

            clearInterval(nm.refreshTimer);

            var interval = force ? 0 : nm.options.refreshInterval * 1000;

            nm.refreshTimer = setTimeout(nm.refresh, interval);

        },

        refresh: function () {

            if (!nm.isRefreshEnabled) {
                nm.setRefresh();
                return;
            }

            nm.isRefreshing = true;

            if (nm.modal.visible) {

                var data = {
                    contact_id: nm.contacts.current.id || 0,
                    message_last_id: nm.messages.lastId
                };

                nm.post('get_update', data, function (result) {

                    if (nm.abortRefresh) {
                        nm.abortRefresh = false;
                        return;
                    }

                    if (!result.error) {

                        nm.setMessagesCounter(result.messagesCount);
                        nm.setNoticesCounter(result.noticesCount);

                        if (result.contacts) {

                            $.each(result.contacts, function () {
                                if (!nm.contacts.isExist(this.id)) {
                                    nm.contacts.add(this);
                                    nm.contacts.top(this.id);
                                } else {
                                    nm.contacts.setCounter(this.id, this.new_messages);
                                    nm.contacts.setStatus(this.id, this.is_online);
                                }
                            });

                        }

                        if (result.messages) {

                            $.each(result.messages, function () {
                                nm.messages.add(this);
                                nm.messages.lastId = this.id;
                            });

                            $('#nm-chat').waitForImages({
                                finished: function () {
                                    nm.messages.scroll();
                                },
                                waitForAll: true
                            });

                        }

                    } else {
                        console.log('Ошибка обновления данных');
                        if (result.message) {
                            alert(result.message);
                        }
                    }

                    nm.isRefreshing = false;
                    nm.setRefresh();

                });

            } else {

                nm.post('get_messages_count', {}, function (result) {

                    if (!result.error) {

                        nm.setMessagesCounter(result.messagesCount);
                        nm.setNoticesCounter(result.noticesCount);

                    } else {
                        console.log('Ошибка получения количества новых сообщений');
                        if (result.message) {
                            alert(result.message);
                        }
                    }

                    nm.isRefreshing = false;
                    nm.setRefresh();

                })

            }

        },

        // POST запрос на сервер
        post: function(act, data, doneFunc) {

            var url = '/neomessenger/' + act;

            var request = $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'json'
            });

            request.done(function(data) {
                if ($.isFunction(doneFunc)) {
                    doneFunc(data);
                }
            });

            request.fail(function () {
                if ($.isFunction(doneFunc)) {
                    doneFunc({
                        error: true
                    });
                }
            });

        },

        plural: function(count, form1, form2, form3) {
            var result = count%10==1&&count%100!=11?form1:(count%10>=2&&count%10<=4&&(count%100<10||count%100>=20)?form2:form3);
            return (result || '%d').replace('%d', count);
        },

        setMessagesCounter: function (value) {

            var $button = $('li.messages-counter');

            $('.counter', $button).remove();
            $.animateTitle('clear');

            if (value > 0) {
                var html = '<span class="counter">' + value + '</span>';
                $('a', $button).append(html);
                $.animateTitle(['*********************', 'У вас ' + nm.plural(value, '%d непрочитанное сообщение', '%d непрочитанных сообщения', '%d непрочитанных сообщений')], 1000);

                if (value > nm.messagesCount) {
                    nm.playSound();
                }
            }

            nm.messagesCount = value;

        },

        setNoticesCounter: function (value) {

            var $button = $('li.notices-counter');

            if (!$button.length && value > 0) {
                $button = $(nm.render.noticesButton()).insertAfter('li.messages-counter');
                icms.modal.bind('li.notices-counter a');
            }

            $button.unbind('click.nm.notices');
            $button.bind('click.nm.notices', function () {
                $.animateTitle('clear');
                $button.unbind('click.nm.notices');
            });

            $('.counter', $button).remove();
            $.animateTitle('clear');

            if (value > 0) {
                var html = '<span class="counter">' + value + '</span>';
                $('a', $button).append(html);
                $.animateTitle(['*********************', 'У вас ' + nm.plural(value, '%d непрочитанное уведомление', '%d непрочитанных уведомления', '%d непрочитанных уведомлений')], 1000);

                if (value > nm.noticesCount) {
                    nm.playSound();
                }
            }

            nm.noticesCount = value;

        },

        // Открытие окна мессенджера
        open: function() {

            nm.modal.show();
            nm.contacts.load(nm.recipientId);

        },

        // Контакты
        contacts: {

            contactsList: [],
            current: {},
            _lock: false,

            load: function(id) {

                var self = nm.contacts;

                self.contactsList = [];
                self.current      = {};
                self._lock        = false;
                nm.isRefreshEnabled  = false;
                self.unLock();

                nm.post('get_contacts', { recipient_id: id }, function(result) {

                    if (!result.error) {

                        if (result.contacts) {

                            $.each(result.contacts, function() {
                                self.add(this);
                            });

                            $('.nm-content').show();

                            var isSelect = false;

                            if ($(window).width() > 479) {
                                isSelect = true;
                            }

                            if (nm.recipientId > 0) {
                                isSelect = true;
                            }

                            if (isSelect) {
                                self.select(self.contactsList[0].id);
                            }

                            nm.modal.$el.addClass('nm-contacts-loaded');
                            nm.modal.onDimensions();

                        } else {
                            $('.nm-nomess').show();
                        }

                    } else {
                        console.log('Произошла ошибка при получении списка контактов');
                        if (result.message) {
                            alert(result.message);
                        }
                    }

                    nm.isRefreshEnabled = true;
                    $('.nm-loading').hide();

                });

            },

            isExist: function(id) {

                for (var i = 0; i < this.contactsList.length; i++) {
                    if (this.contactsList[i].id == id) return true;
                }

                return false;

            },

            add: function(user) {

                if (this.isExist(user.id)) return;

                this.contactsList.push(user);

                $('#nm-userlist').append(nm.render.contact(user));

            },

            select: function(id, force) {

                if (this._lock) return;

                var contact = $('#nm-contact' + id);

                nm.modal.$el.addClass('nm-selected nm-contacts-open');

                if (contact.hasClass('selected') && !force) return;

                $('#nm-userlist li').removeClass('selected');

                contact.addClass('selected');

                $.each(nm.contacts.contactsList, function() {
                    if (this.id == id) {
                        nm.contacts.current = this;
                        return;
                    }
                });

                nm.messages.load(id);

            },

            lock: function() {
                this._lock = true;
            },

            unLock: function() {
                this._lock = false;
            },

            remove: function (id) {

                var $contact = $('#nm-contact' + id),
                    $counter = $contact.find('.counter');

                if ($counter.length) {
                    alert('От контакта есть непрочитанные сообщения.');
                } else {

                    var result = confirm('Вы уверены что хотите удалить контакт?');

                    if (result) {

                        nm.post('delete_contact', { contact_id: id }, function(result) {

                            if (!result.error) {

                                for (var i = 0; i < nm.contacts.contactsList.length; i++) {
                                    if (nm.contacts.contactsList[i].id == id) {
                                        nm.contacts.contactsList.splice(i, 1);
                                        break;
                                    }
                                }

                                $contact.slideUp(function() {

                                    $(this).remove();

                                    var selectContact = nm.contacts.contactsList[0];

                                    if (selectContact) {
                                        nm.contacts.select(selectContact.id);
                                    } else {
                                        $('.nm-content').hide();
                                        $('.nm-nomess').show();
                                    }

                                });

                            } else {
                                console.log('Произошла ошибка при удалении контакта');
                                if (result.message) {
                                    alert(result.message);
                                }
                            }

                        });

                    }

                }

            },

            ignore: function (id) {

                var $contact = $('#nm-contact' + id),
                    $counter = $contact.find('.counter');

                if ($counter.length) {
                    alert('От контакта есть непрочитанные сообщения.');
                } else {

                    var result = confirm('Добавить контакт в черный список?\nВы не будете получать от него сообщения после этого');

                    if (result) {

                        nm.post('ignore_contact', {contact_id: id}, function (result) {

                            if (!result.error) {

                                var $contact = $('#nm-contact' + id);

                                for (var i = 0; i < nm.contacts.contactsList.length; i++) {
                                    if (nm.contacts.contactsList[i].id == id) {
                                        nm.contacts.contactsList.splice(i, 1);
                                        break;
                                    }
                                }

                                $contact.slideUp(function () {

                                    $(this).remove();

                                    var selectContact = nm.contacts.contactsList[0];

                                    if (selectContact) {
                                        nm.contacts.select(selectContact.id);
                                    } else {
                                        $('.nm-content').hide();
                                        $('.nm-nomess').show();
                                    }

                                });

                            } else {
                                console.log('Произошла ошибка при добавлении контакта в черный список');
                                if (result.message) {
                                    alert(result.message);
                                }
                            }

                        });

                    }
                }

            },

            forgive: function (id) {

                var result = confirm('Удалить контакт из черного списка?');

                if (result) {

                    nm.post('forgive_contact', {contact_id: id}, function (result) {

                        if (!result.error) {

                            nm.contacts.current.is_ignored = false;
                            nm.contacts.select(id, true);
                            $('#nm-contact' + id).find('.nm-contact-ignored').remove();

                        }

                    });

                }

            },

            top: function(id) {

                var $contact = $('#nm-contact' + id),
                    $container = $contact.parent();

                $container.prepend($contact).scrollTop(0);

            },

            setCounter: function(id, count) {

                var $contact = $('#nm-contact' + id),
                    $counter = $('.counter', $contact),
                    old_count = 0;

                if ($counter.length) {
                    old_count = parseInt($counter.attr('rel'));
                    $counter.remove();
                }

                if (count > 0) {
                    $contact.prepend('<span class="counter" rel="' + count + '">+ ' + count + '</span>');
                }

                if (count > old_count) {
                    this.top(id);
                }

            },

            setStatus: function(id, status) {

                var $contact = $('#nm-contact' + id);

                $contact.removeClass('nm-online');

                if (status) {
                    $contact.addClass('nm-online');
                }

            },

            filter: function() {

                var query = $('#nm-search-inp').val().trim(),
                    reg = new RegExp('^' + query.toUpperCase(), 'i');

                $('#nm-search-clr')[(query.length ? 'show' : 'hide')]();

                for (var i = 0; i < this.contactsList.length; i++) {
                    var c = this.contactsList[i],
                        $contact = $('#nm-contact' + c.id),
                        nickname = c.nickname,
                        result = reg.test(nickname.toUpperCase());

                    $contact['slide' + (result ? 'Down' : 'Up')]();
                }

            }

        },

        // Сообщения
        messages: {

            _sendLock: false,
            oldLoading: false,
            lastId: false,
            firstId: false,

            load: function(id) {

                var self = nm.messages;

                $('#nm-chat-wrapper').hide();
                $('#nm-msg-loading').show();
                $('#nm-chat').html('').unbind('scroll');
                $('#editor-nm-msg-field').val(nm.draft.get(id));

                this.lastId = false;
                this.older_id = false;
                this._sendLock = false;
                this.oldLoading = false;

                nm.isRefreshEnabled = false;
                nm.contacts.lock();

                nm.post('get_messages', {contact_id: id}, function(result) {

                    if (!result.error) {

                        var messages = result.messages;

                        $('#nm-contact-panel').html(nm.render.contactPanel(nm.contacts.current));
                        $('#nm-msg-loading').hide();
                        $('#nm-chat-wrapper').show();

                        if (messages) {
                            $.each(messages, function () {
                                self.add(this);
                            });

                            self.lastId = messages[messages.length - 1].id;

                            if (result.has_older) {
                                self.older_id = result.older_id;
                                $('#nm-chat').bind('scroll', function () {
                                    if ($(this).scrollTop() <= 5) self.oldLoad();
                                });
                            }
                        }

                        nm.modal.onDimensions();

                        $('#nm-chat').waitForImages({
                            finished: function () {
                                self.scroll();
                            },
                            waitForAll: true
                        });

                        if (nm.recipientId > 0) {
                            var $form = $('#nm-dialog .nm-editor form'),
                                $input = $('textarea', $form);

                            $input.focus();
                            nm.recipientId = 0;
                        }

                    } else {
                        console.log('Произошла шибка при загрузке сообщений');
                        if (result.message) {
                            alert(result.message);
                        }
                    }

                    nm.isRefreshEnabled = true;
                    nm.contacts.unLock();

                });

            },

            add: function(message, prepend) {

                $('#nm-chat')[(prepend ? 'prepend' : 'append')](nm.render.message(message));

            },

            oldLoad: function() {

                if (this.oldLoading) return;

                this.oldLoading = true;
                nm.contacts.lock();

                var self = nm.messages,
                    $chat = $('#nm-chat');

                $chat.prepend('<div class="older-loading"></div>');

                nm.post('more_messages', {
                    contact_id: nm.contacts.current.id,
                    message_id: self.older_id
                }, function (result) {

                    $chat.find('.older-loading').remove();

                    if (!result.error) {

                        var oldFirstId = self.older_id;
                        self.older_id = result.older_id;

                        if (result.messages) {
                            $.each(result.messages, function() {
                                self.add(this, true);
                            });

                            $chat.waitForImages({
                                finished: function() {
                                    var pos = $chat.find('#nm-message-' + oldFirstId).position();
                                    $chat.scrollTop($chat.scrollTop() + pos.top);
                                },
                                waitForAll: true
                            });
                        }

                        if (!result.has_older) {
                            $chat.unbind('scroll');
                        }

                    } else {
                        console.log('Произошла ошибка при получении старых сообщений');
                        if (result.message) {
                            alert(result.message);
                        }
                    }

                    nm.contacts.unLock();
                    self.oldLoading = false;

                });

            },

            scroll: function() {

                $('#nm-chat').scrollTop($('#nm-chat')[0].scrollHeight);

            },

            massSend: function() {

                if (this._sendLock) return;

                var result = confirm('Вы уверены что хотите разослать сообщение всем пользователям сайта?');

                if (result) {
                    nm.messages.send(true);
                }

            },

            send: function(mass) {

                if (this._sendLock) { return; }

                var self = nm.messages,
                    $form = $('#nm-dialog .nm-editor form'),
                    $chat = $('#nm-chat'),
                    $input = $('textarea', $form),
                    content = $input.val().trim(),
                    to_id = nm.contacts.current.id;

                if (!nm.isMobile) {
                    $input.focus();
                }

                if (!content) { return; }

                $input.attr('disabled', 'disabled');

                if (!mass) { nm.contacts.top(to_id); }

                self.sendLock();
                nm.contacts.lock();
                nm.isRefreshEnabled = false;

                var form_data = {
                    contact_id: to_id,
                    content: content,
                    last_id: this.lastId
                };

                var tempMsgHtml = nm.render.message({
                    from_id: nm.currentUser.id,
                    id: 'temp-msg',
                    temp: true,
                    content: '<div class="nm-temp-msg-loading"></div>',
                    user: {
                        avatar: nm.currentUser.avatar,
                        url: nm.currentUser.url
                    }
                });

                if (mass) { form_data.massmail = true; }

                $chat.append(tempMsgHtml);
                self.scroll();

                self.setReaded();

                nm.post('send_message', form_data, function (result) {

                    var newMessagesCount = 0,
                        messages = result.messages;

                    $chat.find('#nm-message-temp-msg').remove();
                    $input.removeAttr('disabled').val('');

                    if (!nm.isMobile) {
                        $input.focus();
                    }

                    nm.draft.del(to_id);

                    if (!result.error) {

                        if (messages) {

                            for (var i = 0; i < messages.length; i++) {
                                var message = messages[i];
                                if (message.is_new == "1" && message.from_id != nm.currentUser.id) {
                                    newMessagesCount++;
                                }
                                self.add(message);
                                self.lastId = message.id;
                            }

                            if (newMessagesCount) {
                                var newCount = nm.messagesCount + newMessagesCount;
                                nm.setMessagesCounter(newCount);
                            }

                            var $contact = $('#nm-contact' + nm.contacts.current.id),
                                $counter = $('.counter', $contact),
                                old_count = 0;

                            if ($counter.length) {
                                old_count = parseInt($counter.attr('rel'));
                            }

                            nm.contacts.setCounter(nm.contacts.current.id, newMessagesCount + old_count);

                            $chat.waitForImages({
                                finished: function() {
                                    self.scroll();
                                },
                                waitForAll: true
                            });

                            setTimeout(self.scroll);
                        }

                    } else {

                        if (result.message) {
                            alert(result.message);
                        } else {
                            console.log('Произошла ошибка при отправке сообщения');
                        }

                    }

                    nm.isRefreshEnabled = true;
                    nm.contacts.unLock();
                    self.sendUnLock();

                });

            },

            setReaded: function() {

                var $contact = $('#nm-contact' + nm.contacts.current.id),
                    $counter = $('.counter', $contact),
                    count = $counter.length ? parseInt($counter.attr('rel')) : 0;

                nm.contacts.setCounter(nm.contacts.current.id, 0);
                $('.conversation-item.item-left.new').removeClass('new');

                var newCount = nm.messagesCount - count;

                nm.setMessagesCounter(newCount);

                nm.post('read_messages', {
                    contact_id: nm.contacts.current.id
                });

                if (nm.isRefreshing) {
                    nm.abortRefresh = true;
                }

            },

            sendLock: function() {

                this._sendLock = true;

            },

            sendUnLock: function() {

                this._sendLock = false;

            }

        },

        draft: {

            update: function() {

                var text = $('#editor-nm-msg-field').val();

                nm.ls.set('nm_draft_' + nm.currentUser.id + '_' + nm.contacts.current.id, {text: text});

            },

            get: function(id) {

                return (nm.ls.get('nm_draft_' + nm.currentUser.id + '_' + id) || {})['text'] || '';

            },

            del: function(id) {

                nm.ls.remove('nm_draft_' + nm.currentUser.id + '_' + id);

            }

        },

        // Модальное окно
        modal: {

            visible: false,

            show: function() {

                $('html').addClass('nm-opened' + (nm.isMobile ? ' nm-mobile' : ''));
                $('body').append(nm.render.mainModal());

                this.$el = $('#nm-dialog');
                this.$bg = $('#nm-overlay');

                this.onDimensions();

                this.$el.fadeIn();
                this.$bg.trigger('nm_opened');

                this.visible = true;

            },

            hide: function() {

                var self = nm.modal;

                this.$bg.trigger('nm_closed');
                this.$el.fadeOut(function() {
                    $('html').removeClass('nm-opened' + (nm.isMobile ? ' nm-mobile' : ''));
                    $('#nm-dialog').remove();
                    self.$bg.remove();
                });

                this.visible = false;

            },

            onDimensions: function() {

                var $contactPanel = $('#nm-contact-panel');

                var cpHeight = $contactPanel.outerHeight();

                var headerHeight = 38; // Высота заголовка
                var searchHeight = 42; // Высота поиска
                var controlsHeight = $('#nm-composer').is(':visible') ? $('#nm-composer').outerHeight() : 0;
                var windowW = $(window).width();
                var windowH = $(window).height();
                var modalH = nm.modal.$el.height();
                var modalW = nm.modal.$el.width();

                $('#nm-chat').css({
                    height: modalH - headerHeight - controlsHeight - cpHeight
                });

                $('#nm-userlist').css({
                    height: modalH - headerHeight - searchHeight
                });

                if (nm.isMobile) return;

                var x = (windowH - modalH) / 2;
                var y = (windowW - modalW) / 2;

                nm.modal.$el.css({
                    top: x > 0 ? x : 0,
                    left: y > 0 ? y : 0
                });

            }

        },

        // Звуковое оповещение
        playSound: function () {
            var audio = null;
            if (typeof Audio !== "undefined") {
                audio = new Audio("/neomessenger/sounds/notify.ogg");
                if (!audio.canPlayType('/audio/ogg')) {
                    audio = new Audio("/neomessenger/sounds/notify.mp3");
                }
                audio.play();
            }
        },

        // Вьюпорт
        viewport: {

            meta: null,
            content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no',

            init: function() {
                this.metaTag = $('meta[name=viewport]');

                if (this.metaTag.length) {
                    this.default = this.metaTag.attr('content');
                } else {
                    this.metaTag = $('<meta name="viewport" content="">').appendTo('head');
                    this.default = '';
                }
            },

            change: function() {

                if (!nm.isMobile) return;

                if (!this.metaTag) { this.init(); }

                var content = this.content;

                this.metaTag.attr('content', content);

            },

            restore: function() {

                if (!nm.isMobile) return;

                var content = this.default;

                this.metaTag.attr('content', content);

            }

        },

        // LocalStorage
        ls: {

            supported: function() {
                return (window.localStorage !== undefined && window.JSON !== undefined);
            },

            set: function(key, val) {
                this.remove(key);
                try {
                    return this.supported() ? localStorage.setItem(key, JSON.stringify(val)) : false;
                } catch (e) {
                    return false;
                }
            },

            get: function(key) {
                try {
                    return this.supported() ? JSON.parse(localStorage.getItem(key)) : false;
                } catch (e) {
                    return false;
                }
            },

            remove: function(key) {
                try { localStorage.removeItem(key); } catch(e) {}
            }

        },

        // "Шаблонизатор"
        render: {

            mainModal: function() {

                return '' +
                '<div id="nm-overlay">' +
                    '<div id="nm-dialog" ' + (nm.isMobile ? 'class="mobile"' : '') + '>' +
                        '<div class="nm-header">' +
                            '<div class="nm-toggle"></div>' +
                            '<div class="nm-title">Моя переписка</div>' +
                            '<div class="nm-close" title="Закрыть окно"></div>' +
                        '</div>' +
                        '<div class="nm-body">' +
                            '<div class="nm-loading"></div>' +
                            '<div class="nm-nomess">Вы еще ни с кем не переписывались.</div>' +
                            '<div class="nm-content">' +
                                '<div class="nm-left">' +
                                    '<div class="nm-search-c-wrap">' +
                                        '<div class="nm-inp-c-bl">' +
                                            '<div class="nm-search-icon"></div>' +
                                            '<input type="text" id="nm-search-inp" value="" placeholder="Начните вводить имя...">' +
                                            '<div id="nm-search-clr" title="Сбросить фильтр"></div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<ul id="nm-userlist"></ul>' +
                                '</div>' +
                                '<div class="nm-right">' +
                                    '<div id="nm-msg-loading"></div>' +
                                    '<div id="nm-chat-wrapper">' +
                                        '<div id="nm-contact-panel"></div>' +
                                        '<div id="nm-chat" class="conversation-inner"></div>' +
                                        '<div id="nm-composer">' +
                                            '<div class="nm-editor">' +
                                                '<form action="/neomessenger/send_message" method="post">' +
                                                    nm.htmlEditor +
                                                '</form>' +
                                            '</div>' +
                                            '<div class="nm-buttons">' +
                                                '<button type="submit" id="nm-send" class="nm-submit ' + (nm.options.is_flip_buttons ? 'pull-right' : 'pull-left') + '">Отправить' + (nm.isMobile ? '' : ' (' + nm.options.sendType + ')') + '</button>' +
                                                (nm.currentUser.is_admin ? '<button type="submit" id="nm-mass-send" class="nm-submit ' + (!nm.options.is_flip_buttons ? 'pull-right' : 'pull-left') + '">Отправить всем</button>' : '') +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="clearfix"></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            },

            contact: function(user) {

                return '' +
                  '<li id="nm-contact' + user.id + '" class="user_contact' + (user.is_online ? ' nm-online' : '') + '" rel="' + user.id + '" >' +
                    (user.new_messages > 0 ? '<span class="counter" rel="' + user.new_messages + '">+ ' + user.new_messages + '</span>' : '') +
                    (user.is_ignored ? '<div class="nm-contact-ignored"></div>' : '') +
                    '<div class="userpic">' +
                      '<a href="' + user.url + '" target="_blank" style="background-image:url(' + user.avatar + ')" title="Перейти в профиль пользователя"></a>' +
                    '</div>' +
                    '<div class="username" title="' + user.nickname + '">' + user.nickname + '</div>' +
                  '</li>';

            },

            message: function(msg) {

                var isMy = false;

                if (nm.currentUser.id == msg.from_id) {
                    isMy = true;
                }

                var isNew = (msg.is_new == 1 && !isMy) ? true : false;

                return '' +
                  '<div id="nm-message-' + msg.id + '" class="conversation-item ' + (isMy ? 'item-right' : 'item-left') + (isNew ? ' new' : '') + ' clearfix" rel="' + msg.id + '">' +
                    '<div class="conversation-user">' +
                      '<a href="' + msg.user.url + '" target="_blank" style="background-image:url(' + msg.user.avatar + ')" title="Перейти в профиль пользователя"></a>' +
                    '</div>' +
                    '<div class="conversation-body">' +
                      (!msg.temp ? ('<div class="author">' + msg.user.nickname + ':</div>' +
                      '<div class="date">' + msg.date_pub + '</div>') : '') +
                      '<div class="msg-content">' + msg.content + '</div>' +
                    '</div>' +
                  '</div>';

            },

            noticesButton: function () {

                return '' +
                  '<li class="info notices-counter">' +
                  '  <a class="item" href="/messages/notices">' +
                  '    <span class="wrap">Уведомления</span>' +
                  '  </a>' +
                  '</li>';

            },

            contactPanel: function (contact) {

                return '' +
                  '<a href="' + contact.url + '" title="Перейти в профиль пользователя">' +
                  '  <span>' +
                  '    <img src="' + contact.avatar + '" width="32" height="32" border="0">' +
                  '  </span>' +
                  '  <span>' + contact.nickname + '</span>' +
                  '</a>' +
                  '<div class="nm-cp-actions">' +
                  '  <input type="button" class="button button-small" value="' + (contact.is_ignored ? 'Прекратить игнор' : 'В игнор') + '" onclick="icms.neomessenger.contacts.' + (contact.is_ignored ? 'forgive' : 'ignore') + '(' + contact.id + ')">' +
                  '  <input type="button" class="button button-small" value="Удалить" onclick="icms.neomessenger.contacts.remove(' + contact.id + ')">' +
                  '</div>' +
                  '<div class="clearfix"></div>';

            }

        }

    };

    return nm;

}).call(icms.neomessenger || {}, jQuery);
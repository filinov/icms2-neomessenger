<?php

class actionNeomessengerSendMessage extends cmsAction {

    public function run() {

        if (!$this->request->isAjax()) { cmsCore::error404(); }

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $contact_id = $this->request->get('contact_id');
        $content = $this->request->get('content');
        $last_id = $this->request->get('last_id');

        $is_massmail = $this->request->get('massmail');

        // Массовая рассылка
        if ($is_massmail) {

            if (!$user->is_admin) {
                $template->renderJSON(array('error' => true));
            }

            $content_html = cmsEventsManager::hook('html_filter', $content);

            if (!$content_html) {
                $template->renderJSON(array('error' => true));
            }

            $recipients = cmsCore::getModel('users')
                ->resetFilters()
                ->getUsersIds();

            $this->messenger->addRecipients($recipients);

            $notices_ids = $this->messenger->sendNoticePM(array(
                'content' => $content_html
            ));

            $notices_count = $notices_ids ? count($notices_ids) : 0;

            $template->renderJSON(array(
                'error' => true,
                'message' => 'Было разослано "' . $notices_count . '" уведомлений.'
            ));

        // Обычная отправка сообщения
        } else {

            // Проверяем валидность
            $is_valid = is_numeric($contact_id);

            if (!$is_valid) {
                $template->renderJSON(array('error' => true));
            }

            $contact = $this->messenger->model->getContact($user->id, $contact_id);

            // Контакт существует?
            if (!$contact) {
                $template->renderJSON(array('error' => true));
            }

            // Контакт не в игноре у отправителя?
            if ($contact['is_ignored']) {
                $template->renderJSON(array('error' => true, 'message' => LANG_PM_CONTACT_IS_IGNORED));
            }

            // Отправитель не в игноре у контакта?
            if ($this->messenger->model->isContactIgnored($contact_id, $user->id)) {
                $template->renderJSON(array('error' => true, 'message' => LANG_PM_YOU_ARE_IGNORED));
            }

            // Контакт принимает сообщения от этого пользователя?
            if (!$user->isPrivacyAllowed($contact, 'messages_pm')) {
                $template->renderJSON(array('error' => true, 'message' => LANG_PM_CONTACT_IS_PRIVATE));
            }

            // Отправляем сообщение
            $content_html = cmsEventsManager::hook('html_filter', $content);

            if ($content_html) {

                $this->messenger->setSender($user->id);
                $this->messenger->addRecipient($contact_id);
                $this->messenger->sendMessage($content_html);

                $is_online = cmsUser::userIsOnline($contact_id);

                if (!$is_online) {
                    $this->messenger->sendNoticeEmail('messages_new');
                }

            }

            //  Получаем если есть предыдущие сообщения + только-что отправленное
            $_messages = $this->model
                ->filterGt('id', $last_id)
                ->getMessages($user->id, $contact_id);

            $messages = array();

            if ($_messages) {

                $users_model = cmsCore::getModel('users');

                $users_cache = array();

                foreach ($_messages as $message) {

                    if (isset($users_cache[$message['from_id']])) {
                        $_user = $users_cache[$message['from_id']];
                    } else {
                        $_user = $users_model->getUser($message['from_id']);
                        $users_cache[$_user['id']] = $_user;
                    }

                    $message['user'] = array(
                        'id' => $_user['id'],
                        'nickname' => $_user['nickname'],
                        'url' => href_to('users', $_user['id']),
                        'avatar' => self::getAvatar($_user['avatar'], 'micro')
                    );

                    $messages[] = $message;

                }

            }

            $template->renderJSON(array(
                'error' => false,
                'messages' => $messages ? $messages : false
            ));

        }

    }

}
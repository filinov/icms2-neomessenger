<?php

class actionNeomessengerSendMessage extends cmsAction {

    public function run() {

        $user     = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $contact_id  = $this->request->get('contact_id');
        $content     = $this->request->get('content');
        $last_id     = $this->request->get('last_id');
        $csrf_token  = $this->request->get('csrf_token');

        // Проверяем валидность
        $is_valid = is_numeric($contact_id) && cmsForm::validateCSRFToken($csrf_token, false);

        if (!$is_valid) {
            $template->renderJSON(array('error' => true));
        }

        $contact = $messenger->model->getContact($user->id, $contact_id);

        // Контакт существует?
        if (!$contact) {
            $template->renderJSON(array('error' => true));
        }

        // Контакт не в игноре у отправителя?
        if ($contact['is_ignored']) {
            $template->renderJSON(array('error' => true, 'message' => LANG_PM_CONTACT_IS_IGNORED));
        }

        // Отправитель не в игноре у контакта?
        if ($messenger->model->isContactIgnored($contact_id, $user->id)) {
            $template->renderJSON(array('error' => true, 'message' => LANG_PM_YOU_ARE_IGNORED));
        }

        // Контакт принимает сообщения от этого пользователя?
        if (!$user->isPrivacyAllowed($contact, 'messages_pm')) {
            $template->renderJSON(array('error' => true, 'message' => LANG_PM_CONTACT_IS_PRIVATE));
        }

        // Отправляем сообщение
        $content_html = cmsEventsManager::hook('html_filter', $content);

        if ($content_html) {

            $messenger->setSender($user->id);
            $messenger->addRecipient($contact_id);
            $messenger->sendMessage($content_html);

            $is_online = cmsUser::userIsOnline($contact_id);

            if (!$is_online) {
                $messenger->sendNoticeEmail('messages_new');
            }

        }

        //  Получаем если есть предыдущие сообщения + только-что отправленное
        $messages = $this->model
            ->filterGt('id', $last_id)
            ->getMessages($user->id, $contact_id);

        $template->renderJSON(array(
            'error' => false,
            'messages' => $messages ? array_values($messages) : false
        ));

    }

}
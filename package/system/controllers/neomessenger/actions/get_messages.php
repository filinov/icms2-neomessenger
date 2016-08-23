<?php

class actionNeomessengerGetMessages extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $contact_id = $this->request->get('contact_id');

        // Проверяем валидность
        $is_valid = is_numeric($contact_id);

        if (!$is_valid) {
            $template->renderJSON(array('error' => true));
        }

        $messages = $this->model->limit($this->options['messages_limit'])->getMessages($user->id, $contact_id);

        $older_id = count($messages) ? $messages[0]['id'] : 0;

        $has_older = $messenger->model->hasOlderMessages($user->id, $contact_id, $older_id);

        $template->renderJSON(array(
            'messages' => $messages ? array_values($messages) : false,
            'has_older' => $has_older,
            'older_id' => $older_id,
            'csrf_token' => cmsForm::getCSRFToken()
        ));

    }

}
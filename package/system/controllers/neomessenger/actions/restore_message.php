<?php

class actionNeomessengerRestoreMessage extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();

        $message_id = $this->request->get('message_id', 0);

        if (!$message_id) {
            $template->renderJSON(array('error' => true));
        }

        $messege = $this->model->getItemById('{users}_messages', $message_id);

        if (!$messege) {
            $template->renderJSON(array('error' => true));
        }

        $field = false;

        // Если сообщение отправил я
        if ($messege['from_id'] == $user->id && $messege['is_deleted_from']) {
            $field = 'is_deleted_from';
        }

        // Если сообщение отправили мне
        if ($messege['to_id'] == $user->id && $messege['is_deleted_to']) {
            $field = 'is_deleted_to';
        }

        if (!$field) {
            $template->renderJSON(array('error' => true));
        }

        $this->model->update('{users}_messages', $message_id, array($field => null));

        $template->renderJSON(array(
            'error' => false
        ));

    }

}
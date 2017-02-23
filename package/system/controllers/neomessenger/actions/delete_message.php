<?php

class actionNeomessengerDeleteMessage extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();

        $_message_ids = $this->request->get('message_ids', array());

        if (!$_message_ids) {
            $template->renderJSON(array('error' => true));
        }

        $message_ids = array();

        foreach ($_message_ids as $message_id) {

            $messege = $this->model->getItemById('{users}_messages', $message_id);

            if (!$messege) { continue; }

            $field = false;

            // Если сообщение отправил я
            if ($messege['from_id'] == $user->id) {
                $field = 'is_deleted_from';
            }

            // Если сообщение отправили мне
            if ($messege['to_id'] == $user->id) {
                $field = 'is_deleted_to';
            }

            if ($field) {

                $this->model->update('{users}_messages', $message_id, array($field => 1));

                $message_ids[] = (int)$message_id;

            }

        }

        $template->renderJSON(array(
            'error'       => false,
            'delete_text' => LANG_NEOMESSENGER_IS_DELETE.LANG_NEOMESSENGER_DO_RESTORE,
            'message_ids' => $message_ids
        ));

    }

}
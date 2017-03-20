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

            $message = $this->model->getItemById('{users}_messages', $message_id);

            if (!$message) { continue; }

            // Если сообщение отправил я
            if ($message['from_id'] == $user->id) {

                if ($message['is_new']) {

                    $this->model->update('{users}_messages', $message_id, array(
                        'is_deleted_from' => 1,
                        'is_deleted_to'   => 1
                    ));

                } else {

                    $this->model->update('{users}_messages', $message_id, array(
                        'is_deleted_from' => 1
                    ));

                }

            }

            // Если сообщение отправили мне
            if ($message['to_id'] == $user->id) {

                $this->model->update('{users}_messages', $message_id, array(
                    'is_deleted_to' => 1
                ));

            }

            $message_ids[] = (int)$message_id;

        }

        $template->renderJSON(array(
            'error'       => false,
            'delete_text' => LANG_NEOMESSENGER_IS_DELETE.LANG_NEOMESSENGER_DO_RESTORE,
            'message_ids' => $message_ids
        ));

    }

}
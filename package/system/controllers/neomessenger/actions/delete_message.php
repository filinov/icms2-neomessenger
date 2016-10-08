<?php

class actionNeomessengerDeleteMessage extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();

        $_message_ids = $this->request->get('message_ids', array());

        if (!$_message_ids) {
            $template->renderJSON(array('error' => true));
        }

        foreach ($_message_ids as $message_id) {
            $message_ids[] = (int)$message_id;
        }

        $this->model->deleteMessages($user->id, $message_ids);

        $template->renderJSON(array(
            'error'          => false,
            'delete_text'    => LANG_NEOMESSENGER_IS_DELETE.LANG_NEOMESSENGER_DO_RESTORE,
            'message_ids'    => $message_ids
        ));

    }

}
<?php

class actionNeomessengerRestoreMessage extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();

        $message_id = $this->request->get('message_id', 0);

        if (!$message_id) {
            $template->renderJSON(array('error' => true));
        }

        $this->model->restoreMessages($user->id, $message_id);

        $template->renderJSON(array(
            'error' => false
        ));

    }

}
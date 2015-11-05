<?php

class actionNeomessengerReadMessages extends cmsAction {

    public function run() {

        if (!$this->request->isAjax()) {
            cmsCore::error404();
        }

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $contact_id = $this->request->get('contact_id');

        $this->messenger->model->setMessagesReaded($user->id, $contact_id);

        $template->renderJSON(array(
            'error' => false
        ));

    }

}
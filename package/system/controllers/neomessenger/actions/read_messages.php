<?php

class actionNeomessengerReadMessages extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $contact_id = $this->request->get('contact_id');

        $messenger->model->setMessagesReaded($user->id, $contact_id);

        $template->renderJSON(array(
            'error' => false
        ));

    }

}
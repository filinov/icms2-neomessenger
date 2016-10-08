<?php

class actionNeomessengerReadMessages extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $messenger = cmsCore::getController('messages');

        $contact_id = $this->request->get('contact_id');

        $messenger->model->setMessagesReaded($user->id, $contact_id);

        cmsTemplate::getInstance()->renderJSON(array(
            'error' => false
        ));

    }

}
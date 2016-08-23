<?php

class actionNeomessengerForgiveContact extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $contact_id = $this->request->get('contact_id');

        $contact = $messenger->model->getContact($user->id, $contact_id);

        if (!$contact){
            $template->renderJSON(array('error' => true));
        }

        $messenger->model->forgiveContact($user->id, $contact_id);

        $template->renderJSON(array(
            'error' => false
        ));

    }

}
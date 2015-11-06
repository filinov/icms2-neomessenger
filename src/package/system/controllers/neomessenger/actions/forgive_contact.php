<?php

class actionNeomessengerForgiveContact extends cmsAction {

    public function run() {

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $contact_id = $this->request->get('contact_id');

        $contact = $this->messenger->model->getContact($user->id, $contact_id);

        if (!$contact){
            $template->renderJSON(array('error' => true));
        }

        $this->messenger->model->forgiveContact($user->id, $contact_id);

        $template->renderJSON(array(
            'error' => false
        ));

    }

}
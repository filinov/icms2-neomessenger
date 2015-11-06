<?php

class actionNeomessengerDeleteContact extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();

        $contact_id = $this->request->get('contact_id');

        $contact = $this->messenger->model->getContact($user->id, $contact_id);

        if (!$contact){
            $template->renderJSON(array('error' => true));
        }

        $this->messenger->model->deleteContact($user->id, $contact_id);

        $template->renderJSON(array(
            'error' => false
        ));

    }

}
<?php

class actionNeomessengerIgnoreContact extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $contact_id = $this->request->get('contact_id');

        $contact = $messenger->model->getContact($user->id, $contact_id);

        if (!$contact) {
            $template->renderJSON(array('error' => true));
        }

        if (!$user->is_admin && $contact['is_admin']) {
            $template->renderJSON(array('error' => true, 'message' => LANG_NEOMESSENGER_ADMIN_NOT_IGNORE));
        }

        $messenger->model->ignoreContact($user->id, $contact_id);
        $messenger->model->deleteContact($user->id, $contact_id);

        $template->renderJSON(array('error' => false));

    }

}
<?php

class actionNeomessengerIgnoreContact extends cmsAction {

    public function run() {

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $contact_id = $this->request->get('contact_id');

        $contact = $this->messenger->model->getContact($user->id, $contact_id);

        if (!$contact) {
            $template->renderJSON(array('error' => true));
        }

        if (!$user->is_admin && $contact['is_admin']) {
            $template->renderJSON(array('error' => true, 'message' => LANG_NEOMESSENGER_ADMIN_NOT_IGNORE));
        }

        $this->messenger->model->ignoreContact($user->id, $contact_id);
        $this->messenger->model->deleteContact($user->id, $contact_id);

        $template->renderJSON(array('error' => false));

    }

}
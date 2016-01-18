<?php

class actionNeomessengerGetContacts extends cmsAction {

    public function run() {

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $is_allowed = $user->isInGroups($this->options['groups_allowed'] || array());

        if (!$is_allowed) {
            $template->renderJSON(array('error' => true));
        }

        $contact_id = $this->request->get('recipient_id');

        if (!empty($this->options['permanent_users'])) {

            $permanent_users = explode(',', $this->options['permanent_users']);

            foreach ($permanent_users as $permanent_user) {

                $permanent_user = (int) $permanent_user;

                if ($permanent_user && $user->id != $permanent_user) {

                    $is_contact_exists = $this->messenger->model->isContactExists($user->id, $permanent_user);

                    if (!$is_contact_exists) {

                        $this->messenger->model->addContact($user->id, $permanent_user);

                    }

                }

            }

        }

        if ($contact_id > 0) {

            $is_contact_exists = $this->messenger->model->isContactExists($user->id, $contact_id);

            if ($is_contact_exists) {
                $this->messenger->model->updateContactsDateLastMsg($user->id, $contact_id, false);
            }

            if (!$is_contact_exists) {
                $this->messenger->model->addContact($user->id, $contact_id);
            }

        }

        $contacts = $this->model->getContacts($user->id);

        $message_last_id = $this->model->getLastMessageID($user->id);

        $template->renderJSON(array(
            'error' => false,
            'contacts' => $contacts ? array_values($contacts) : false,
            'message_last_id' => $message_last_id
        ));

    }

}
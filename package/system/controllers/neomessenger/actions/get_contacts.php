<?php

class actionNeomessengerGetContacts extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $is_allowed = $user->isInGroups($this->options['groups_allowed'] || array());

        if (!$is_allowed) {
            $template->renderJSON(array('error' => true));
        }

        $contact_id = $this->request->get('recipient_id');

        if (!empty($this->options['fixed_contacts'])) {

            $fixed_contacts = explode(',', $this->options['fixed_contacts']);

            foreach ($fixed_contacts as $fixed_contact) {

                $fixed_contact = (int) $fixed_contact;

                if ($fixed_contact && $user->id != $fixed_contact) {

                    $is_contact_exists = $messenger->model->isContactExists($user->id, $fixed_contact);

                    if (!$is_contact_exists) {

                        $messenger->model->addContact($user->id, $fixed_contact);

                    }

                }

            }

        }

        if ($contact_id > 0) {

            $is_contact_exists = $messenger->model->isContactExists($user->id, $contact_id);

            if ($is_contact_exists) {
                $messenger->model->updateContactsDateLastMsg($user->id, $contact_id, false);
            }

            if (!$is_contact_exists) {
                $messenger->model->addContact($user->id, $contact_id);
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
<?php

class actionNeomessengerGetContacts extends cmsAction {

    public function run() {

        if (!$this->request->isAjax()) { cmsCore::error404(); }

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $is_allowed = $user->isInGroups($this->options['groups_allowed']);

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

        $_contacts = $this->messenger->model->select('u.is_online', 'is_online')->getContacts($user->id);

        $contacts = array();

        if ($_contacts) {
            foreach ($_contacts as $contact) {

                $contact['id'] = $contact['contact_id'];
                $contact['is_online'] = (bool)$contact['is_online'];
                $contact['url'] = href_to('users', $contact['contact_id']);
                $contact['avatar'] = $this->getAvatar($contact['avatar'], 'micro');
                $contact['is_ignored'] = (bool)$this->messenger->model->isContactIgnored($user->id, $contact['contact_id']);

                $contacts[] = $contact;

            }
        }

        $template->renderJSON(array(
            'error' => false,
            'contacts' => $contacts ? $contacts : false
        ));

    }

}
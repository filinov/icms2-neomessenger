<?php

class actionNeomessengerGetUpdate extends cmsAction {

    public function run() {

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $contact_id = $this->request->get('contact_id');
        $message_last_id = $this->request->get('message_last_id');

        $_contacts = $this->messenger->model->getContacts($user->id);

        $contacts = array();

        if ($_contacts) {
            foreach ($_contacts as $contact) {

                $contact['id'] = $contact['contact_id'];
                $contact['url'] = href_to('users', $contact['contact_id']);
                $contact['is_online'] = cmsUser::userIsOnline($contact['contact_id']);
                $contact['avatar'] = $this->getAvatar($contact['avatar'], 'micro');

                $contacts[] = $contact;

            }
        }

        $_messages = $this->model
            ->filterGt('id', $message_last_id)
            ->getMessages($user->id, $contact_id);

        $messages = array();

        if ($_messages) {

            $users_model = cmsCore::getModel('users');

            $users_cache = array();

            foreach ($_messages as $message) {

                if (isset($users_cache[$message['from_id']])) {
                    $_user = $users_cache[$message['from_id']];
                } else {
                    $_user = $users_model->getUser($message['from_id']);
                    $users_cache[$_user['id']] = $_user;
                }

                $message['user'] = array(
                    'id' => $_user['id'],
                    'nickname' => $_user['nickname'],
                    'url' => href_to('users', $_user['id']),
                    'avatar' => self::getAvatar($_user['avatar'], 'micro')
                );

                $messages[] = $message;

            }

        }

        $messages_count = $this->messenger->model->getNewMessagesCount($user->id);

        $this->messenger->model->resetFilters();

        $noticesCount = $this->messenger->model->getNoticesCount($user->id);

        $template->renderJSON(array(
            'error' => false,
            'contacts' => $contacts ? $contacts : false,
            'messages' => $messages ? $messages : false,
            'messagesCount' => $messages_count,
            'noticesCount' => $noticesCount
        ));

    }

}
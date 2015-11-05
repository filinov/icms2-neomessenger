<?php

class actionNeomessengerMoreMessages extends cmsAction {

    public function run() {

        if (!$this->request->isAjax()) {
            cmsCore::error404();
        }

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $contact_id = $this->request->get('contact_id');
        $message_id = $this->request->get('message_id');

        $contact = $this->messenger->model->getContact($user->id, $contact_id);

        if (!$contact) {
            $template->renderJSON(array('error' => true));
        }

        $_messages = $this->model
            ->filterLt('id', $message_id)
            ->limit($this->options['messages_limit'])
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

        $first_message_id = count($messages) ? $messages[0]['id'] : 0;

        $has_older = $this->messenger->model
            ->hasOlderMessages($user->id, $contact_id, $first_message_id);

        $template->renderJSON(array(
            'messages' => $messages ? array_reverse($messages) : false,
            'has_older' => $has_older,
            'older_id' => $first_message_id
        ));

    }

}
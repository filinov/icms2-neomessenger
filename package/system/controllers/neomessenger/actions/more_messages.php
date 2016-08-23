<?php

class actionNeomessengerMoreMessages extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $contact_id = $this->request->get('contact_id');
        $message_id = $this->request->get('message_id');

        $contact = $messenger->model->getContact($user->id, $contact_id);

        if (!$contact) {
            $template->renderJSON(array('error' => true));
        }

        $messages = $this->model
            ->filterLt('id', $message_id)
            ->limit($this->options['messages_limit'])
            ->getMessages($user->id, $contact_id);

        $first_message_id = count($messages) ? $messages[0]['id'] : 0;

        $has_older = $messenger->model
            ->hasOlderMessages($user->id, $contact_id, $first_message_id);

        $template->renderJSON(array(
            'messages' => $messages ? array_values(array_reverse($messages)) : false,
            'has_older' => $has_older,
            'older_id' => $first_message_id
        ));

    }

}
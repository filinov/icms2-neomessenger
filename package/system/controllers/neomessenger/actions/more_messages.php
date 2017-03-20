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
            ->limit($this->options['messages_limit'] + 1)
            ->getMessages($user->id, $contact_id);

        if (count($messages) > $this->options['messages_limit']) {
            $has_older = true;
            array_shift($messages);
        } else {
            $has_older = false;
        }

        if (is_array($messages)) {
            $messages = array_reverse($messages);
        }

        if ($messages && $this->isExtendsEnabled()) {
            $extends_ctrl = cmsCore::getController('nm_extends');
            $messages = $extends_ctrl->prepareMessages($messages);
        }

        $template->renderJSON(array(
            'messages' => $messages,
            'has_older' => $has_older
        ));

    }

}
<?php

class actionNeomessengerGetUpdate extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();

        $message_last_id = $this->request->get('message_last_id');

        $contacts = $this->model->getContacts($user->id);

        $messages = $this->model
            ->filterGt('id', $message_last_id)
            ->getMessagesFromAllContacts($user->id);

        $messages_count = $this->model->getNewMessagesCount($user->id);

        if ($messages && $this->isExtendsEnabled()) {
            $extends_ctrl = cmsCore::getController('nm_extends');
            $messages = $extends_ctrl->prepareMessages($messages);
        }

        cmsTemplate::getInstance()->renderJSON(array(
            'error' => false,
            'contacts' => $contacts,
            'messages' => $messages,
            'messagesCount' => $messages_count
        ));

    }

}
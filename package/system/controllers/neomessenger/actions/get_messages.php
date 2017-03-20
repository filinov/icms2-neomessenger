<?php

class actionNeomessengerGetMessages extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();

        $contact_id = $this->request->get('contact_id');

        // Проверяем валидность
        $is_valid = is_numeric($contact_id);

        if (!$is_valid) {
            $template->renderJSON(array('error' => true));
        }

        $messages = $this->model
            ->limit($this->options['messages_limit'] + 1)
            ->getMessages($user->id, $contact_id);

        if (count($messages) > $this->options['messages_limit']) {
            $has_older = true;
            array_shift($messages);
        } else {
            $has_older = false;
        }

        if ($messages && $this->isExtendsEnabled()) {
            $extends_ctrl = cmsCore::getController('nm_extends');
            $messages = $extends_ctrl->prepareMessages($messages);
        }

        $template->renderJSON(array(
            'messages' => $messages,
            'has_older' => $has_older,
            'csrf_token' => cmsForm::getCSRFToken()
        ));

    }

}
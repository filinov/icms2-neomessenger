<?php

class actionNeomessengerGetMessagesCount extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();
        $template = cmsTemplate::getInstance();
        $messenger = cmsCore::getController('messages');

        $messagesCount = $messenger->model->getNewMessagesCount($user->id);

        $messenger->model->resetFilters();

        $noticesCount = $messenger->model->getNoticesCount($user->id);

        $template->renderJSON(array(
            'error' => false,
            'messagesCount' => $messagesCount,
            'noticesCount' => $noticesCount
        ));

    }

}
<?php

class actionNeomessengerGetMessagesCount extends cmsAction {

    public function run() {

        $messenger = cmsCore::getController('messages');

        $messagesCount = $messenger->model->getNewMessagesCount($this->cms_user->id);

        $messenger->model->resetFilters();

        $noticesCount = $messenger->model->getNoticesCount($this->cms_user->id);

        $this->cms_template->renderJSON(array(
            'error' => false,
            'messagesCount' => $messagesCount,
            'noticesCount' => $noticesCount
        ));

    }

}

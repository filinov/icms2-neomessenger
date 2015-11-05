<?php

class actionNeomessengerGetMessagesCount extends cmsAction {

    public function run() {

        if (!$this->request->isAjax()) { cmsCore::error404(); }

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $messagesCount = $this->messenger->model->getNewMessagesCount($user->id);

        $this->messenger->model->resetFilters();

        $noticesCount = $this->messenger->model->getNoticesCount($user->id);

        $template->renderJSON(array(
            'error' => false,
            'messagesCount' => $messagesCount,
            'noticesCount' => $noticesCount
        ));

    }

}
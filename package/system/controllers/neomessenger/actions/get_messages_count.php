<?php

class actionNeomessengerGetMessagesCount extends cmsAction {

    public function run() {

        $user = cmsUser::getInstance();

        $messagesCount = $this->model->getNewMessagesCount($user->id);

        cmsTemplate::getInstance()->renderJSON(array(
            'error' => false,
            'messagesCount' => $messagesCount
        ));

    }

}

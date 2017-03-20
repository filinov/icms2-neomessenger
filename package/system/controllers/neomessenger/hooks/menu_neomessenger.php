<?php

class onNeomessengerMenuNeomessenger extends cmsAction {

    public function run($item) {

        $user = cmsUser::getInstance();

        if (!$user->is_logged) { return false; }

        $action = $item['action'];

        if ($action === 'messages') {

            $count = $this->model->getNewMessagesCount($user->id);

            return array(
                'url'     => href_to('messages'),
                'counter' => $count
            );

        }

        return $item;

    }

}
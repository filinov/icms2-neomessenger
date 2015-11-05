<?php

class onNeomessengerMenuMessages extends cmsAction {

    public function run($item) {

        if ($item['url'] == '/messages' || $item['action'] == 'view') {

            $user = cmsUser::getInstance();
            $template = cmsTemplate::getInstance();

            $layout = $template->getLayout();

            if ($user->is_logged && $layout === "main") {

                $is_allowed = $user->isInGroups($this->options['groups_allowed']);

                if ($is_allowed) {

                    $this->addPluginToPage();

                }

            }

        }

        return $item;

    }

}
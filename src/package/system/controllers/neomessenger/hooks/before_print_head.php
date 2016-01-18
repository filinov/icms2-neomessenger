<?php

class onNeomessengerBeforePrintHead extends cmsAction {

    public function run($template) {

        $user = cmsUser::getInstance();

        if (
            $user->is_logged &&
            $template->getLayout() === "main" &&
            !empty($this->options['groups_allowed']) &&
            $user->isInGroups($this->options['groups_allowed'])
        ) {

            $this->addPluginToPage();

        }

        return $template;

    }

}
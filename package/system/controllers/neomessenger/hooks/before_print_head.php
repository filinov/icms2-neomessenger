<?php

class onNeomessengerBeforePrintHead extends cmsAction {

    public function run($template) {

        $user = cmsUser::getInstance();

        if (!$user->is_logged) { return $template; }
        if (!$user->isInGroups($this->options['groups_allowed'])) { return $template; }

        $this->appConfiguration();
        $this->addAssetsToPage();
        $this->applyStyle();
        $this->addLangJS();

        return $template;

    }

    private function appConfiguration() {

        $template = cmsTemplate::getInstance();

        $nm = array(
            'options'     => $this->getNmOptions(),
            'currentUser' => $this->getCurrentUser(),
            'htmlEditor'  => $this->getHtmlEditor()
        );

        $template->addHead('<script>var nm = ' . json_encode($nm) . ';</script>');

    }

    private function addAssetsToPage() {

        $template = cmsTemplate::getInstance();

        $template->setContext($this);

        $template->addControllerCSS('styles');
        $template->addControllerCSS('custom');

        $template->addControllerJS('libs.min');
        $template->addControllerJS('neomessenger');
        $template->addControllerJS('templates.min');

        $template->restoreContext();

        if ($this->isExtendsEnabled()) {
            cmsCore::getController('nm_extends')->addAssetsToPage();
        }

    }

    private function applyStyle() {

        $template = cmsTemplate::getInstance();

        $styler_file = $template->getTemplateFileName('controllers/neomessenger/styler');

        ob_start();

        include $styler_file;

        $style = ob_get_clean();

        $template->addHead($style);

        return true;

    }

    private function addLangJS() {

        $template = cmsTemplate::getInstance();

        $phrases = array(
            'LANG_NEOMESSENGER_YOU', 'LANG_NEOMESSENGER_CONTACT_UNREAD_MSG', 'LANG_NEOMESSENGER_CONFIRM_CONTACT_DELETE',
            'LANG_NEOMESSENGER_UM1', 'LANG_NEOMESSENGER_UM2', 'LANG_NEOMESSENGER_UM10', 'LANG_NEOMESSENGER_MAIN_TITLE',
            'LANG_NEOMESSENGER_UN1', 'LANG_NEOMESSENGER_UN2', 'LANG_NEOMESSENGER_UN10', 'LANG_DELETE', 'LANG_CANCEL',
            'LANG_NEOMESSENGER_CONFIRM_CONTACT_IGNORE', 'LANG_NEOMESSENGER_CONFIRM_CONTACT_FORGIVE', 'LANG_NEOMESSENGER_NO_MESS',
            'LANG_NEOMESSENGER_SEARCH_PLACEHOLDER', 'LANG_NEOMESSENGER_CONTACT_FORGIVE', 'LANG_NEOMESSENGER_CONTACT_IGNORE',
            'LANG_NEOMESSENGER_CP_MESSAGES_SPELLCOUNT'

        );

        $template->addHead('<script>' . $template->getLangJS($phrases) . ';</script>');

    }

}
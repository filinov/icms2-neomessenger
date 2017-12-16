<?php

class neomessenger extends cmsFrontend {

    protected $useOptions = true;

    private $is_extends_enabled = false;
    private $is_extends_checked = false;

    public $extends_ctrl = null;

    /**
     * Все запросы могут быть выполнены только авторизованными и только по аякс
     * @param type $action_name
     * @return bool
     */
    public function before($action_name) {

        parent::before($action_name);

        if (!$this->request->isAjax()) { cmsCore::error404(); }

        if (!cmsUser::isLogged()) { cmsCore::error404(); }

        return true;

    }

    /**
     * @return array
     */
    public function getNmOptions() {

        $options = array(
            'refreshInterval'    => $this->options['refresh_interval'],
            'send_enter'         => (bool)$this->options['send_enter'],
            'close_backdrop'     => (bool)$this->options['close_backdrop'],
            'show_widget_button' => (bool)$this->options['show_widget_button'],
            'is_title_count'     => (bool)$this->options['is_title_count'],
            'is_favicon_count'   => (bool)$this->options['is_favicon_count'],
            'root_url'           => cmsConfig::get('root')
        );

        if ($this->isExtendsEnabled()) {
            $options['extends'] = $this->extends_ctrl->get_options();
        }

        return $options;

    }

    /**
     * @return array
     */
    public function getCurrentUser() {

        $user = cmsUser::getInstance();

        return array(
            'id'       => $user->id,
            'nickname' => $user->nickname,
            'avatar'   => array(
                'micro' => html_avatar_image_src($user->avatar, 'micro'),
                'small' => html_avatar_image_src($user->avatar, 'small')
            ),
            'is_admin' => $user->is_admin
        );

    }

    /**
     * @return bool
     */
    public function isExtendsEnabled() {

        if ($this->is_extends_checked) {
            return $this->is_extends_enabled;
        }

        $this->is_extends_checked = true;

        if (!cmsCore::isControllerExists('nm_extends')) {
            return false;
        }

        if (!$this->isControllerInstalled('nm_extends')) {
            return false;
        }

        if (!cmsController::enabled('nm_extends')) {
            return false;
        }

        $this->extends_ctrl = cmsCore::getController('nm_extends');

        if (!$this->extends_ctrl->isActivated()) {
            return false;
        }

        return $this->is_extends_enabled = true;

    }

    /**
     * @return string
     */
    public function getHtmlEditor() {

        if ($this->isExtendsEnabled()) {
            if ($this->extends_ctrl->editorEnabled()) {
                return $this->extends_ctrl->getEditor();
            }
        }

        return cmsTemplate::getInstance()->renderInternal($this, 'editor');

    }

}
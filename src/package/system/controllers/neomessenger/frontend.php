<?php

class neomessenger extends cmsFrontend {

    protected $useOptions = true;
    public $messenger;

    function __construct($request) {

        $this->messenger = cmsCore::getController('messages');

        parent::__construct($request);

    }

    public function addPluginToPage() {

        $template = cmsTemplate::getInstance();
        $user = cmsUser::getInstance();

        $opt = array(

            'currentUser' => array(

                'id'       => $user->id,
                'nickname' => $user->nickname,
                'avatar'   => self::getAvatar($user->avatar, 'micro'),
                'is_admin' => $user->is_admin

            ),

            'options' => array(

                'refreshInterval' => $this->options['refresh_interval'],
                'sendType'        => $this->options['send_type'],
                'isBackdropClose' => $this->options['is_backdrop_close'],
                'is_flip_buttons' => $this->options['is_flip_buttons'] === 1

            ),

            'htmlEditor'  => self::htmlEditor()

        );

        $template->addCSS($template->getStylesFileName($this->name));
        $template->addJS($template->getJavascriptFileName($this->name . "/libs/isMobile"));
        $template->addJS($template->getJavascriptFileName($this->name . "/libs/animatetitle"));
        $template->addJS($template->getJavascriptFileName($this->name . "/libs/jquery.waitforimages"));
        $template->addJS($template->getJavascriptFileName($this->name . "/neomessenger"));

        $template->addHead("<script>var nm_options = " . json_encode($opt) . "</script>");

    }

    /**
     * Возвращает аватар
     * @param $avatars
     * @param string $size_preset
     * @return string
     */
    public static function getAvatar($avatars, $size_preset = 'small') {

        $config = cmsConfig::getInstance();

        $default = array(
            'normal' => 'default/avatar.jpg',
            'small' => 'default/avatar_small.jpg',
            'micro' => 'default/avatar_micro.png'
        );

        if (empty($avatars)) {
            $avatars = $default;
        }

        if (!is_array($avatars)) {
            $avatars = cmsModel::yamlToArray($avatars);
        }

        $src = $avatars[$size_preset];

        if (!strstr($src, $config->upload_host)) {
            $src = $config->upload_host . '/' . $src;
        }

        return $src;

    }

    public static function htmlEditor() {

        $field_id = 'nm-msg-field';

        $options = array(
            'id' => 'editor-' . $field_id
        );

        return html_editor($field_id, '', $options);

    }

}
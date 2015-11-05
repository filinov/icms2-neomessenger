<?php

function install_package() {

    $core = cmsCore::getInstance();

    $is_installed = $core->db->getRowsCount('controllers', 'name = "neomessenger"', 1);

    if (!$is_installed) {

        $core->db->insert('controllers', array(
            'title' => 'Неомессенджер',
            'name'  => 'neomessenger',
            'is_enabled' => 1,
            'options' => '---',
            'author' => 'NEOm@ster',
            'url' => 'http://www.instantcms.ru/users/neomaster',
            'version' => '2.2',
            'is_backend' => 1
        ));

    }

}
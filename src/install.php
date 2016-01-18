<?php

function install_package() {

    $model = new cmsModel();

    $model->filterEqual('name', 'neomessenger')->updateFiltered('controllers', array(
        'is_external' => 1
    ));

    return true;

}

function after_install_package() {

    if (file_exists(cmsConfig::get('root_path') . 'neomessenger')) {

        cmsUser::addSessionMessage(LANG_NEOMESSENGER_AFTER_INSTALL_MSG, 'error');

    }

}
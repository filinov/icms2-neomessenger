<?php

    $this->setPageTitle(LANG_NEOMESSENGER_CP_MASS_MAILING);
    $this->addBreadcrumb(LANG_NEOMESSENGER_CP_MASS_MAILING);

    $this->renderForm($form, $mailing, array(
        'action' => '',
        'method' => 'post',
        'submit' => array(
            'title' => LANG_SUBMIT
        )
    ), $errors);
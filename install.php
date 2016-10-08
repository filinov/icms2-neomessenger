<?php

function install_package() {

    $core = cmsCore::getInstance();

    if (
        !$core->db->getRowsCount('scheduler_tasks', "controller = 'messages'") &&
        !$core->db->getRowsCount('scheduler_tasks', "controller = 'neomessenger'")
    ) {
        $core->db->query("INSERT INTO `{#}scheduler_tasks` (`title`, `controller`, `hook`, `period`, `is_active`) VALUES ('Очистка удалённых личных сообщений (удалить задание на icms версии 2.6.0 и выше)', 'neomessenger', 'clean', '1440', '1');");
    }

    if (!isFieldExists('{users}_messages', 'is_deleted')) {
        $core->db->query("ALTER TABLE `{users}_messages` ADD `is_deleted` TINYINT(1) UNSIGNED NULL DEFAULT NULL");
    }

    if (!isFieldExists('{users}_messages', 'date_delete')) {
        $core->db->query("ALTER TABLE `{users}_messages` ADD `date_delete` TIMESTAMP NULL DEFAULT NULL COMMENT 'Дата удаления' AFTER `date_pub`, ADD INDEX (`date_delete`);");
    }

    $core->db->query("ALTER TABLE `{users}_messages` CHANGE `date_pub` `date_pub` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания'");

    $remove_files = array(
        'templates/default/controllers/neomessenger/styles.css'
    );

    if ($remove_files) {
        foreach ($remove_files as $file_path) {
            $file_path = cmsConfig::get('root_path') . $file_path;
            @unlink($file_path);
        }
    }


    return true;

}

/* ---------------------------------------------------------------------- */

function after_install_package() {}

/* ---------------------------------------------------------------------- */

function isFieldExists($table_name, $field) {
    $table_fields = getTableFields($table_name);
    return in_array($field, $table_fields, true);
}

function getTableFields($table) {
    $db = cmsDatabase::getInstance();
    $fields = array();
    $result = $db->query("SHOW COLUMNS FROM `{#}{$table}`");
    while($data = $db->fetchAssoc($result)){
        $fields[] = $data['Field'];
    }
    return $fields;
}
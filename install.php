<?php

function install_package() {

    $core = cmsCore::getInstance();

    // Задача для планировщика
    if (!$core->db->getRowsCount('scheduler_tasks', "controller = 'neomessenger'")) {
        $core->db->query("INSERT INTO `{#}scheduler_tasks` (`title`, `controller`, `hook`, `period`, `is_active`) VALUES ('Очистка удалённых личных сообщений', 'neomessenger', 'clean', '1440', '1');");
    }

    // Флаг удаления сообщения получателем
    if (!isFieldExists('{users}_messages', 'is_deleted_to')) {
        $core->db->query("ALTER TABLE `{users}_messages` ADD `is_deleted_to` TINYINT(1) UNSIGNED NULL DEFAULT NULL");
    }

    // Флаг удаления сообщения отправителем
    if (!isFieldExists('{users}_messages', 'is_deleted_from')) {
        $core->db->query("ALTER TABLE `{users}_messages` ADD `is_deleted_from` TINYINT(1) UNSIGNED NULL DEFAULT NULL");
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
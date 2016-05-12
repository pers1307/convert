<?php

require_once 'console.php';

/**
 * Добавление новой картинки в список
 */
$query = new MSTable('{catalog_items}');
$query->setFields(['*']);
$items = $query->getItems();

$conf = array(115, 115, true);

foreach ($items as $key => &$item) {
    $buf = unserialize($item['gallery']);

    foreach ($buf as $key2 => &$elem) {

        if (file_exists(DOC_ROOT . $elem['path']['original'])) {
            $result = MSFiles::makeImageThumb(DOC_ROOT . $elem['path']['original'], $conf);
            //$result = 'test /' . $elem['path']['original'];
            $elem['path']['min'] = $result;
        }
    }

    $item['gallery'] = serialize($buf);

    $sql = 'UPDATE ' . PRFX . "catalog_items SET `gallery`='" . $item['gallery'] . "' WHERE `id`=" . $item['id'];
    MSCore::db()->execute($sql);
}
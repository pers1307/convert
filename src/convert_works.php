<?php

require_once 'console.php';

$query = new MSTable('{works}');
$query->setFields(['*']);
$items = $query->getItems();

$galleries = [];

foreach ($items as $key =>$item) {
    $arrayGallery = unserialize($item['gallery']);

    foreach ($arrayGallery as $key2 => $pic) {
        $galleries[$key][$key2] = $pic['path']['original'];
    }
}

$conf3 = array(800, 480,
    'watermark' => array(
        'src' => DOC_ROOT . '/DESIGN/SITE/images/watermark400x400.png',
        'offset_x' => 150,
        'offset_y' => 0
    )
);

foreach ($items as $key => $item) {

    if (isset($galleries[$key])) {

        $tempGal = unserialize($item['gallery']);

        foreach ($tempGal as $key2 => $temp) {

            // Переконфигурировать картинку
            //$galleries[$key][$key2];
            $result = MSFiles::makeImageThumb(DOC_ROOT . $galleries[$key][$key2], $conf3);

            $tempGal[$key2]['path']['win'] = $result;
        }

        $items[$key]['gallery'] = serialize($tempGal);
    }
}

foreach ($items as $key => $item) {
    $sql = 'UPDATE ' . PRFX . "works SET `gallery`='" . $item['gallery'] . "' WHERE `id`=" . $item['id'];
    MSCore::db()->execute($sql);
}
<?php

require_once 'console.php';

// Перенос для каталога

$query = new MSTable('{catalog_items}');
$query->setFields(['*']);
$items = $query->getItems();

$imagies_out = [];
$galleries = [];

foreach ($items as $key =>$item) {
    $r = unserialize($item['image_out']);
    $t = unserialize($item['gallery']);

    if (isset($r[0]['path']['original'])) {
        $imagies_out[$key] = $r[0]['path']['original'];
    }

    if (isset($t[0]['path']['original'])) {
        $galleries[$key] = $t[0]['path']['original'];
    }
}

//$conf = array(160, 110, true,
//    'watermark' => array(
//        'src' => DOC_ROOT . '/DESIGN/SITE/images/watermark100x100.png',
//        'offset_x' => 30,
//        'offset_y' => 0
//    ));
//
//foreach ($items as $key => $item) {
//
//    if (isset($imagies_out[$key])) {
//        $result = MSFiles::makeImageThumb(DOC_ROOT . $imagies_out[$key], $conf);
//
//        $r = unserialize($item['image_out']);
//
//        $r[0]['path']['min'] = $result;
//
//        $r = serialize($r);
//
//        $items[$key]['image_out'] = $r;
//    }
//}
//
//foreach ($items as $key => $item) {
//    $sql = 'UPDATE ' . PRFX . "catalog_items SET `image_out`='" . $item['image_out'] . "' WHERE `id`=" . $item['id'];
//    MSCore::db()->execute($sql);
//}

$conf = array(80, 80, true,
    'watermark' => array(
        'src' => DOC_ROOT.'/DESIGN/SITE/images/watermark50x50.png',
        'offset_x' => 15,
        'offset_y' => 10
    )
);

$conf2 = array(300, 200, true,
    'watermark' => array(
        'src' => DOC_ROOT.'/DESIGN/SITE/images/watermark200x200.png',
        'offset_x' => 50,
        'offset_y' => 0
    ));

$conf3 = array(720, 480, true,
    'watermark' => array(
        'src' => DOC_ROOT.'/DESIGN/SITE/images/watermark400x400.png',
        'offset_x' => 150,
        'offset_y' => 0
    ));

foreach ($items as $key => $item) {

    if (isset($galleries[$key])) {
        $result = MSFiles::makeImageThumb(DOC_ROOT . $galleries[$key], $conf);

        $result2 = MSFiles::makeImageThumb(DOC_ROOT . $galleries[$key], $conf2);

        $result3 = MSFiles::makeImageThumb(DOC_ROOT . $galleries[$key], $conf3);


        $r = unserialize($item['gallery']);

        $r[0]['path']['min'] = $result;
        $r[0]['path']['first'] = $result2;
        $r[0]['path']['win'] = $result3;

        $r = serialize($r);

        $items[$key]['gallery'] = $r;
    }
}

foreach ($items as $key => $item) {
    $sql = 'UPDATE ' . PRFX . "catalog_items SET `gallery`='" . $item['gallery'] . "' WHERE `id`=" . $item['id'];
    MSCore::db()->execute($sql);
}



//$count = 0;
//
//foreach ($items as $key =>$item) {
//
//    if (isset($imagies_out[$key]) && isset($galleries[$key])) {
//        ++$count;
//    }
//
//    $r = unserialize($item['image_out']);
//    $t = unserialize($item['gallery']);
//
//    if (isset($r[0]['path']['original'])) {
//        $imagies_out[$key] = $r[0]['path']['original'];
//    }
//
//    if (isset($t[0]['path']['original'])) {
//        $galleries[$key] = $t[0]['path']['original'];
//    }
//}

//print_r($count);
//print_r(count($items));

//$conf = array(80, 80, true,
//    'watermark' => array(
//        'src' => DOC_ROOT.'/DESIGN/SITE/images/watermark50x50.png',
//        'offset_x' => 15,
//        'offset_y' => 10
//    )
//);
//
//$conf2 = array(300, 200, true,
//    'watermark' => array(
//        'src' => DOC_ROOT.'/DESIGN/SITE/images/watermark200x200.png',
//        'offset_x' => 50,
//        'offset_y' => 0
//    ));
//
//$conf3 = array(720, 480, true,
//    'watermark' => array(
//        'src' => DOC_ROOT.'/DESIGN/SITE/images/watermark400x400.png',
//        'offset_x' => 150,
//        'offset_y' => 0
//    ));
//
//foreach ($items as $key => $item) {
//
//    if (isset($imagies_out[$key]) && !isset($galleries[$key])) {
//
//        $result = MSFiles::makeImageThumb(DOC_ROOT . $imagies_out[$key], $conf);
//
//        $result2 = MSFiles::makeImageThumb(DOC_ROOT . $imagies_out[$key], $conf2);
//
//        $result3 = MSFiles::makeImageThumb(DOC_ROOT . $imagies_out[$key], $conf3);
//
//        $r = unserialize($item['gallery']);
//
//        $r[0]['path']['min'] = $result;
//        $r[0]['path']['first'] = $result2;
//        $r[0]['path']['win'] = $result3;
//
//        $r = serialize($r);
//
//        $items[$key]['gallery'] = $r;
//    }
//
////    if (isset($galleries[$key])) {
////        $result = MSFiles::makeImageThumb(DOC_ROOT . $galleries[$key], $conf);
////
////        $result2 = MSFiles::makeImageThumb(DOC_ROOT . $galleries[$key], $conf2);
////
////        $result3 = MSFiles::makeImageThumb(DOC_ROOT . $galleries[$key], $conf3);
////
////
////        $r = unserialize($item['gallery']);
////
////        $r[0]['path']['min'] = $result;
////        $r[0]['path']['first'] = $result2;
////        $r[0]['path']['win'] = $result3;
////
////        $r = serialize($r);
////
////        $items[$key]['gallery'] = $r;
////    }
//}
//
//foreach ($items as $key => $item) {
//    $sql = 'UPDATE ' . PRFX . "catalog_items SET `gallery`='" . $item['gallery'] . "' WHERE `id`=" . $item['id'];
//    MSCore::db()->execute($sql);
//}
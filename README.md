# Publisher convert

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Кароч, это конвертер картинок для Publisher'а.
Очень часто возникает ситуация, что картинки не нравятся клиенту.
Этот запил призван облегчить процесс конвертирования.

## Установка

Используй composer и все.

``` bash
$ php composer.phar require --prefer-dist pers1307/convert "dev-master"
```

## Инструкция

Пока что в пакете можно найти только сборник рецептов.

Добавление новой картинки в коллекцию, или замена старой

Быстрое решение

``` php
require_once 'console.php';

$query = new MSTable('{catalog_items}');
$query->setFields(['*']);
$items = $query->getItems();

$conf = array(115, 115, true);

foreach ($items as $key => &$item) {
    $buf = unserialize($item['gallery']);

    foreach ($buf as $key2 => &$elem) {

        if (file_exists(DOC_ROOT . $elem['path']['original'])) {
            $result = MSFiles::makeImageThumb(DOC_ROOT . $elem['path']['original'], $conf);
            $elem['path']['min'] = $result;
        }
    }

    $item['gallery'] = serialize($buf);

    $sql = 'UPDATE ' . PRFX . "catalog_items SET `gallery`='" . $item['gallery'] . "' WHERE `id`=" . $item['id'];
    MSCore::db()->execute($sql);
}
```

Правильное решение, хотя скорее обертка.

``` php
use pers1307\convert;

$convertImage = new ConvertImage();
$convertImage->setDocRoot(DOC_ROOT);
$convertImage->setConfig([115, 115, true]);
$convertImage->setCollectionKey('gallery');

$function = function($pathOriginal, $config) { MSFiles::makeImageThumb($pathOriginal, $config); };

$query = new MSTable('{catalog_items}');
$query->setFields(['*']);
$items = $query->getItems();

foreach ($items as $key => &$item) {

    $item = $convertImage->newImageInCollection($item, 'min', $function)

    $sql = 'UPDATE ' . PRFX . "catalog_items SET `gallery`='" . $item['gallery'] . "' WHERE `id`=" . $item['id'];
    MSCore::db()->execute($sql);
}
```

Пример переконвертирования картинки в галлерее картинок.
Быстрое решение

``` php
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
```

## Автор

- [Pereskokov Yurii (pers1307)](https://github.com/pers1307)

## Лицензия

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

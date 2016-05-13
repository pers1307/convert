<?php
/**
 * ConvertImage.php
 * Класс помогает конвертировать картинки
 *
 * @author      Pereskokov Yurii
 * @version     0.1
 * @copyright   2016 Pereskokov Yurii
 * @license     Mediasite LLC
 * @link        http://www.mediasite.ru/
 */

namespace pers1307\convert;

use KoKoKo\assert\Assert;

class ConvertImage
{
    /**
     * @var string
     * Каталог сайта
     */
    protected $docRoot;

    /**
     * @var array
     * Конфигурация для новой картинки
     */
    protected $config;

    /**
     * @var string
     * Ключ коллекции в которой надо поменять коллекцию картинок
     */
    protected $collectionKey;

    /**
     * @param $docRoot string
     */
    public function setDocRoot($docRoot)
    {
        Assert::assert($docRoot, 'docRoot')->notEmpty()->string();

        $this->docRoot = $docRoot;
    }

    /**
     * @param $config array
     */
    public function setConfig($config)
    {
        Assert::assert($config, 'config')->isArray();

        $this->config = $config;
    }

    /**
     * @param $collectionKey string
     */
    public function setCollectionKey($collectionKey)
    {
        Assert::assert($collectionKey, 'collectionKey')->notEmpty()->string();

        $this->collectionKey = $collectionKey;
    }

    /**
     * Добавить новую картинку в коллекцию
     *
     * @param $item array
     * @param $newImageKey string
     * @param $functionConvert
     *
     * @return array
     */
    public function newImageInCollection($item, $newImageKey, $functionConvert)
    {
        Assert::assert($newImageKey, 'newImageKey')->notEmpty()->string();

        $buf = unserialize($item[$this->collectionKey]);

        foreach ($buf as $key2 => &$elem) {

            if (file_exists($this->docRoot . $elem['path']['original'])) {

                $result = $functionConvert($this->docRoot . $elem['path']['original'], $this->config);
                $elem['path'][$newImageKey] = $result;
            }
        }

        $item[$this->collectionKey] = serialize($buf);

        return $item;
    }
}
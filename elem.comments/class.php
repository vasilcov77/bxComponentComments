<?php
/**
 * Created by PhpStorm.
 * @autor: Vasilcov Maxim <vasilcov77@mail.ru>
 * Date: 27.07.2021
 * Time: 21:58
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Highloadblock\HighloadBlockTable;


Loader::includeModule("main");
Loader::includeModule('highloadblock');


class CDevComments extends CBitrixComponent implements Controllerable
{

    /**
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['IBLOCK_ID'] = (int)$arParams['IBLOCK_ID'];
        $arParams['IBLOCK_TYPE'] = (string)$arParams['IBLOCK_TYPE'];
        $arParams['ELEMENT_ID'] = (int)$arParams['ELEMENT_ID'];
        $arParams['HIGHLOAD_BLOCK_ID'] = (int)$arParams['HIGHLOAD_BLOCK_ID'];
        $arParams['HIDDEN'] = md5(implode('', $this->arParams) . __CLASS__);

        return $arParams;
    }

    /**
     * @return string[]
     */
    protected function listKeysSignedParameters(): array
    {
        return [
            'IBLOCK_ID',
            'IBLOCK_TYPE',
            'ELEMENT_ID',
            'HIGHLOAD_BLOCK_ID',
            'HIDDEN',
        ];
    }

    /**
     * @return array[][]
     */
    public function configureActions(): array
    {
        return [
            'sendMessage' => [
                'prefilters' => []
            ],
        ];
    }

    /**
     *
     * Обработчик отправки комментария
     *
     * @param $post
     * @return array
     */
    public function sendMessageAction($post): array
    {
        $error = [];
        foreach ($post as $item)
            $data[$item["name"]] = $item["value"];

        if ($data['mode'] == $this->arParams['HIDDEN'] && check_bitrix_sessid()) {
            $elemId = $this->add(
                $this->arParams['HIGHLOAD_BLOCK_ID'],
                [
                    'UF_IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                    'UF_ELEM_ID' => $this->arParams['ELEMENT_ID'],
                    'UF_FIO' => $data["fio"],
                    'UF_EMAIL' => $data["email"],
                    'UF_COMMENT' => $data['comment'],
                ]
            );
            if(empty($elemId))
                $error[] = "Произошла ошибка при добавлении элемента";
        } else {
            $error[] = "Ошибка проверки сесcии или идентификатора";
        }


        return [
            'STATUS' => empty($error),
            'ERROR' => $error
        ];
    }

    /**
     *
     * Получаем сущность
     *
     * @param $strIblockData
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getEntityDataClass($strIblockData): string
    {
        if (is_numeric($strIblockData)) {
            $arHLBlock = HighloadBlockTable::getById($strIblockData)->fetch();
        } else {
            $arHLBlock = HighloadBlockTable::getList(array('filter' => array('TABLE_NAME' => $strIblockData)))->fetch();
        }

        $obEntity = HighloadBlockTable::compileEntity($arHLBlock);
        $strEntityDataClass = $obEntity->getDataClass();

        return $strEntityDataClass;
    }

    /**
     *
     * Получаем список отфильтрованных элементов
     *
     * @param $strIblockData
     * @param array $arFilter
     * @param array $arSelect
     * @param int $intLimit
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getElement($strIblockData, $arFilter = array(), $arSelect = array(), $intLimit = 0): array
    {
        $strEntityDataClass = self::getEntityDataClass($strIblockData);

        $arQuery = array();

        if (!empty($arFilter)) {
            $arQuery['filter'] = $arFilter;
        }

        if (!empty($arSelect)) {
            $arQuery['select'] = $arSelect;
        }

        if (is_numeric($intLimit) && $intLimit > 0) {
            $arQuery['limit'] = $intLimit;
        }

        $dbData = $strEntityDataClass::getList($arQuery);

        $arElements = $dbData->fetchAll();

        return $arElements;
    }


    /**
     *
     * Добавляем элемент в Хайлоад
     *
     * @param $strIblockData
     * @param $arFields
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function add($strIblockData, $arFields): int
    {
        $strEntityDataClass = self::getEntityDataClass($strIblockData);
        $obResult = $strEntityDataClass::add($arFields);

        return $obResult->getId();
    }

    /**
     * @return mixed|void|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        $this->arResult['ELEMENTS'] = $this->getElement(
            $this->arParams['HIGHLOAD_BLOCK_ID'],
            [
                "UF_ELEM_ID" => $this->arParams['ELEMENT_ID'],
                "UF_IBLOCK_ID" => $this->arParams['IBLOCK_ID']
            ]
        );

        $this->IncludeComponentTemplate();
    }

}
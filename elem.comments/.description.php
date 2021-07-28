<?php
/**
 * Created by PhpStorm.
 * @autor: Vasilcov Maxim <vasilcov77@mail.ru>
 * Date: 27.07.2021
 * Time: 21:59
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => "Комментарии",
    "DESCRIPTION" => "Комментарии к элементу инфоблока",
    "SORT" => 10,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "Project", // for example "my_project"
        /*"CHILD" => array(
            "ID" => "", // for example "my_project:services"
            "NAME" => "",  // for example "Services"
        ),*/
    ),
    "COMPLEX" => "N",
);
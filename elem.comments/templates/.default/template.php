<?php
/**
 * Created by PhpStorm.
 * @autor: Vasilcov Maxim <vasilcov77@mail.ru>
 * Date: 27.07.2021
 * Time: 22:02
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */

/** @var CBitrixComponent $component */

$this->setFrameMode(true);

?>
<? if ($arResult['ELEMENTS']) : ?>
    <pre><?php echo print_r($arResult['ELEMENTS'], true) ?></pre>
<? endif; ?>

<form class="form" id="comments_form">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="mode" value="<?= $arParams['HIDDEN'] ?>">
    <div class="top-row">
        <div class="form-group form-group--name">
            <label for="feedback-name">ФИО</label>
            <input type="text" required data-parsley-required-message="Поле обязательно для заполнения" name="fio" id="feedback-name" placeholder="ФИО">
        </div>
        <div class="form-group form-group--email">
            <label for="feedback-email">Почта</label>
            <input type="text" required data-parsley-required-message="Поле обязательно для заполнения" name="email" id="feedback-email" placeholder="Почта">
        </div>
        <div class="form-group form-group--text">
            <label for="feedback-text">Комментарий</label>
            <div class="textarea-wrapper">
                <textarea name="comment" required data-parsley-required-message="Поле обязательно для заполнения" id="feedback-text" placeholder="Текст" rows="10"></textarea>
            </div>
        </div>

    </div>
    <div class="button-wrapper">
        <button
            type="submit"
            class="button__round__blue">Оправить
        </button>
    </div>
</form>

<script>
var params = <?=\Bitrix\Main\Web\Json::encode(['signedParameters' => $this->getComponent()->getSignedParameters()])?>; //получаем параметры
</script>
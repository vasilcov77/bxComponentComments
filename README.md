# bxComponentComments
Кастомный компонент для тестового задания

```php
    $APPLICATION->IncludeComponent(
        'dev:elem.comments',
        '',
        array(
            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'ELEMENT_ID' => $arResult['ID'],
            'HIGHLOAD_BLOCK_ID' => 4,
        )
    )
```
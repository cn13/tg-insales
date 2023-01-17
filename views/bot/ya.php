<?php

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<yml_catalog>
    <shop>
        <categories>
            <?php
            foreach ($categories as $key => $name): ?>
                <category id="<?= $key ?>"><?= $name ?></category>
            <?php
            endforeach; ?>
        </categories>
        <offers>
            <?php
            foreach ($goods as $good): ?>
                <offer id="<?= $good->id ?>">
                    <name><?= $good->name ?></name>
                    <price><?= $good->price ?></price>
                    <currencyId>RUR</currencyId>
                    <categoryId><?= md5($good->group_name) ?></categoryId>
                    <picture>http://smokelife.ru/images/<?= $good->getImage() ?></picture>
                </offer>
            <?php
            endforeach; ?>
        </offers>
    </shop>
</yml_catalog>

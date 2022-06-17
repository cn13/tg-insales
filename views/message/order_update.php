<?php

/** @var $order array */
$change = $order['order_changes'][0];
/** @var $user \app\models\UserShop */
?>
Изменение в заказе <?= $order['number'] ?>:
    <?= date('d.m.Y H:i', strtotime($change['created_at'])) ?> <?= $change['full_description'] ?>

--
Сумма: <?= $order['total_price'] ?>

<a href="https://<?= $user->shop ?>/admin2/orders/<?= $order['id'] ?>">Перейти к заказу</a>

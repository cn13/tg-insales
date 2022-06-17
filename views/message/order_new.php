<?php
/** @var $order array */
/** @var $shopUrl string */

?>
Поступил новый заказ <?= $order['number'] ?> на сумму: <?= $order['total_price'] ?>

<a href="https://<?= $shopUrl ?>/admin2/orders/<?= $order['id'] ?>">Перейти к заказу</a>

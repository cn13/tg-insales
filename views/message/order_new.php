<?php
/** @var $order array */

/** @var $user \app\models\UserShop */

?>
Поступил новый заказ <?= $order['number'] ?> на сумму: <?= $order['total_price'] ?>

<a href="https://<?= $user->shop ?>/admin2/orders/<?= $order['id'] ?>">Перейти к заказу</a>

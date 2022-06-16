<?php
/** @var $order array */

?>
Изменения в заказе <?= $order['number'] ?>:
<?php
foreach ($order['order_changes'] as $change): ?>
    - <?= $change['full_description'] ?> - <?= date('d.m.Y H:i:s', strtotime($change['created_at'])) ?><br>
<?php
endforeach; ?>

<?php
/**
 * @var $goods \app\models\Good[]
 */

?>
<style>
    .fa-check {
        color: lightgreen !important;
    }

    .fa-close {
        color: red !important;
    }

    .table-image {

    td, th {
        vertical-align: middle;
    }

    }
</style>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>Картинка</th>
        <th>Название</th>
        <th>Наличие</th>
        <th>Цена</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach ($goods as $good): ?>
        <tr>
            <th scope="row"><?= $i++ ?></th>
            <td class="w-25">
                <img src="/images/<?= $good->getImage() ?>" class="img-fluid img-thumbnail" alt="Sheep">
            </td>
            <td><?= $good->name ?></td>
            <td><i class="cust fa fa-<?= $good->balance > 0 ? "check" : "close" ?>"></i></td>
            <td><?= $good->price ?> руб</td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>
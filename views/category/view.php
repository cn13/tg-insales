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

    .img-thumbnail {
        max-width: 100px !important;
        min-width: 100px !important;
        max-height: 100px !important;
        min-height: 100px !important;
    }
</style>
<table class="table table-hover table-image">
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
                <?php
                echo \branchonline\lightbox\Lightbox::widget(
                    [
                        'files' => [
                            [
                                'thumb' => '/images/' . $good->getImage(),
                                'original' => '/images/' . $good->getImage(),
                                'title' => $good->name,
                                'thumbOptions' => [
                                    'class' => 'img-fluid img-thumbnail'
                                ]
                            ],
                        ]
                    ]
                );
                ?>
            </td>
            <td><?= $good->name ?></td>
            <td>Наличие <i class="fa fa-<?= $good->balance > 0 ? "check" : "close" ?>"></i></td>
            <!--td><?= $good->price ?> руб</td-->
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>
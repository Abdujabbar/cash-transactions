<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Transfers ' . $type;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr />
    <p>
        <?=Html::a("Incoming",
                    ['index', 'type' => \app\controllers\TransfersController::TYPE_INCOME],
                    ['class' => 'btn btn-'.($type===\app\controllers\TransfersController::TYPE_INCOME?'primary':'default')])?>
        <?=Html::a("Outcoming",
                    ['index', 'type' => \app\controllers\TransfersController::TYPE_OUTCOME],
                    ['class' => 'btn btn-'.($type===\app\controllers\TransfersController::TYPE_OUTCOME?'primary':'default')])?>
    </p>
    <hr />
    <?php if($type === \app\controllers\TransfersController::TYPE_OUTCOME):?>
    <p>
        <?= Html::a('Create Transfer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <hr />
    <?php endif;?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'header' => 'username',
                'value' => function($model) {
                    return Yii::$app->request->get('type') === \app\controllers\TransfersController::TYPE_INCOME ?
                            $model->senderUser->username : $model->receiverUser->username;
                }
            ],
            'amount',
            'created_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

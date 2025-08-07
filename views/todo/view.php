<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Todo $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Todos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// Register Font Awesome CSS for icons
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
?>
<div class="todo-view">

    <div class="d-flex align-items-center mb-4">
        <?php if ($model->is_done): ?>
            <i class="fas fa-check-circle text-success fa-2x me-3"></i>
            <h1 class="text-decoration-line-through text-muted"><?= Html::encode($this->title) ?></h1>
        <?php else: ?>
            <i class="far fa-circle text-muted fa-2x me-3"></i>
            <h1><?= Html::encode($this->title) ?></h1>
        <?php endif; ?>
    </div>

    <div class="alert alert-<?= $model->is_done ? 'success' : 'warning' ?> mb-4">
        <strong>Status:</strong> 
        <?php if ($model->is_done): ?>
            <i class="fas fa-check"></i> This task has been completed!
        <?php else: ?>
            <i class="fas fa-clock"></i> This task is still pending.
        <?php endif; ?>
    </div>

    <p>
        <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fas fa-list"></i> ' . Yii::t('app', 'Back to List'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
        'attributes' => [
            'id',
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->is_done) {
                        return '<span class="text-decoration-line-through text-muted">' . Html::encode($model->title) . '</span>';
                    }
                    return Html::encode($model->title);
                }
            ],
            [
                'attribute' => 'description',
                'format' => 'ntext',
                'value' => $model->description ?: 'No description provided'
            ],
            [
                'attribute' => 'is_done',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->is_done) {
                        return '<span class="badge bg-success"><i class="fas fa-check"></i> Completed</span>';
                    } else {
                        return '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>';
                    }
                }
            ],
        ],
    ]) ?>

</div>

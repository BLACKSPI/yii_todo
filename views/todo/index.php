<?php

use app\models\Todo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TodoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Todos');
$this->params['breadcrumbs'][] = $this->title;

// Register Font Awesome CSS for icons
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Custom CSS for todo styling
$this->registerCss('
.todo-completed {
    background-color: #f8f9fa;
    opacity: 0.8;
}
.todo-completed .todo-title {
    text-decoration: line-through;
    color: #6c757d;
}
.quick-toggle {
    cursor: pointer;
    transition: all 0.3s ease;
}
.quick-toggle:hover {
    transform: scale(1.2);
}
.todo-stats {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    margin-bottom: 20px;
}
');

// Get statistics
$totalTodos = Todo::find()->count();
$completedTodos = Todo::find()->where(['is_done' => 1])->count();
$pendingTodos = $totalTodos - $completedTodos;
?>
<div class="todo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Todo Statistics -->
    <div class="todo-stats p-4 mb-4">
        <div class="row text-center">
            <div class="col-md-4">
                <h4><i class="fas fa-list"></i> Total</h4>
                <h2><?= $totalTodos ?></h2>
            </div>
            <div class="col-md-4">
                <h4><i class="fas fa-check-circle"></i> Completed</h4>
                <h2><?= $completedTodos ?></h2>
            </div>
            <div class="col-md-4">
                <h4><i class="fas fa-clock"></i> Pending</h4>
                <h2><?= $pendingTodos ?></h2>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Create Todo'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="btn-group" role="group">
            <?= Html::a('All', ['index'], ['class' => 'btn btn-outline-secondary' . (empty(Yii::$app->request->get('TodoSearch')['is_done']) ? ' active' : '')]) ?>
            <?= Html::a('Pending', ['index', 'TodoSearch[is_done]' => 0], ['class' => 'btn btn-outline-warning']) ?>
            <?= Html::a('Completed', ['index', 'TodoSearch[is_done]' => 1], ['class' => 'btn btn-outline-success']) ?>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view table-responsive'],
        'tableOptions' => ['class' => 'table table-striped table-hover'],
        'rowOptions' => function ($model, $key, $index, $grid) {
            return $model->is_done ? ['class' => 'todo-completed'] : [];
        },
        'columns' => [
            [
                'attribute' => 'is_done',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->is_done) {
                        return '<i class="fas fa-check-circle text-success fa-lg quick-toggle" 
                                   title="Mark as pending" 
                                   onclick="toggleTodo(' . $model->id . ', 0)"></i>';
                    } else {
                        return '<i class="far fa-circle text-muted fa-lg quick-toggle" 
                                   title="Mark as completed" 
                                   onclick="toggleTodo(' . $model->id . ', 1)"></i>';
                    }
                },
                'filter' => ['0' => 'Pending', '1' => 'Completed'],
                'contentOptions' => ['style' => 'width: 80px; text-align: center;']
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    $class = $model->is_done ? 'todo-title' : '';
                    return '<span class="' . $class . '">' . Html::encode($model->title) . '</span>';
                }
            ],
            [
                'attribute' => 'description',
                'format' => 'raw',
                'value' => function ($model) {
                    if (empty($model->description)) {
                        return '<span class="text-muted">No description</span>';
                    }
                    $description = Html::encode($model->description);
                    if (strlen($description) > 100) {
                        $description = substr($description, 0, 100) . '...';
                    }
                    return $description;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'View',
                            'class' => 'btn btn-sm btn-outline-info me-1'
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => 'Update',
                            'class' => 'btn btn-sm btn-outline-primary me-1'
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Delete',
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'urlCreator' => function ($action, Todo $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

</div>

<script>
function toggleTodo(id, status) {
    fetch('<?= Url::to(['/todo/toggle']) ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>'
        },
        body: 'id=' + id + '&status=' + status
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating todo status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating todo status');
    });
}
</script>

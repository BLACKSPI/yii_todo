<?php

/** @var yii\web\View $this */

use app\models\Todo;
use yii\helpers\Html;
use yii\helpers\Url;
use Exception;

$this->title = 'Todo Application';

// Get todo statistics - handle case when table doesn't exist
try {
    $totalTodos = Todo::find()->count();
    $completedTodos = Todo::find()->where(['is_done' => 1])->count();
    $pendingTodos = $totalTodos - $completedTodos;
    $recentTodos = Todo::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
} catch (Exception $e) {
    // Database table doesn't exist yet
    $totalTodos = 0;
    $completedTodos = 0;
    $pendingTodos = 0;
    $recentTodos = [];
}
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Todo Application</h1>

        <p class="lead">Manage your tasks efficiently with our simple todo application.</p>

        <p>
            <?= Html::a('View All Todos', ['/todo/index'], ['class' => 'btn btn-lg btn-primary me-3']) ?>
            <?= Html::a('Create New Todo', ['/todo/create'], ['class' => 'btn btn-lg btn-success']) ?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2 class="card-title">
                            <i class="fas fa-list"></i> Total Tasks
                        </h2>
                        <h3 class="text-primary"><?= $totalTodos ?></h3>
                        <p class="card-text">Total number of tasks in your todo list.</p>
                        <p><a class="btn btn-outline-primary" href="<?= Url::to(['/todo/index']) ?>">View All &raquo;</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2 class="card-title">
                            <i class="fas fa-check-circle"></i> Completed
                        </h2>
                        <h3 class="text-success"><?= $completedTodos ?></h3>
                        <p class="card-text">Tasks you've successfully completed.</p>
                        <p><a class="btn btn-outline-success" href="<?= Url::to(['/todo/index', 'TodoSearch[is_done]' => 1]) ?>">View Completed &raquo;</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2 class="card-title">
                            <i class="fas fa-clock"></i> Pending
                        </h2>
                        <h3 class="text-warning"><?= $pendingTodos ?></h3>
                        <p class="card-text">Tasks waiting to be completed.</p>
                        <p><a class="btn btn-outline-warning" href="<?= Url::to(['/todo/index', 'TodoSearch[is_done]' => 0]) ?>">View Pending &raquo;</a></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($recentTodos)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3>Recent Todos</h3>
                <div class="list-group">
                    <?php foreach ($recentTodos as $todo): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">
                                <?php if ($todo->is_done): ?>
                                    <i class="fas fa-check-circle text-success"></i>
                                    <s><?= Html::encode($todo->title) ?></s>
                                <?php else: ?>
                                    <i class="far fa-circle text-muted"></i>
                                    <?= Html::encode($todo->title) ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($todo->description): ?>
                                <small class="text-muted"><?= Html::encode($todo->description) ?></small>
                            <?php endif; ?>
                        </div>
                        <span class="badge bg-<?= $todo->is_done ? 'success' : 'secondary' ?> rounded-pill">
                            <?= $todo->is_done ? 'Done' : 'Pending' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-3">
                    <?= Html::a('View All Todos', ['/todo/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

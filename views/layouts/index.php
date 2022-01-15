<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">></script>
</head>
<body class="d-flex flex-column h-100 loginBody">
<?php $this->beginBody() ?>
<header>
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <div class="col-2">
                
                </div>
                <!-- <div class="col-2">
                    <div class="btn btn-send">
                        Send
                    </div>                    
                </div> -->
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-3">
                    <ul class="header_buttons">
                        <li class="header_li <?php if($this->context->action->id=='question'){echo 'selected_tab'; }?>" actionName="question">Questions</li>
                        <li class="header_li <?php if($this->context->action->id=='response'){echo 'selected_tab'; }?>" actionName="response">Responses</li>
                        <li class="header_li <?php if($this->context->action->id=='settings'){echo 'selected_tab'; }?>" actionName="settings">Settings</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<footer>
  <?= $content ?>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
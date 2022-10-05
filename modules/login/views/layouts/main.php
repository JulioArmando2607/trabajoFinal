<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\bundles\TemplateAsset;

$bundle = TemplateAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body id="kt_body"
          class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
              <?php $this->beginBody() ?>
        <div class="d-flex flex-column flex-root">
            <!-- END Login Background Pic Wrapper-->
            <!-- START Login Right Container-->
            <?= $content ?>
            <!-- END Login Right Container-->
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

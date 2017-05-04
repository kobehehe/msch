<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->pageTitle; ?></title>
    <?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/classic/style.css");
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/../library/plugins/bootstrap2/plugins/customize-from-v3.css");
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/../library/plugins/sco/css/scojs.css");

    if ($this->action->id == "update" || $this->action->id == "create") {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/jquery/jquery-1.10.2.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/bootstrap2/js/bootstrap.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/datetimepicker/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/datetimepicker/bootstrap-datetimepicker.zh-CN.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/../library/plugins/bootstrap2/css/bootstrap-responsive.min.css");
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/../library/plugins/bootstrap2/css/bootstrap.min.css");
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/../library/plugins/datetimepicker/datetimepicker.css");
    } else {
        $bootstrap = yii::app()->getComponent("bootstrap");
        $bootstrap->register();
    }

    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/sco/js/sco.message.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/sco/js/sco.modal.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/sco/js/sco.tooltip.js", CClientScript::POS_END);
    ?>

    <style>
        ul.yiiPager .first,
        ul.yiiPager .last {
            display: inline;
        }
    </style>

</head>

<body style="padding-top: 0px;">

<?php echo $content; ?>

</body>
</html>

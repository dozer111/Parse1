<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
use yii\widgets\ActiveForm;
use yii\helpers\HTML;
?>
<div class="site-index">
<pre><?=print_r($articles)?></pre>
    <?php $form=ActiveForm::begin()?>

        <?=$form->field($model,'site')->dropDownList(['jaspervexxel.xyz']) ?>

        <?=$form->field($model,'category')->dropDownList($categories_data) ?>
        <?=$form->field($model,'limit')->textInput(['type'=>'number'])?>
    <?=HTML::submitButton(); ?>
    <?php ActiveForm::end(); ?>




    <table class="table table-hover">
            <thead>
            <tr>
                <th>№</th>
                <th>ParseTime</th>
                <th>Post time</th>
                <th>Title</th>
                <th>Text</th>
                <th>Recources</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($articles as $article):?>
            <tr>
                <th>123</th>
                <th><?=time()?></th>
                <th><?=$article[2]?></th>
                <th><a href="<?=$article['article_href']?>"><?=$article['article_title']?></a></th>
                <th><?=$article[1]?></th>
                <th> ...</th>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <a href="/web/site/index?addCvs"><button class="btn btn-success" >Create CVS file</button></a><br>
    <input type="email">
</div>

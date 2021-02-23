<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>My Friends</h1>
    </div>

    <div class="container">
        <!-- <h3 class='text-center'>My Friends</h3> -->
        <?php if(Yii::$app->session->hasFlash('message')){?>
        <div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> <?php echo Yii::$app->session->getFlash('message') ?>
        </div>
        <?php }?>
        <div>
            <span><?= Html::a('Friends You might Know',['friend'],['class'=>'btn btn-info btn-small']) ?></span>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php if(count($friends)>0){ ?>
            <?php foreach($friends as $friend) {?>
            <!-- <?php echo "User Name = {$friend->users->username}\n";?> -->
            <tbody>

                <tr>
                    <td><?php echo $friend->users->username?> </td>
                    <td><?php echo $friend->users->email ?> </td>

                    <td>
                        <div class="col-lg-offset-1 col-lg-11">
                            <span><?= Html::a('Unfriend',['/site/unfriend', 'id'=>$friend->friend_id,'userid'=>Yii::$app->user->identity->id],['class'=>'btn  btn-primary btn-small']) ?></span>
                        </div>
                    <td>
                </tr>

            </tbody>
            <?php }?>
            <?php  } else{?>
            <thead>
                <tr>
                    <th>You have no friend Yet</th>
                    <th><span><?= Html::a('Friends You might Know',['friend'],['class'=>'btn  btn-primary btn-small']) ?></span>
                    </th>
                </tr>
                <tr>
                </tr>
            </thead>
            <?php  } ?>

        </table>
    </div>
    <?= LinkPager::widget(['pagination' => $pages]) ?>


</div>
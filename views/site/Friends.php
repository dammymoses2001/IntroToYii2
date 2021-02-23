<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>


<?php 
// var_dump($friends);
$friendslist = [];
foreach ($users as $key => $value) {
    # code...
    foreach ($friends as $key => $value1) {
        # code...
       if($value->id === $value1->friend_id){
           array_push($friendslist,$value1->friend_id);
       }
    }
}

?>
<div class="jumbotron">
    <h1>People You May Know</h1>
</div>

<div class="container">
    <?php if(Yii::$app->session->hasFlash('message')){?>
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> <?php echo Yii::$app->session->getFlash('message') ?>
    </div>
    <?php }?>
    <?php if(Yii::$app->session->hasFlash('error')){?>
    <div class="alert alert-error alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Warning!</strong> <?php echo Yii::$app->session->getFlash('error') ?>
    </div>
    <?php }?>
    <div>
        <span><?= Html::a('Back','index',['class'=>'btn  btn-primary btn-small']) ?></span>

    </div>
    <!-- <h3 class='text-center'>People You May Know</h3> -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>

        <?php foreach($users as $post) {?>
        <tbody>
            <?php if($post->id !==Yii::$app->user->identity->id){ ?>
            <tr>
                <td><?php echo $post->username?>
                </td>
                <td><?php echo $post->email ?> </td>

                <td>
                    <?php if(!in_array($post->id,$friendslist)){ ?>

                    <?php $form =ActiveForm::begin();?>
                    <div class="col-lg-offset-1 col-lg-11">
                        <span><?= Html::a('Add Friend',['/site/add', 'id'=>$post->id,'userid'=>Yii::$app->user->identity->id],['class'=>'btn btn-primary btn-small']) ?></span>
                    </div>
                    <?php $form =ActiveForm::end();?>

                    <?php  }else{ ?>
                    <div class="col-lg-offset-1 col-lg-11">
                        <span><?= Html::a('Friend',['friend'],['class'=>'btn  btn-success btn-small']) ?></span>
                    </div>
                    <?php  } ?>
                <td>

            </tr>

            <?php } ?>
        </tbody>
        <?php }?>

    </table>
</div>
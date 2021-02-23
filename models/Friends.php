<?php

namespace app\models;

use Yii;


class Friends extends \yii\db\ActiveRecord{
    
    public function rules(){
        return [
            [['user_id','friend_id'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            // 'id' => 'ID',
            'user_id' => 'user_id',
            'friend_id' => 'friend_id',
            // 'date_created' => 'date_created',
           
        ];
    }
    public function getUsers(){
        return $this->hasOne(NewUser::className(),['id'=>'friend_id']);
        // return $this->hasMany(Post::className(), ['author_id' => 'id']);
    }
}
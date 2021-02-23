<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "new_user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 */
class NewUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'string','max'=>80],
            [[ 'password','authKey', 'accessToken'], 'string','max' => 255],
            // [['username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            // 'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
           
        ];
    }
    public static function findIdentity($id){
        return self::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null){
        return self::findOne(['accessToken'=>$token]);
    }
    public static function findByUsername($username){
        return self::findOne(['username'=>$username]);
    }
    public static function findByUserEmail($email){
        // echo $email;
        // var_dump($email);
        return self::find()->where(['email' => $email])->one();
    }
    public  function getId(){
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public  function validatePassword($password){
        return password_verify($password,$this->password);
    }
    public function getFriend(){
        return $this->hasMany(Friends::className(),['id'=>'user_id']);
    }
    
}
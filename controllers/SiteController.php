<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\NewUser;
use app\models\Friends;
use yii\data\Pagination;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // echo Yii::$app->user->isGuest;
        if(Yii::$app->user->isGuest){
            $this->redirect('login');
        }
        if(!Yii::$app->user->isGuest){
            $id = Yii::$app->user->identity->id;
            $query = Friends::find()->where(['user_id' => $id]);
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>2]);
            $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
                return $this->render('index', [
                    'friends' => $models,
                    'pages' => $pages,
               ]);
        }
     
        

       
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        // if(Yii::$app->user->isGuest){
        //         $this->redirect('login');
        //     }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // echo "hello";
         return $this->goBack();
        }

        $model->password = '';
        $model->email = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if(Yii::$app->user->isGuest){
            $this->redirect('login');
        }
        Yii::$app->user->logout();

        return $this->redirect('login');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
       
        if(Yii::$app->user->isGuest){
            $this->redirect('login');
        }
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
    
         if(Yii::$app->user->isGuest){
            $this->redirect('login');
        }
         return $this->render('about');
    }


public function actionRegister()
{
    $model = new NewUser();

    if ($model->load(Yii::$app->request->post())) {
        if ($model->validate()) {
            // form inputs are valid, do something here
            $model->username = $_POST['NewUser']['username'];
            $model->email = $_POST['NewUser']['email'];
            $model->password = password_hash($_POST['NewUser']['password'],PASSWORD_ARGON2I);
            $model->authKey = md5(random_bytes(5));
            $model->accessToken=password_hash(random_bytes(10),PASSWORD_DEFAULT);
            if($model->save()){
                return $this->redirect(['login']);
            }
                
            
        }
    }

    return $this->render('register', [
        'model' => $model,
    ]);
}
        public function actionFriend(){
             
       
            if(!Yii::$app->user->isGuest){
                $id = Yii::$app->user->identity->id;
                $friends = Friends::find()->where(['user_id' => $id])->All();
                $users=NewUser::find()->All();
                return $this->render('friends',['friends'=>$friends,'users'=>$users]);
            }
            return $this->render('friends');
            
        }

        public function actionAdd($id,$userid){
            // $user =intval($userid);
            $model = Yii::$app->db->createCommand('SELECT * FROM friends WHERE user_id=:id AND friend_id=:friend_id')
            ->bindValue(':id',$userid )
            ->bindValue(':friend_id', $id)
            ->queryOne();
            //  var_dump($model);
             if($model){
                Yii::$app->getSession()->setFlash('error','you have this friend already');
                    return $this->redirect(['friend']);
             }
             else{
                    $model = new Friends();
                    $model->user_id =Yii::$app->user->identity->id;
                    $model->friend_id = $id;
                   if( $model->save()){
                    Yii::$app->getSession()->setFlash('message','Friend Added');
                    return $this->redirect(['index']);
                   }
                }
            
        }

        public function actionUnfriend($id,$userid){
            //model to delete the id from the friend table
        //  echo $id;
        //  echo $userid;
            // $model = new Friends();
            
            $model = Yii::$app
            ->db
            ->createCommand()
            ->delete('friends', ['user_id' => $userid,'friend_id'=>$id])
            ->execute();
           
            if($model){
                Yii::$app->getSession()->setFlash('message','You have unadd a Friend ');
                return $this->redirect(['index']);
            } 
            else{
                Yii::$app->getSession()->setFlash('message','Oops, something went wrong');
                return $this->redirect(['index']);
            }
        }
}
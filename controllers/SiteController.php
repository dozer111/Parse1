<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\forms\ParseInputModel as ParseForm;
use app\models\SiteModel;
use app\models\CategoryModel;
use app\models\RecordDataModel;
use app\models\RecordInfoModel;
use yii\swiftmailer\Mailer;



class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
        $model=new ParseForm();

        #подключаем 2 модели для более точного задания ресурса парсинга
        $siteTableData=SiteModel::find()->select('site_url')->asArray()->all();
        $categoryTableData=CategoryModel::find()->select('category_url')->asArray()->all();
        if(isset($_POST['ParseInputModel']))
        {

        }

        $neededUrl='http://jaspervexxel.xyz/category/show/1';
        $categoryUrl='/category/show/1';
        $baseUrl='http://jaspervexxel.xyz';
        $mainAttributes=['#wrapper','.container1'];
        $searchAttributes=['.articleMain','p.article_articleText','p.article_timeCreate'];
        $parseModel=new Test2Controller($neededUrl,$baseUrl,$mainAttributes,$searchAttributes);
        #наш массив с информацией
        $articles=$parseModel->getMainParsePart();
        #записываем данные в сжатом виде
        #1 выделяем id
        $recordDataModel=new RecordDataModel();
        $recordDataId=$recordDataModel::find()->count();
        $json_data=json_encode($articles);
        #нарочно увеличиваю счетчик, чтобы записать в другую таблицу
        $recordDataId=$recordDataId+1;
        $recordDataModel->id=$recordDataId;
        $recordDataModel->record_json_data=$json_data;
        $recordDataModel->save();
        # теперь, сохраняем данные, для зрителя и красивых графиков
        #2.1 достаю данные с БД о сайте(не успел сделать нормальную админку, данный сайт написал вручную)
        $recordInfoModel= new RecordInfoModel();
        $recordInfoModel->site_id=SiteModel::find('id')->where('site_url=:url',[':url'=>$baseUrl])->limit(1)->one();
        #2.2 данные о категории (просто распарсил по href)
        $categoryModel=new CategoryModel();
        $recordInfoModel->category_id=$categoryModel::find('id')->where('category_url=:url',[':url'=>$categoryUrl])->limit(1)->one();
        #2.3 к-во распаршенных записей со страницы
        $recordInfoModel->record_count=count($articles);
        #2.4 ссылка на запакованную в json вариацию данных (по счетчику выше)
        $recordInfoModel->record_full_string_id=$recordDataId;
        #2.5 время парса ( в юниксе)
            $recordInfoModel->time_parsed=time();

        # теперь, пункт 3, после успешного сохранения передать массив с данными в вид,
        # + показать кнопку "Сохранить в  CVS"
        $recordInfoModel->save();
        
        if(isset($_GET['addCvs']))
        {
            $fp = fopen('../web/cvs/'.md5(time()).'.csv', 'w');
            foreach ($articles as $fields) {
                fputcsv($fp, $fields);
            }

        }
        
        



        return $this->render('index',['model'=>$model,'sites_data'=>$siteTableData,
            'categories_data'=>$categoryTableData,'articles'=>$articles]
            );
    }
    
    
    


}

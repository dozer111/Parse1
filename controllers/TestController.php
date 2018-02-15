<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 15.02.2018
 * Time: 1:38
 */

namespace app\controllers;
use yii\web\Controller;
use app\models\RecordDataModel;
use app\models\RecordInfoModel;
use app\models\SiteModel;
use app\models\CategoryModel;



class TestController extends Controller
{

    public function actionIndex()
    {
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
        $fp = fopen('../web/cvs/file.csv', 'w');

        foreach ($articles as $fields) {
            fputcsv($fp, $fields);
        }


       # return $this->render('index',['acceptToCvs'=>$addData,'articles'=>$articles]);
    }

    public function addCvs()
    {

    }

    public function actionShow()
    {

        return $this->render('index');

    }


}
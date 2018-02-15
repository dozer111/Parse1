<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 15.02.2018
 * Time: 0:56
 */

namespace app\controllers;
use yii\web\Controller;
use Sunra\PhpSimple\HtmlDomParser as simple_html_dom;

#==============================================================
# контроллер родитель для других парсинговых классов
#==============================================================
abstract class ParseController extends Controller
{
    public $baseUrl;
    public $parseUrl;
    public $mainFindAttributes=[];
    public $innerFindAttributes=[];

    const SITE_REFERER='http://www.google.com';

    public function __construct($neededUrl,$baseUrl,array $mainAttributes,array $innerAttributes=[])
    {
        $this->baseUrl=$baseUrl;
        $this->parseUrl=$neededUrl;
        $this->mainFindAttributes=$mainAttributes;
        $this->innerFindAttributes=$innerAttributes;
    }

    /**
     * @return mixed
     * ==========================================================
     * Метод для взятия данных с URL в виде строки
     * ==========================================================
     */
    public function getHtml($url_change='')
    {
        $ch=curl_init();
        if ($url_change==='') curl_setopt($ch,CURLOPT_URL,$this->parseUrl);
        else  curl_setopt($ch,CURLOPT_URL,$url_change);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");
        curl_setopt($ch,CURLOPT_REFERER, self::SITE_REFERER);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $data=curl_exec($ch);
        return $data;

    }




    /**
     *
     * ======================================================
     * Метод для работы с основным потоком сайта
     * =======================================================
     */
    public function getMainParsePart($articleCounter=0){


            $parsed_string=$this->getHtml();
            #$html=new HtmlDomParser();
            $html=simple_html_dom::str_get_html($parsed_string);

            $articles=$html->find($this->mainFindAttributes[0],0);
            $finalArray=[];
            # переменная для задания ключа кусочку массива
            $counter=0;
            # создаем свой счетчик, для указания нужного количества записей
            # если его нету, то ставлю свой собственный лимит
            if ($articleCounter!==0) $counter2=$articleCounter;
            else $counter2=15000000;


            foreach ($articles->find($this->mainFindAttributes[1]) as $test)
            {

                if($counter2<0)break;
                #узнаем о ссылках, а заголовках в общем потоке новостей
                $href=$this->baseUrl.$test->find('a',0)->href;
                $article_title=$test->find('a',0)->plaintext;
                $finalArray[$counter]['article_href']=$href;
                $finalArray[$counter]['article_title']=$article_title;
                # поехали по ссылкам
                if (!empty($this->innerFindAttributes))
                {
                    $inner_info=$this->getInnerParsePart($href);
                    foreach ($inner_info as $key => $value)
                    {
                        $finalArray[$counter][$key]=$value;
                    }
                    
                }
                $counter2--;
                $counter++;

            }
            # print_r($finalArray);
            return $finalArray;

    }









/**
 * @param $url
 * @return array
 * ========================================================
 * Метод для рекурсивного обхода внутренних частей сайта:
 * 1) Переход по ссылкам
 * 2) Переход по страницам
 * ========================================================
 */
protected function getInnerParsePart($url)
{
    $parsed_string=$this->getHtml($url);
    $html=simple_html_dom::str_get_html($parsed_string);
    $result=[];

    $child_wrapper=$html->find($this->innerFindAttributes[0],0);


    foreach ($this->innerFindAttributes as $attributeListKey => $attributesListValue)
        if($attributeListKey!==0)
        {

            $result[$attributeListKey]=$child_wrapper->find($attributesListValue,0)->plaintext;

        }
    return $result;
}


}
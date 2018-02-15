<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 14.02.2018
 * Time: 21:36
 */

namespace app\models\forms;
use yii\base\Model;


class ParseInputModel extends Model
{
    public $site;
    public $category;
    public $limit;


    public function rules()
    {
        return [
            [['site','category'],'string'],
            [['limit'],'integer']
        ];
    }

}
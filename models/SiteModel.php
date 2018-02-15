<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "site".
 *
 * @property int $id
 * @property string $site_url
 *
 * @property Category[] $categories
 */
class SiteModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_url'], 'required'],
            [['site_url'], 'string', 'max' => 255],
            [['site_url'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_url' => 'Site Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['site_id' => 'id']);
    }
}

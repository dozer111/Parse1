<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record_info".
 *
 * @property int $id
 * @property int $site_id
 * @property int $category_id
 * @property string $time_parsed
 * @property int $record_count
 * @property int $record_full_string_id
 *
 * @property RecordData $recordFullString
 */
class RecordInfoModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'record_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_id', 'category_id', 'time_parsed', 'record_count'], 'required'],
            [['site_id', 'category_id', 'record_count', 'record_full_string_id'], 'integer'],
            [['time_parsed'], 'string', 'max' => 255],
            [['record_full_string_id'], 'exist', 'skipOnError' => true, 'targetClass' => RecordDataModel::className(), 'targetAttribute' => ['record_full_string_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Site ID',
            'category_id' => 'Category ID',
            'time_parsed' => 'Time Parsed',
            'record_count' => 'Record Count',
            'record_full_string_id' => 'Record Full String ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecordFullString()
    {
        return $this->hasOne(RecordData::className(), ['id' => 'record_full_string_id']);
    }
}

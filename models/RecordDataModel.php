<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record_data".
 *
 * @property int $id
 * @property string $record_json_data
 *
 * @property RecordInfo[] $recordInfos
 */
class RecordDataModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'record_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_json_data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_json_data' => 'Record Json Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecordInfos()
    {
        return $this->hasMany(RecordInfoModel::className(), ['record_full_string_id' => 'id']);
    }
}

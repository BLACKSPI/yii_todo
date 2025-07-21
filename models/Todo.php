<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "todo".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $is_done
 */
class Todo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'todo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['is_done'], 'default', 'value' => 0],
            [['title'], 'required'],
            [['description'], 'string'],
            [['is_done'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'is_done' => 'Is Done',
        ];
    }

}

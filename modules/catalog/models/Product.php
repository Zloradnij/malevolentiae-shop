<?php

namespace app\modules\catalog\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $catalog_id
 * @property string $title
 * @property string $alias
 * @property int $sort
 * @property int $status
 * @property double $price
 * @property string $description_short
 * @property string $description
 * @property string $import_path
 */
class Product extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 0;

    public function behaviors()
    {
        return [
            BlameableBehavior::class,
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catalog_id', 'sort', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'alias', 'status'], 'required'],
            [['price'], 'number'],
            [['description_short', 'description'], 'string'],
            [['title', 'alias', 'import_path'], 'string', 'max' => 250],
            ['status', 'in', 'range' => [static::STATUS_ACTIVE, static::STATUS_DELETE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('shop', 'ID'),
            'catalog_id'        => Yii::t('shop', 'Catalog ID'),
            'title'             => Yii::t('shop', 'Title'),
            'alias'             => Yii::t('shop', 'Alias'),
            'sort'              => Yii::t('shop', 'Sort'),
            'status'            => Yii::t('shop', 'Status'),
            'price'             => Yii::t('shop', 'Price'),
            'description_short' => Yii::t('shop', 'Description Short'),
            'description'       => Yii::t('shop', 'Description'),
            'import_path'       => Yii::t('shop', 'Import Path'),

            'created_at'        => Yii::t('shop', 'Created At'),
            'updated_at'        => Yii::t('shop', 'Updated At'),
            'created_by'        => Yii::t('shop', 'Created By'),
            'updated_by'        => Yii::t('shop', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\catalog\models\query\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\catalog\models\query\ProductQuery(get_called_class());
    }

    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['id' => 'catalog_id']);
    }

    public function getProduct2Category()
    {
        return $this->hasMany(Product2category::class, ['product_id' => 'id']);
    }

    public function getCategories()
    {
        return $this
            ->hasMany(Category::class, ['id' => 'category_id'])
            ->via('product2Category');
    }

    public function getVariants()
    {
        return $this->hasMany(ProductVariant::class, ['product_id' => 'id']);
    }
}

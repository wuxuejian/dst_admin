<?php
namespace backend\models;
use yii\db\ActiveRecord;
class ConfigCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%config_category}}';
    }
    
    public function rules(){
        return [
            ['title','filter','filter'=>'htmlspecialchars'],
            ['parent_id','checkParentId'],
            ['title','required'],
            ['key','match','pattern'=>'/^\w+$/','when'=>function($model){
                return !empty($model->key);
            },'message'=>'分类键名只能是数字、字母或下划线！'],
            ['key','unique','when'=>function($model){
                return !empty($model->key);
            },'message'=>'分类键名已经存在！'],
            ['list_order','filter','filter'=>'intval']
        ];
    }
    /**
     * 验证父级id是否正确
     */
    public function checkParentId(){
        if(!$this->parent_id){
            $this->parent_id = 0;
            return true;
        }
        if(!self::find()->select('id')->one()){
            $this->addError('parent_id','非法的父级配置');
            return false;
        }
        return true;
    }
    
    /**
     * 获取所有的父分类
     * @param array $columns 需要的字段
     * @return array
     */
    public function getTopCategory(array $columns = [])
    {
        if(!$columns){
            $select = '*';
        }else{
            $select = $columns;
        }
        return self::find()->select($select)->where([
            'parent_id'=>0
        ])->orderBy('`list_order` desc,`id` asc')->asArray()->all();
    }
    
    /**
     * 获取一个配置分类下的所有配置选项
     * @param  array  $key     分类配置所对应的key
     * @param  string $indexBy 索引字段（该字段只能是配置项表中的字段）
     * @return array      $key所指定的所有配置项
     */
    public function getCategoryConfig($key= [],$indexBy = 'id')
    {
        if(!$key){
            return false;
        }
        $category = $this::find()->select(['id','key'])->where(['in','key',$key])->indexBy('id')->asArray()->all();
        $ids = array_keys($category);
        if(!$ids){
            return false;
        }
        $items = ConfigItem::find()->select(['id','value','text','belongs_id','note'])->where(['and',['in','belongs_id',$ids],'is_del=0'])->orderBy('`list_order` desc,`id`')->asArray()->all();
        $data = [];
        foreach ($items as $val){
            $key = $category[$val['belongs_id']]['key'];
            if(!isset($data[$key])){
                $data[$key] = [];
            }
            $index = $val[$indexBy];
            $data[$key][$index] = $val;
            
        }
        return $data;
    }
    /**
     * 获取分类的配置项
     * @param array $column 要获取的字段
     */
    public function getCategoryConfigItems($column)
    {
        return $this::hasMany(ConfigItem::className(),['belongs_id'=>'id'])
        ->select(['value','text'])->asArray()->all();
    }
    /**
     * 验证一个配置分类是否有值为$value的配置项
     * @param  string $key 分类配置所对应的key
     * @param  int $itemId 要验证的配置项的id
     */
    /*public function checkCategoryConfigItem($key,$itemId){
        $category = self::find()->select(['id'])->where(['key'=>$key])->one();
        return $category->hasMany(ConfigItem::className(),['belongs_id'=>'id'])
        ->select(['id'])->andWhere(['id'=>$itemId])->one() ? true : false;
    }*/

    /**
     * 根据value验证配置项是否正确
     * @param  string $key   配置分类对应的key
     * @param  string $value 要验证的配置项的值
     * @return boolean       true/false
     */
    public static function checkCategoryConfigItemByValue($key,$value)
    {
        $category = self::find()->select(['id'])->where(['key'=>$key])->asArray()->one();
        if(!$category) return false;
        $item = ConfigItem::find()->select(['id'])->where(['belongs_id'=>$category['id'],'value'=>$value])->one();
        return $item ? true : false;
    }
}

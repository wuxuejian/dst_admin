<?php
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\ConfigItem;
class ConfigController extends BaseController
{

    /**
     * 配置分类管理
     */
    public function actionCategoryManage()
    {
        $button = $this->getCurrentActionBtn();
        return $this->render('category-manage',[
            'button'=>$button
        ]);
    }
    
    /**
     * 获取配置分类列表
     */
    public function actionGetCategoryList()
    {
        $configCategory = ConfigCategory::find()
                          ->where(['<>','is_del','1'])
                          ->orderBy('`list_order` desc,`id` asc')->asArray()->all();
        require(dirname(dirname(getcwd())).'/vendor/siteextension/class/Category.class.php');
        $configCategory = \Category::unlimitedForLayer($configCategory,'parent_id');
        $returnArr = [];
        $returnArr['total'] = 0;
        $returnArr['rows'] = $configCategory;
        echo json_encode($returnArr);
    }
    
    /**
     * 添加配置分类
     */
    public function actionCategoryAdd(){
        $model = new ConfigCategory();
        //data submit start
        if(yii::$app->request->isPost){
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                $saveAttributes = [
                    'id',
                    'parent_id',
                    'title',
                    'key',
                    'list_order'
                ];
                if($model->save(false,$saveAttributes)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '配置分类添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '配置分类添加失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0].'&';
                    }
                    $errorStr = rtrim($errorStr,'&');
                }else{
                    $errorStr = '未知错误';
                }
                $returnArr['info'] = $errorStr;
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        //查询所有父级分类
        $topCategory = $model->getTopCategory(['id','title']);
        return $this->render('category-add',[
            'topCategory'=>$topCategory
        ]);
    }
    
    /**
     * 修改配置分类信息
     */
    public function actionCategoryEdit()
    {
        $id = intval($_REQUEST['id']);
        //data submit start
        if(yii::$app->request->isPost){
            $model = ConfigCategory::findOne(['id'=>$id]);
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                $saveAttributes = [
                    'parent_id',
                    'title',
                    'key',
                    'list_order'
                ];
                if($model->save(false,$saveAttributes)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '配置分类添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '配置分类添加失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0].'&';
                    }
                    $errorStr = rtrim($errorStr,'&');
                }else{
                    $errorStr = '未知错误';
                }
                $returnArr['info'] = $errorStr;
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $model = new ConfigCategory();
        $topCategory = $model->getTopCategory(['id','title']);
        $category = $model::find()->where(['id'=>$id])->asArray()->one();
        return $this->render('category-edit',[
            'topCategory'=>$topCategory,
            'category'=>$category
        ]);
    }
    
    /**
     * 删除配置分类
     */
    public function actionCategoryRemove()
    {
        $id = intval($_GET['id']) or die('param id is required');
        //查询该类的子类
        $returnArr = [];
        if(ConfigCategory::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '配置分类删除成功';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '配置分类删除失败';
        }
        echo json_encode($returnArr);
    }

    /**
     * 锁定配置分类
     */
    public function actionCategoryLock()
    {
        $id = intval($_GET['id']) or die('param id is required');
        $configCategory = ConfigCategory::find()->select(['is_lock'])->where(['id'=>$id])->asArray()->one();
        $configCategory or die('record not found');
        $returnArr = [];
        if($configCategory['is_lock'] == 1){
            //解锁
            if(ConfigCategory::updateAll(['is_lock'=>0],['id'=>$id])){
                $returnArr['status'] = true;
                $returnArr['info'] = '配置分类解锁成功！';
            }else{
                $returnArr['status'] = false;
                $returnArr['info'] = '配置分类解锁失败！';
            }
        }else{
            //锁定
            if(ConfigCategory::updateAll(['is_lock'=>1],['id'=>$id])){
                $returnArr['status'] = true;
                $returnArr['info'] = '配置分类锁定成功！';
            }else{
                $returnArr['status'] = false;
                $returnArr['info'] = '配置分类锁定失败！';
            }
        }
        echo json_encode($returnArr);
    }
    
    /**
     * 配置条目管理
     */
    public function actionItemManage()
    {
        return $this->render('item-manage');
    }
    
    /**
     * 获取配置分类树
     */
    public function actionGetCategoryTree()
    {
        $category = ConfigCategory::find()
                    ->where(['!=','is_del','1'])
                    ->select(['id','text'=>'title','parent_id'])->asArray()->all();
        require(dirname(dirname(getcwd())).'/vendor/siteextension/class/Category.class.php');
        $category = \Category::unlimitedForLayer($category,'parent_id');
        echo json_encode($category);
    }
    
    /**
     * 子类配置索引页
     */
    public function actionItemIndex()
    {
        //检测当前配置分类是否被锁定
        $belongsId = intval(yii::$app->request->get('belongsId')) or die('param belongsId is required');
        $configCategory = ConfigCategory::find()->select(['is_lock'])->where(['id'=>$belongsId])->one();
        $configCategory or die('record not found!');
        if($configCategory['is_lock'] == 1 && !self::$isSuperman){
            //分类被锁定并且当前不是superman登陆
            $button = [];
        }else{
            $button = $this->getCurrentActionBtn();
        }
        return $this->render('item-index',[
            'belongsId'=>$belongsId,
            'button'=>$button
        ]);
    }
    
    /**
     * 获取子类配置列表
     */
    public function actionGetItemList()
    {
        $pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
        $belongsId = intval(yii::$app->request->get('belongsId'));
        $activeRecord = ConfigItem::find()
                        ->andWhere(['belongs_id'=>$belongsId])
                        ->andWhere(['<>','is_del',1]);
        $total = $activeRecord->count();
        $pages = new Pagination(['totalCount'=>$total,'pageSize'=>$pageSize]);
        $data = $activeRecord->offset($pages->offset)->limit($pages->limit)->orderBy('`list_order` desc,`id` asc')->asArray()->all();
        $returnArr = [];
        $returnArr['total'] = $total;
        $returnArr['rows'] = $data;
        echo json_encode($returnArr);
    }
    
    /**
     * 添加配置项目
     */
    public function actionItemAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new ConfigItem();
            $model->setScenario('add');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                //不允许非superman为锁定分类添加配置项
                if(!self::$isSuperman){
                    $configCategory = ConfigCategory::find()->select(['is_lock'])->where(['id'=>$model->belongs_id])->one();
                    if($configCategory['is_lock'] == 1){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '无法为锁定分类添加配置项！';
                        echo json_encode($returnArr);
                        return false;
                    }
                }
                //不允许非superman为锁定分类添加配置项结束
                $saveAttributes = [
                    'belongs_id',
                    'value',
                    'text',
                    'note',
                    'list_order'
                ];
                if($model->save(false,$saveAttributes)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '配置项添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '配置项添加失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0].'&';
                    }
                    $errorStr = rtrim($errorStr,'&');
                }else{
                    $errorStr = '未知错误';
                }
                $returnArr['info'] = $errorStr;
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $belongsId = intval(yii::$app->request->get('belongsId'));
        return $this->render('item-add',[
            'belongsId'=>$belongsId
        ]);
    }
    
    /**
     * 修改配置项
     */
    public function actionItemEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id'));
            $model = ConfigItem::findOne(['id'=>$id]);
            $model->setScenario('edit');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                //不允许非superman修改锁定分类的配置项
                if(!self::$isSuperman){
                    $configCategory = ConfigCategory::find()->select(['is_lock'])->where(['id'=>$model->getOldAttribute('belongs_id')])->one();
                    if($configCategory['is_lock'] == 1){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '无法修改锁定分类下的配置项！';
                        echo json_encode($returnArr);
                        return false;
                    }
                }
                //不允许非superman修改锁定分类的配置项结束
                $saveAttributes = [
                    'value',
                    'text',
                    'note',
                    'list_order'
                ];
                if($model->save(false,$saveAttributes)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '配置项修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '配置项修改失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0].'&';
                    }
                    $errorStr = rtrim($errorStr,'&');
                }else{
                    $errorStr = '未知错误';
                }
                $returnArr['info'] = $errorStr;
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = intval(yii::$app->request->get('id'));
        $item = ConfigItem::find()->where(['id'=>$id])->asArray()->one();
        return $this->render('item-edit',[
            'item'=>$item
        ]);
    }
    
    /**
     * 配置项删除
     */
    public function actionItemRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
        $configItem = ConfigItem::find()->select(['belongs_id'])->where(['id'=>$id])->asArray()->one();
        $configItem or die('record not found!');
        $returnArr = [];
        //不允许非superman删除锁定配置项
        if(!self::$isSuperman){
            $configCategory = ConfigCategory::find()->select(['is_lock'])->where(['id'=>$configItem['belongs_id']])->one();
            if($configCategory['is_lock'] == 1){
                $returnArr['status'] = false;
                $returnArr['info'] = '无法删除锁定分类下的配置项！';
                echo json_encode($returnArr);
                die;
            }
        }
        if(ConfigItem::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '配置项删除成功';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '配置项删除失败';
        }
        echo json_encode($returnArr);
    }

    /**
     * 创建配置文件
     * system/config/create-config
     */
    public function actionCreateConfig(){
        $str = "<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=car_system',
            'dsn' => 'mysql:host=localhost;dbname=car_system', 
            'username' => 'root',
            'password' => '4Z3uChwl',
            'charset' => 'utf8',
            'tablePrefix' => 'cs_'
        ],
        'db1' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=car_system',
            'dsn' => 'mysql:host=localhost;dbname=car_monidata', 
            'username' => 'root',
            'password' => '4Z3uChwl',
            'charset' => 'utf8',
            'tablePrefix' => 'cs_'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];";
        return file_put_contents('../../common/config/main-local.php',$str);
    }
}
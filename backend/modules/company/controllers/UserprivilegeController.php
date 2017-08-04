<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/6/1
 * Time: 13:55
 * 个人权限设置
 */
namespace frontend\modules\company\controllers;
use Yii;
use yii\data\Pagination;
use common\models\ProProduceUser;
use common\models\SysMenu;
use frontend\modules\company\models\BusUserPriv;

class UserprivilegeController extends \frontend\controllers\BaseController {

    public function actionIndex(){

        $datas['urls'] = array(
            'setpri' => \Yii::$app->urlManager->createUrl(['company/userprivilege/set-pri']),
            'savepri' => \Yii::$app->urlManager->createUrl(['company/userprivilege/save-pri']),
        );

        $buttons = [
            'setpri'=>array('text'=>'权限设置','class'=>'icon-user-key', 'click'=>'Userpri.setPriv()'),

        ];
        $datas['buttons'] = $this->validateUserButtons(__METHOD__, $buttons, $_SESSION['bUserId']);  // 权限过滤
        return $this->renderPartial('index',['datas'=>$datas]);
    }
    /**
     * @name 人员列表
     * @return json
     */
    public function actionList(){
        $search = Yii::$app->request->post();

        $session = Yii::$app->session;
        $session->open();
        $bossid = $session['bBossInfo']['id'];
        $page = ($search['page'] ? $search['page'] : 10);
        $rows = ($search['rows'] ? $search['rows'] : 20);

        $sort='';
        if($search['sort'] && $search['order']){

            $sort=$search['sort'].' '.$search['order'];
        }else{
            $sort="itemid asc";
        }
        $query = ProProduceUser::find()->with('companys')->with('depart')
            ->where("deleted=0 and status_lock=0 and boss_id = $bossid");
        if(!empty($search['worker_name'])){
            $query->andFilterWhere(['worker_name'=>$search['worker_name']]);
        }
        if(!empty($search['user_name'])){
            $query->andFilterWhere(['user_name'=>$search['user_name']]);
        }
        if(!empty($search['part_id'])){
            $query->andFilterWhere(['part_id'=>$search['part_id']]);
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = $rows;
        $models = $query->offset(($page-1)*$rows)->limit($pages->limit)->orderBy($sort)
            ->asArray()->all();
        foreach($models as $key=>$company)
        {
            $companys = $company['companys'];
            $models[$key]['boss_id'] = $companys['company_name'];

            $departs = $company['depart'];
            $models[$key]['part_id'] = $departs['department'];
        }
        echo json_encode( ['total' =>$pages->totalCount, 'rows' =>$models]);

    }

    /**
     * @name 以下为个人权限设置 如果有该权限默认勾选
     */
    public function actionSetPri(){
       /* //所有主目录
        $menuAll =SysMenu::find()
            ->select('id, parent, mark, text, buttons')
            ->indexBy('mark')
            ->asArray()
            ->where("deleted=0 AND installed=1 AND parent ='B' ")
            ->orderBy('parent, sort, mark')
            ->all();
        $menu_all =array();
        foreach($menuAll as $m){
            $key = $m['mark'];
            $menu_all[$key]['id']= $m['id'];
            $menu_all[$key]['text']= $m['text'];

        }

        $user_id = Yii::$app->request->get('user_id');
        $bRole = new BusUserPriv();
        $privs  = $bRole ->getUserPriv($user_id);

        $datas['menulist'] = $menu_all;
        $menus = SysMenu::find()
            ->select('id, parent, mark, text, buttons')
            ->indexBy('mark')
            ->asArray()
            ->where("deleted=0 AND installed=1 and parent<>''")
            ->orderBy('parent, sort, mark')
            ->all();

        foreach ($menus as $mk => $menu){
            $mark = $menu['mark'];
            $parent = $menu['parent'];
            $menu['checked'] = (in_array($mark, $privs['menus']) ? 'checked' : '');   // 是否初始化勾选

            if (!empty($menu['buttons'])){
                $id = $menu['id'];
                $btns = explode(';', $menu['buttons']);
                $menu['buttons'] = '';
                $k=0;
                foreach ($btns as $str){
                    $btn = explode(':', $str);
                    if (@in_array($btn[0], $privs['buttons'][$mark])){
                        $check = 'checked';
                    }else{
                        $check = '';
                    }

                    if($btn[1]){
                        $menu['buttons'] .= "<input type='checkbox' name='{$mark}' id='{$parent}{$mark}{$k}' value='{$btn[0]}' {$check}>{$btn[1]}";
                    }
                    $k++;
                }
            }
            $datas['menu'][$parent][] = $menu;
        }*/
//所有主目录
        $menuAll =SysMenu::find()
            ->select('id, parent, mark, text, buttons')
            ->indexBy('mark')
            ->asArray()
            ->where("deleted=0 AND installed=1 AND parent ='B' ")
            ->orderBy('parent, sort, mark')
            ->all();
        $menu_all =array();
        foreach($menuAll as $m){
            $key = $m['mark'];
            $menu_all[$key]['id']= $m['id'];
            $menu_all[$key]['text']= $m['text'];

        }

        //获取该角色已有权限

        $user_id = Yii::$app->request->get('user_id');
        $bRole = new BusUserPriv();
        $privs  = $bRole ->getUserPriv($user_id);
        $datas['menulist'] = $menu_all;
        $model = new SysMenu();
        $menus = $model->menus('B');//查询出所有目录
        $priv = new SysMenu();
        $datas['menu'] = $priv->setCheckButtons($menus,$privs);//所有按钮生成并默认选择
        return $this->renderPartial('setpriv',['datas'=>$datas]);
    }
    /**
     * @name 保存个人权限
     * @return json
     */
    public function actionSavePri(){
        $response = ['success'=>false, 'message'=>''];
        $data = Yii::$app->request->post();
        $model = new BusUserPriv();
        $succ = $model->savePriv($data);
        if ($succ){
            //删除缓存文件 zenglc 2015-08-28
            $runtime =  Yii::getAlias('@app/runtime');
            $buttonFiel = $runtime.'/sysmenu_route_mark.php';
            if(is_file($buttonFiel)){
                unlink($buttonFiel);
            }
            $response['success'] = true;
            $response['message'] = '保存成功';
        }else{
            $response['message'] = '保存失败';
        }
        echo json_encode($response);
        exit;

    }
}
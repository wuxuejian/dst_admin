<?php
namespace backend\modules\parts\controllers;
use backend\controllers\BaseController;
use backend\models\PurchaseOrderMain;
use backend\models\PurchaseExpress;

use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarFault;
use backend\models\CarBrand;
use backend\models\Owner;
use backend\models\OperatingCompany;
use backend\models\Battery;
use backend\models\Motor;
use backend\models\MotorMonitor;
use backend\classes\UserLog;
use common\models\Excel;
use common\models\File;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\Admin;
use common\classes\Category;
use backend\models\CarType;

class PartsKindController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $type_name = isset($_REQUEST['type_name']) ? trim($_REQUEST['type_name']) : '';
            $note = isset($_REQUEST['note']) ? trim($_REQUEST['note']) : '';
            //判断输入配件分类是否存在
            $query = (new \yii\db\Query())
                ->select('a.parts_name,a.id')
                ->where(['a.is_del'=>0,'a.parts_name'=>$type_name,'a.parents_id'=>0])
                ->from('cs_parts_kind as a');
            $dat = $query->one();
            if($dat){
                $msg['status'] = 2;
                $msg['info'] = '输入的配件类别名重复!';
                echo json_encode($msg);die;
            }
            if($type_name==''){
                $msg['status'] = 2;
                $msg['info'] = '您还有数据未填写!';
                echo json_encode($msg);die;
            }
            $kind_name = 'father';
            $result = Yii::$app->db->createCommand()->insert('cs_parts_kind', [
                'parts_name'                   =>trim($type_name),
                'parents_id'                   =>0,
                'note'                         =>trim($note),
                'parents_id_id'                =>0,
                'last_time'                    =>date('Y-m-d H:i:s'),
            ])->execute();
            if($result){
                $msg['status'] = 1;
                $msg['info'] = '添加成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 2;
                $msg['info'] = '添加失败!';
                echo json_encode($msg);die;
            }
        }
        return $this->render('add');
    }

    public function actionAddKind()
    {
        if(yii::$app->request->isPost){
            $parts_name = isset($_REQUEST['parts_name']) ? trim($_REQUEST['parts_name']) : '';
            $kind_name = isset($_REQUEST['kind_name']) ? trim($_REQUEST['kind_name']) : '';
            $note = isset($_REQUEST['note']) ? trim($_REQUEST['note']) : '';
            if( $kind_name =='' or
                $parts_name == '0'
            ){
                $msg['status'] = 2;
                $msg['info'] = '您还有数据未填写!';
                echo json_encode($msg);die;
            }
            $result = Yii::$app->db->createCommand()->insert('cs_parts_kind', [
                'parts_name'                   =>trim($kind_name),
                'parents_id'                   =>trim($parts_name),
                'note'                         =>trim($note),
                'parents_id_id'                =>0,
                'last_time'                    =>date('Y-m-d H:i:s'),
            ])->execute();
            if($result){
                $msg['status'] = 1;
                $msg['info'] = '添加成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 2;
                $msg['info'] = '添加失败!';
                echo json_encode($msg);die;
            }
        }
        $query = (new \yii\db\Query())
            ->select('a.parts_name,a.id')
            ->where(['a.is_del'=>0,'a.parents_id'=>0])
            ->from('cs_parts_kind as a');
        $type_name = $query->all();
        return $this->render('add-kind',['data'=>$type_name]);
    }

    public function actionEdit()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        $parents_id = isset($_REQUEST['parents_id']) ? trim($_REQUEST['parents_id']) : '';
        $data = array();
        $query = (new \yii\db\Query())
            ->select('a.*,b.parts_name as new_name')
            ->from('cs_parts_kind as a')
            ->where(['a.is_del'=>0,'a.id'=>$id])
            ->leftJoin('cs_parts_kind as b','a.parents_id = b.id');
        $data['data'] = $query->all();
        //下拉配件类别
        $query2 = (new \yii\db\Query())
            ->select('a.parts_name,a.id')
            ->where(['a.is_del'=>0,'a.parents_id'=>0])
            ->from('cs_parts_kind as a');
        $data['parts_name'] = $query2->all();
        if(yii::$app->request->isPost){
            $edit_id = isset($_REQUEST['edit_id']) ? trim($_REQUEST['edit_id']) : '';
            $parents_id = isset($_REQUEST['parts_name']) ? trim($_REQUEST['parts_name']) : '';
            $kind_name = isset($_REQUEST['kind_name']) ? trim($_REQUEST['kind_name']) : '';
            $note = isset($_REQUEST['note']) ? trim($_REQUEST['note']) : '';
            if( $parents_id ==''){
                $msg['status'] = 2;
                $msg['info'] = '您还有数据未填写!';
                echo json_encode($msg);die;
            }
            if($kind_name == ''){
                //父级
                $res=Yii::$app->db->createCommand("update cs_parts_kind set parts_name ='". $parents_id ."',note = '". $note ."',
                                                last_time = '". date('Y-m-d H:i:s') ."' where id=".$edit_id)->execute();
            }else{
                //二级
                $res=Yii::$app->db->createCommand("update cs_parts_kind set parents_id =". $parents_id .",
                                                parts_name = '". $kind_name ."',note = '". $note ."',
                                                last_time = '". date('Y-m-d H:i:s') ."' where id=".$edit_id)->execute();
            }
            if($res){
                $msg['status'] = 1;
                $msg['info'] = '修改成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 0;
                $msg['info'] = '修改失败!';
                echo json_encode($msg);die;
            }
        }
        return $this->render('edit',['all'=>$data]);
    }

    public function actionDel()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        $query2 = (new \yii\db\Query())
            ->select('a.parts_name,a.id')
            ->where(['a.is_del'=>'0','a.parents_id'=>$id])
            ->from('cs_parts_kind as a');
        $data = $query2->one();
        if($data){
            $msg['status'] = 0;
            $msg['info'] = '删除失败,此配件类别下面有配件种类!';
            echo json_encode($msg);die;
        }
        $res=Yii::$app->db->createCommand("update cs_parts_kind set is_del ='". 1 ."',
                                                last_time = '". date('Y-m-d H:i:s') ."' where id=".$id)->execute();
        if($res){
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
            echo json_encode($msg);die;
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
            echo json_encode($msg);die;
        }
    }

    public function actionGetList()
    {
        $parts_type = isset($_REQUEST['parts_type']) ? trim($_REQUEST['parts_type']) : '';
        $parts_kind = isset($_REQUEST['parts_kind']) ? trim($_REQUEST['parts_kind']) : '';
        $pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
        $query = (new \yii\db\Query())
            ->select('a.*,b.parts_name as parents_name')
            ->from('cs_parts_kind as a')
            ->where(['a.is_del'=>0])
            ->leftJoin('cs_parts_kind as b','a.parents_id = b.id');
        if($parts_type != ''){
            $query->andFilterWhere(['like','a.parts_name',trim($parts_type)]);
        }
        if($parts_kind != ''){
            $query->andFilterWhere(['like','a.parts_name',trim($parts_kind)]);
        }
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->all();
        $allData=array();
        $allData['rows'] = $data;
        $allData['total'] = $total;
        return json_encode($allData);
    }
}
<?php
/**
 * 充电桩 模型
 */
namespace backend\models;
class ChargeSpots extends \common\models\ChargeSpots
{

    public function rules()
    {
        $rules = [	           
		    [['code_from_compony','code_from_factory','install_site','mark','logic_addr'],
				'trim'
			],
			[['code_from_compony','code_from_factory','install_site','mark','logic_addr'],
				'filter','filter'=>'htmlspecialchars' 
			],

/*          //因为数据表中将删除的记录标记为is_del=1而非真实删除掉,所以这里不能简单以unique判断值唯一性！
            ['code_from_compony','required','message'=>'电桩编号不能为空！'],
			['code_from_compony','unique','message'=>'该电桩编号已经存在！'],
            ['code_from_factory','required','message'=>'出厂编号不能为空！'],
			['code_from_factory','unique','message'=>'该出厂编号已经存在！'],
            ['logic_addr','required','message'=>'逻辑地址不能为空！'],
            ['logic_addr','unique','message'=>'该逻辑地址已经存在！'],
*/
            ['code_from_compony','checkValueValidity','skipOnEmpty'=>false],
            ['code_from_factory','checkValueValidity','skipOnEmpty'=>false],
            //['logic_addr','checkValueValidity','skipOnEmpty'=>false],

            [['charge_gun_nums'],'default','value'=>'0'],
			[['wire_length','rated_output_voltage','rated_output_current','rated_output_power'],
                'default','value'=>'0.00'
            ],
			//[['lng','lat'],'double'],  //经纬度
			//[['lng','lat'],'default','value'=>''],
            [['purchase_date','install_date'],
				'match','pattern'=>'/^20\d{2}-\d{2}-\d{2}$/',
				'message'=>'购置日期或安装日期格式错误！'
			]
        ];
		return array_merge($rules,parent::rules());
    }

    /**
     * 自定义检查某些字段值的合法性
     * 注意：因为数据表中将删除的记录标记为is_del=1而非真实删除掉,所以这里不能简单以unique判断值唯一性！
     */
    public function checkValueValidity($attribute){
        // 自定义要检查哪几个字段
        $checkFieldsInfo = [
            'code_from_compony'=>'电桩编号',
            'code_from_factory'=>'出厂编号',
            //'logic_addr'=>'逻辑地址'
        ];
        $checkFields = array_keys($checkFieldsInfo);
        if (in_array($attribute,$checkFields)) {
             $attrValue = trim($this->$attribute);
            // 检查字段值不能为空
            if($attrValue == ''){
                $this->addError($attribute, $checkFieldsInfo[$attribute].'不能为空！');
                return false;
            }
            // 检查字段值不能重复（要排除掉已标记为删除的记录和当前记录自身！）
            $res = ChargeSpots::find()
                ->select(['id'])
                ->where([
                    $attribute=>$attrValue,
                    'is_del'=>0
                ])
                ->asArray()->one();
            if (!empty($res)) {
                if($res['id'] != $this->id){
                    $this->addError($attribute, '该'.($checkFieldsInfo[$attribute]).'已经存在！');
                    return false;
                }
            }
            return true;
        }
        return false;
    }


    // 关联“充电站表”
    public function getChargeStation()
    {
        return $this->hasOne(ChargeStation::className(), ['cs_id' => 'station_id']);
    }

    // 关联“前置机表”
    public function getChargeFrontmachine()
    {
        return $this->hasOne(ChargeFrontmachine::className(), ['id' => 'fm_id']);
    }

    /**
     * 获取第一条有效数据作为默认的电桩ID（电桩监控模块使用）
     */
    public static function getDefaultChargerId($chargeType = ''){
        $query = ChargeSpots::find()
            ->select(['id'])
            ->where(['is_del'=>0]);
        if ($chargeType) {
            switch ($chargeType) {
                case 'AC' : //交流桩
                    $types = ['AC','AC_AC','AC_DC'];
                    break;
                case 'DC' : //直流桩
                    $types = ['DC','DC_DC','AC_DC'];
                    break;
            }
            $query->andWhere(['in','charge_type',$types]);
        }
        $row = $query->orderBy('id ASC')->asArray()->one();
        if (!empty($row)) {
            return $row['id'];
        } else {
            return 0;
        }
    }


    /**
     * 根据电桩类型与枪数返回电桩的测量点号
     */
    public static function getMeasuringPoint($type = ''){
        switch ($type) {
            case 'DC': //单直流
                return [
                    '单枪'=>8,
                ];
            case 'AC': //单交流
                return [
                    '单枪'=>2,
                ];
            case 'AC_DC': //交直流
                return [
                    'A枪'=>8,
                    'B枪'=>2,
                ];
            case 'DC_DC': //双直流
                return [
                    'A枪'=>8,
                    'B枪'=>4,
                ];
            case 'AC_AC': //双交流
                return [
                    'A枪'=>2,
                    'B枪'=>1,
                ];
        }
        return [];
    }

    /**
     * 根据电桩类型和测量点号获取测量点
     */
    public static function getMeasuringPointNumber($type = '',$point = 0){
        switch ($type) {
            case 'DC': //单直流
                return 3;
            case 'AC': //单交流
                return 1;
            case 'AC_DC': //交直流
                $number = [2 => 1,8 => 3];
                return $number[$point];
            case 'DC_DC': //双直流
                $number = [4 => 2,8 => 3];
                return $number[$point];
            case 'AC_AC': //双交流
                $number = [1 => 0,2 => 1];
                return $number[$point];
        }
        return 0;
    }

	
	/**
     * 根据电桩的“电桩类型”组建好INNER_ID和电枪名称的对应关系（格式：INNER_ID=>枪号）
     */
    public static function getGunName($type = ''){
        switch ($type) {
            case 'DC': //单直流
                return [
					3=>'单枪'
				];
            case 'AC': //单交流
                return [
					1=>'单枪'
				];
            case 'AC_DC': //交直流
				return [
					3=>'A枪',
					1=>'B枪'
				];
            case 'DC_DC': //双直流
				return [
					3=>'A枪',
					2=>'B枪'
				];
            case 'AC_AC': //双交流
				return [
					1=>'A枪',
					0=>'B枪'
				];
			default:
				return [];	
        }
        
    }

    /**
     * 根据电桩类型和枪的测量点号获取枪号[测量点]
     * @param string $type 充电桩类型
     * @param int    $mpn  测量点号
     */
    public static function getGunCodeWithMPN($type = '',$mpn = 0){
        switch ($type) {
            case 'DC': //单直流
                return 3;
            case 'AC': //单交流
                return 1;
            case 'AC_DC': //交直流
                $number = [2 => 1,8 => 3];
                return $number[$mpn];
            case 'DC_DC': //双直流
                $number = [4 => 2,8 => 3];
                return $number[$mpn];
            case 'AC_AC': //双交流
                $number = [1 => 0,2 => 1];
                return $number[$mpn];
        }
        return 0;
    }

    /**
     * 根据电桩类型和枪的测量点获取枪名称
     * @param string $type 充电桩类型
     * @param int    $mpn  测量点号
     */
    public static function getGunNameWithMP($type = '',$mp = 0){
        switch ($type) {
            case 'DC': //单直流
                return 'A枪';
            case 'AC': //单交流
                return 'A枪';
            case 'AC_DC': //交直流
                $number = [3 => 'A枪',1 => 'B枪'];
                return $number[$mp];
            case 'DC_DC': //双直流
                $number = [3 => 'A枪',2 => 'B枪'];
                return @$number[@$mp];
            case 'AC_AC': //双交流
                $number = [1 => 'A枪',0 => 'B枪'];
                return $number[$mp];
        }
        return '';
    }


	
	
}

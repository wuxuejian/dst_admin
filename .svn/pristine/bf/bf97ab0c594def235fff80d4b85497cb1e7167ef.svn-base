<?php
/**
 * 文件图片上传类
 */
namespace backend\classes;

class MyUploadFile{
	
    /**
     * 功能：文件上传的具体处理
     * @$myFile，指接收上传文件的超全局数组$_FILES['yourCustomName']；
     * @$storeDirName，指定存放文件的目录名称；
     * @$filePrefix，上传文件后加上的文件前缀。
     */
    public function handleUploadFile($myFile,$storeDirName='',$filePrefix='')
	{
        $fileName = $myFile["name"]; 		// 被上传文件的名称
        $fileType = $myFile["type"]; 		// 被上传文件的类型，image/jpeg等
        $fileSize = $myFile["size"]; 		// 被上传文件的大小，以字节计
        $fileTmpName = $myFile["tmp_name"]; // 存储在服务器的文件的临时副本的名称
        $fileError = $myFile["error"]; 		// 由文件上传导致的错误代码,0表示正常
		
		$datas = [];
        if($fileError > 0){
            switch($fileError) {
                case 1:     // 超过了配置文件限制的上传文件的大小 upload_max_filesize
                case 2:     // 超过了表单限制的上传文件的大小 MAX_FILE_SIZE
                    $errorMsg = "上传文件太大了！";
                    break;
                case 3:
                    $errorMsg = "文件只上传了部分！";
                    break;
                case 4:
                    $errorMsg = "没有上传任何文件！";
                    break;
                default:
                    $errorMsg = "上传时发生了错误！";
            }
            $datas['error'] = 1;
            $datas['msg'] = $errorMsg;
            return $datas; 
        }
		
        // 判断上传的文件类型
        list($mainType,$subType) = explode("/", $fileType);
        if($mainType == "image"){
            $allowedExts = ["jpeg", "jpg", "png","gif"];
            if(!in_array($subType,$allowedExts)){
                $datas['error'] = 1;
                $datas['msg'] = '仅支持' . implode(',',$allowedExts) . '图片类型！';
                return $datas;
            }
        }
		
        // 处理上传文件的储存路径，这里指定在与入口文件同级的uploads目录之下。
        $storePath = 'uploads/';
        if(!is_dir($storePath)){
            mkdir($storePath);
        }
		$storePath .= $mainType . '/'; 
        if(!is_dir($storePath)){
            mkdir($storePath);
        }
        if($storeDirName){
            $storePath .=  $storeDirName . '/';
        }
        if(!is_dir($storePath)){
            mkdir($storePath);
        }
		$storePath .= date('Ymd') . '/';
        if(!is_dir($storePath)){
            mkdir($storePath);
        }
		$fileName = $filePrefix . uniqid(). '.'. $subType;
        $uploadFile = $storePath . $fileName; // $uploadFile最后形式: uploads/image/jiaZhao/20151220/vip1_e32s1233d24.png
        if(is_uploaded_file($fileTmpName)){
            if(move_uploaded_file($fileTmpName,$uploadFile)){
                $datas['error'] = 0;
                $datas['msg'] = '文件上传成功！';
                $datas['filePath'] = $uploadFile; 
                $datas['mainType'] = $mainType; 
                $datas['subType'] = $storeDirName;
            }else{
                $datas['error'] = 1;
                $datas['msg'] = '没有将文件上传到规定目录！';
            }
        }else{
            $datas['error'] = 1;
            $datas['msg'] = '不是上传的文件！';
        }
		
        return $datas;
    }
	
	
}
<?php
class PackSendCarData{
    /*public static $config = [
        //车辆数据存放地址
        'cacheDir'     =>  'D:/CacheData',
        //打包文件临时存放地址
        'tmpDir'       =>  'D:/CacheDataPackage',
        //打包多少天前的日志
        'day'          =>  30,
        //ftp配置
        'ftp_host'     =>  '192.168.96.92',
        'ftp_port'     =>  9310,
        'ftp_timeout'  =>  10,
        'ftp_username' => 'szdst',
        'ftp_password' => 'szdst123',
    ];*/
    public static $config = [
        //车辆数据存放地址
        'cacheDir'     =>  '/mnt/CarDataLog',
        //打包文件临时存放地址
        'tmpDir'       =>  '/mnt/CarDataLogPackage',
        //打包多少天前的日志
        'day'          =>  30,
        //ftp配置
        'ftp_host'     =>  'dstzc.wicp.net',
        'ftp_port'     =>  12503,
        'ftp_timeout'  =>  10,
        'ftp_username' => 'cardatasubmit',
        'ftp_password' => '',
    ];

    protected $zipObj;
    protected $zipFileName;
    protected $fileTimeLimit = 0;

    public function __construct(){
        date_default_timezone_set('PRC');
        set_time_limit(0);
        self::$config['cacheDir'] = str_replace('\\','/',self::$config['cacheDir']);
        self::$config['cacheDir'] = rtrim(self::$config['cacheDir'],'/');
        self::$config['tmpDir'] = str_replace('\\','/',self::$config['tmpDir']);
        self::$config['tmpDir'] = rtrim(self::$config['tmpDir'],'/');
        self::$config['day'] = intval(self::$config['day']);
        //zip文件名
        $this->zipFileName = self::$config['tmpDir'].'/'.date('YmdHis').'.zip';
        //文件时间节点
        $this->fileTimeLimit = date('YmdH',strtotime('-'.self::$config['day'].' day'));
    }

    public function start(){
        $this->zipObj = new \ZipArchive();
        $this->zipObj->open($this->zipFileName, \ZIPARCHIVE::CREATE);
        echo 'scaning files before ',self::$config['day'],' days...',"\n";
        $this->readFileAddToPack(self::$config['cacheDir']);
        echo 'packing files before ',self::$config['day'],' days...',"\n";
        $this->zipObj->close();
        if(file_exists($this->zipFileName)){
            echo 'file pack success',"\n";
            echo 'deleting packed files...',"\n";
            $this->delPackedFile(self::$config['cacheDir']);
            //上传文件到ftp服务器
            echo 'upload zip file to :',self::$config['ftp_host'],':',self::$config['ftp_port'],"\n";
            for($i = 0;$i <= 3;$i++){
                if($this->ftpUpload()){
                    echo 'all action success',"\n";
                    break;
                }else{
                    echo 'zip file upload fail',"\n";
                }
            }
        }else{
            echo 'file pack fail',"\n";
        }
    }

    /**
     * 读取并添加到压缩文件中
     */
    public function readFileAddToPack($dir){
        $dh = opendir($dir);
        while($file = readdir($dh)){
            if($file == '.' || $file == '..'){
                continue;
            }
            if(is_dir($dir.'/'.$file)){
                $this->readFileAddToPack($dir.'/'.$file);
            }else{
                $fileTime = substr($file,0,10);
                if($fileTime < $this->fileTimeLimit){
                    //指定时间以前的文件打包
                    $targetName = str_replace(self::$config['cacheDir'].'/','',$dir.'/'.$file);
                    //echo $dir.'/'.$file,"\n";
                    $this->zipObj->addFile($dir.'/'.$file,$targetName);
                }
            }
        }
        closedir($dh);
    }

    /**
     * 删除已经备份的文件
     */
    public function delPackedFile($dir){
        $dh = opendir($dir);
        while($file = readdir($dh)){
            if($file == '.' || $file == '..'){
                continue;
            }
            if(is_dir($dir.'/'.$file)){
                $this->delPackedFile($dir.'/'.$file);
            }else{
                $fileTime = substr($file,0,10);
                if($fileTime < $this->fileTimeLimit){
                    //指定时间以前的文件打包
                    unlink($dir.'/'.$file);
                }
            }
        }
        closedir($dh);
    }

    /**
     * 上传文件到ftp服务器
     */
    protected function ftpUpload(){
        $fh = @ftp_connect(self::$config['ftp_host'],self::$config['ftp_port'],self::$config['ftp_timeout']);
        if(!$fh){
            //echo 'can not open ftp ',self::$config['ftp_host'],':',self::$config['ftp_port'],"\n";
            return false;
        }
        if(!@ftp_login($fh,self::$config['ftp_username'],self::$config['ftp_password'])){
            ftp_close($fh);
            //echo 'ftp login fail!',"\n";
            return false;
        }
        ftp_pasv($fh ,true);//使用被动模式
        //阻塞的二进制文件上传
        //echo '/'.basename($uploadFile);
        if(ftp_put($fh,basename($this->zipFileName),$this->zipFileName,FTP_BINARY)){
            //echo 'ftp file upload success!',"\n";
            ftp_close($fh);
            return true;
        }else{
            //echo 'ftp file upload fail!',"\n";
            ftp_close($fh);
            return false;
        } 
    }
}
$c = new PackSendCarData;
$c->start();
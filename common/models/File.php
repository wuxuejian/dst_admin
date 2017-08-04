<?php
namespace common\models;
class File{
	
	////////////*****统计类方法开始*****//////////////
	/**
     * desc: 统计一个目录下的文件和目录个数的入口方法
     *       主要为清除静态成员的缓存值否则重复调用该
     *       方法统计数据将不准确
     *       参数说明见_dirCount方法
     */
    private static $dirCountDirNum = 0;
    private static $dirCountFileNum = 0;
    public static function dirCount($dir,$recursion = false)
    {
        self::$dirCountDirNum = 0;
        self::$dirCountFileNum = 0;
        return self::_dirCount($dir,$recursion);
    }
    /**
     * desc:统计一个目录下的文件和目录个数
     * @param  string  $dir       要统计的目录
     * @param  boolean $recursion 是否要递归统计
     * @return array              0=>目录数量 1=>文件数量
     */
    private static function _dirCount($dir,$recursion)
    {
        $dir = rtrim($dir,'\\/');
        if(!is_dir($dir)) return [];
        $handle = opendir($dir);
        while (false !== ($name = readdir($handle))) {
            if($name != '.' && $name != '..'){
                $currentName = $dir.DIRECTORY_SEPARATOR.$name;
                if(is_dir($currentName)){
                    self::$dirCountDirNum ++;
                    if($recursion) self::_dirCount($currentName,$recursion);
                }elseif(is_file($currentName)){
                    self::$dirCountFileNum ++;
                }
            }
        }
        closedir($handle);
        return [self::$dirCountDirNum,self::$dirCountFileNum];
    }
    ////////////*****统计类方法结束*****//////////////
    /**
     * desc:检索整个目录包括目录下的所有目录和文件
     *      返回结果为utf-8格式数据如需gbk编码请用iconv函数处理
     * @param  string  $dir       要检索的目录
     * @param  boolean $recursion 是否要递归处理
     * @param  string  $except    要排除的目录和文件的正则表达示目录分隔符‘/’
     * @return array              如果检索的目录不存在将返回空数据
     *                            目录所包含的目录与文件的数组
     */
    private static $dirScanAllResult = [];//存放检索结果
    public static function dirScanAll($dir,$recursion = false,$except = '')
    {
        $dir = rtrim(str_replace(['/','\\'],'/',$dir),'/');
        if(!is_dir($dir)) return [];
        self::$dirScanAllResult = [];
        return self::_dirScanAll($dir,$recursion,$except);
    }
    protected static function _dirScanAll($dir,$recursion = false,$except = '')
    {
        $handle = opendir($dir);
		$name = readdir($handle);
        while ( $name !== false ) {
            if($name != '.' && $name != '..'){
                $currentName = $dir.'/'.$name;
                if( empty($except) || !preg_match($except,$currentName) ){
                    self::$dirScanAllResult[] = iconv('gbk','utf-8//IGNORE',$currentName);
                    if(is_dir($currentName) && $recursion) self::_dirScanAll($currentName,true,$except);
                } 
            }
			$name = readdir($handle);
        }
        closedir($handle);
        return self::$dirScanAllResult;
    }

    /**
     * desc:目录比较，将源目录中的文件（包括目录）与目标目录中的文件相比较如果
     * @param  string   $source   源目录
     * @param  string   $target   目标目录
     * @param  array    $except   要排除的文件和目录数组
     * @param  bool     $make     如果文件不一致是否要在目标目录中创建源目录中的文件
     * @param  variable &$missing 引用传递目标目录中缺失的文件
     * @return array              如果源目录和目标目录中任意一个参数不是有效的目录将返回空数组
     *                            成功后返回源目录中的目录目录不存在或不同的目录和文件
     */
    public static function dirCompare($source,$target,$except = '',$make = false,&$missing = [])
    {
        $source = rtrim(str_replace(['/','\\'],'/',$source),'/');
        $target = rtrim(str_replace(['/','\\'],'/',$target),'/');
        if(!is_dir($source) || !is_dir($target)) return [];
        $difference = [];//存放目标目录中缺失或和源目录不相同的目录和文件
        $sourceFile = self::dirScanAll($source,true,$except);//获取源目录中的所有文件和目录
        if($sourceFile){
            foreach($sourceFile as $file){
                $file = iconv('utf-8','gbk',$file);//文件比对是必须是gbk编码
                $targetFileName = substr_replace($file,$target,0,strlen($source));
                if(is_dir($file)){
                    //源目录中是目录
                    if(!is_dir($targetFileName)){
                        $missing[] = iconv('gbk','utf-8',$file);//返回为utf-8格式
                        $difference[] = iconv('gbk','utf-8',$file);//返回为utf-8格式
                        !$make or mkdir($targetFileName,'0755');
                    }
                }else{
                    //源目录中是文件
                    if(!file_exists($targetFileName)){
                        $difference[] = iconv('gbk','utf-8',$file);//返回为utf-8格式
                        $missing[] = iconv('gbk','utf-8',$file);//返回为utf-8格式
                        !$make or copy($file,$targetFileName);
                    }elseif(sha1_file($file) !== sha1_file($targetFileName)){
                        $difference[] = iconv('gbk','utf-8',$file);//返回为utf-8格式
                        !$make or copy($file,$targetFileName);
                    }
                }
            }
        }
        return $difference;
    }

    /**
     * desc:压缩指定目录
     * @param  string $sourcePath 要打包的目录
     * @param  string $outZipPath 输出路径
     * @return null
     */
    public static function dirZip($sourcePath, $outZipPath)
    {
        $pathInfo = pathInfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];
    
        $z = new \ZipArchive();
        $z->open($outZipPath, \ZIPARCHIVE::CREATE);
        $z->addEmptyDir($dirName);
        self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        $z->close();
    }
    private static function folderToZip($folder, &$zipFile, $exclusiveLength)
    {
        $handle = opendir($folder);
        $f = readdir($handle);
        while (false !== $f) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
            $f = readdir($handle);
        }
        closedir($handle);
    }

    /**
     * 添加指定文件到压缩包
     */
    public static function filesToZip($files,$zipPath)
    {
        $zipPath = str_replace('\\/','/',$zipPath);
        if(empty($zipPath)){
            throw new \Exception('param zipPath can not be blank!');
        }
        if(!is_dir(dirname($zipPath))){
            throw new \Exception('dirname of zipPath is not a dir!');
        }
        $z = new \ZipArchive();
        $z->open($zipPath, \ZIPARCHIVE::CREATE);
        //$localPath = substr($filePath, $exclusiveLength);
        foreach($files as $val){
            if(file_exists($val)){
                if($pos = strrpos($val,'/')){
                    $fileName = substr($val,$pos+1);
                }else{
                    $fileName = $val;
                }
                $z->addFile($val,$fileName);
            }
        }
        $z->close();
        return true;
    }

    /**
     * desc:删除一个目录的所有内容
     * @param string   $dir 要删除的目录
     * @return boolean      成功返回true失败返回false
     */
    public static function dirDel($dir)
    {
        if(!is_dir($dir)) return false;
        $dir = rtrim(str_replace(['/','\\'],'/',$dir),'/');
        $handler = opendir($dir);
        $name = readdir($handler);
        while( $name !== false ){
            if($name != '.' && $name != '..'){
                if(is_file($dir.'/'.$name))
                    unlink($dir.'/'.$name);
                else
                    self::dirDel($dir.'/'.$name);
            }
            $name = readdir($handler);
        }
        closedir($handler);
        return rmdir($dir);
    }

    /**
     * desc:拷贝一个目录到目标目录
     * @param  string  $copyDir   将要被拷备的目录
     * @param  string  $targetDir 目标目录
     * @param  string  $except    【正则表达式】要排除的目录或文件
     * @return mixed              有错误返回错误处理失败的文件和目录否则返true 
     */
    protected static $dirCopyError = [];
    public static function dirCopy($copyDir,$targetDir,$except = ''){ 
        $copyDir = rtrim(str_replace(['/','\\'],'/',$copyDir),'/');
        $targetDir = rtrim(str_replace(['/','\\'],'/',$targetDir),'/');
        if(!is_dir($copyDir) || !is_dir($targetDir)) return false;
        self::$dirCopyError = [];
        self::_dirCopy($copyDir,$targetDir,$except);
        if(self::$dirCopyError) return self::$dirCopyError;
        return true;
    }
    public static function _dirCopy($copyDir,$targetDir,$except = '')
    {
        $handler = opendir($copyDir);
        $name = readdir($handler);
        while( $name !== false ){
            if($name != '.' && $name != '..'){
                if(empty($except) || !preg_match($except,iconv('gbk','utf-8',$copyDir.'/'.$name))){
                    if(is_file($copyDir.'/'.$name)){
                        if(!@copy($copyDir.'/'.$name,$targetDir.'/'.$name)){
                            self::$dirCopyError[] = $copyDir.'/'.iconv('gbk','utf-8',$name);
                        }
                    }else{
                        if(!is_dir($targetDir.'/'.$name) && !@mkdir($targetDir.'/'.$name)){
                            self::$dirCopyError[] = $copyDir.'/'.iconv('gbk','utf-8',$name);
                        }else{
                            self::_dirCopy($copyDir.'/'.$name,$targetDir.'/'.$name,$except);
                        }
                    }
                }
            }
            $name = readdir($handler);
        }
        closedir($handler);
    }

    /**
     * 下载指定文件
     */
    public static function fileDownload($filePath)
    {
        header("Content-type:text/html;charset=utf-8");
        $filePath = str_replace('\\/','/',$filePath);
        $fileName = substr($filePath,strrpos($filePath,'/')+1);
        //用以解决中文不能显示出来的问题 
        //首先要判断给定的文件存在与否 
        if(!file_exists($filePath)){ 
            echo "指定文件不存在！"; 
            return false; 
        } 
        $fp = fopen($filePath,"r"); 
        $fileSize = filesize($filePath); 
        //下载文件需要用到的头 
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".$fileName); 
        $buffer = 1024; 
        $readSize = 0; 
        //向浏览器返回数据 
        while(!feof($fp) && $readSize < $fileSize){ 
            echo fread($fp,$buffer);
            $readSize += $buffer;  
        } 
        fclose($fp); 
    }
}
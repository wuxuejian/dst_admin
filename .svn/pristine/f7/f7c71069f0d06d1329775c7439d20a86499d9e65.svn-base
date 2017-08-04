<?php
$ftp_info = [
    //'host'     =>  'dstzc.wicp.net',
    //'port'     =>  12503,
    'host'     =>  '192.168.96.92',
    'port'     =>  9310,
    'timeout'  =>  10,
    'username' => 'szdst',
    'password' => 'szdst123',
];
$uploadFile = './test.zip';
function ftpUpload($ftp_info,$uploadFile){
    $fh = @ftp_connect($ftp_info['host'],$ftp_info['port'],$ftp_info['timeout']);
    if(!$fh){
        echo "can not open {$ftp_info['host']}:{$ftp_info['port']}\n";
        return false;
    }
    if(!@ftp_login($fh,$ftp_info['username'],$ftp_info['password'])){
        ftp_close($fh);
        echo 'login fail!',"\n";
        return false;
    }
    ftp_pasv($fh ,true);//使用被动模式
    //阻塞的二进制文件上传
    //echo '/'.basename($uploadFile);
    if(ftp_put($fh,$uploadFile,$uploadFile,FTP_BINARY)){
        echo 'file upload success!',"\n";
        ftp_close($fh);
        return true;
    }else{
        echo 'file upload fail!',"\n";
        ftp_close($fh);
        return false;
    } 
}
$tryNum = 3;
do{
    echo 'times: ',(4 - $tryNum),"\n";
    if(!ftpUpload($ftp_info,$uploadFile)){
        $tryNum --;
    }else{
        break;
    }
}while($tryNum > 0);
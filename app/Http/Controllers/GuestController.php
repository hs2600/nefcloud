<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
// use Aws\Exception\AwsException;
// use Aws\S3\ObjectUploader;
// use Aws\S3\MultipartUploader;
// use Aws\Exception\MultipartUploadException;    
use Aws\DynamoDb\DynamoDbClient;
// use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    
    public function awsSetup($id){

        if(session()->get('authenticated')){
            $user_sub = session()->get('sub');
        } else {
            $user_sub = 'not_authorized';
        } 
        $authenticated = session()->get('authenticated');
        $email = session()->get('email');

        $sharedConfig = [
            //'profile' => 'default',
            'region' => 'us-west-1',
            'version' => 'latest'
        ];
       
        $s3Client = new S3Client($sharedConfig);
        $dynamodb = new DynamoDbClient($sharedConfig);        
        $marshaler = new Marshaler();
        $tableName_files = 'nefcloud_files';
        $tableName_keys = 'nefcloud_file_keys';
        $tableName_shares = 'nefcloud_shares'; 
        $tableName_guest_shares = 'nefcloud_guest_shares';
        
        $bucket = 'nefcloud.com';
        $s3url = 'https://f.nefcloud.com';    
        $share_dir = '';
        $obj_prefix = 'UserStorage/';
  
        $dir = '';
        $bc_dir = '';
        if(isset($_GET["dir"])){
            $dir = $_GET["dir"];
            $dir = base64_decode($_GET["dir"]);
            $dir = array_filter(explode("/",$dir));
            $dir = implode('/',$dir);
            $bc_dir = $dir; //to use in breadcrums
            $dir_enc = base64_encode($dir); // to hide directory as base64
        }

        $total_size = 0;
        if(isset($_COOKIE['st_info'])){
            $total_size = $_COOKIE['st_info'];
        }

        $storage_total = 20; //in GB
        $gb = sprintf("%.2f", $total_size / pow(1024, 3));
        $used_percent = sprintf("%.2f", $gb / $storage_total) * 100;

        $sz = 'BKMGT';
        if($total_size < 1024){
            $st_total = $total_size. ' bytes';
        } else {
            $factor_total = floor((strlen($total_size) - 1) / 3);
            $st_total = sprintf("%.2f", $total_size / pow(1024, $factor_total)) . ' ' . @$sz[$factor_total].'B';
        }
    
        $storage_used = $st_total;

        $var_array = [
            'authenticated' => $authenticated,
            'user_sub' => $user_sub,
            'email' => $email,
            'storage_used' => $storage_used,
            'storage_total' => $storage_total,
            'used_percent' => $used_percent,
            'total_size' => $total_size,
            'bc_dir' => $bc_dir,
            'dir' => $dir,
            's3Client' => $s3Client,
            'dynamodb' => $dynamodb,
            'marshaler' => $marshaler,
            'bucket' => $bucket,
            'obj_prefix' => $obj_prefix,
            'share_dir' => $share_dir,
            'tableName_files' => $tableName_files,
            'tableName_keys' => $tableName_keys,
            'tableName_shares' => $tableName_shares,
            'tableName_guest_shares' => $tableName_guest_shares,
            's3url' => $s3url,
            'id' => $id
        ];

        return $var_array;
    }

    public function share($share_id){
        return view('pages.share.share', $this->awsSetup($share_id));
    }

    public function guestupload(){
        return view('inc.upload_guest', $this->awsSetup(''));
    }    

}

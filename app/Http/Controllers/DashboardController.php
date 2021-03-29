<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
// use Aws\S3\ObjectUploader;
// use Aws\S3\MultipartUploader;
// use Aws\Exception\MultipartUploadException;    
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{

    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $authenticated = session()->get('authenticated');
    //     if($authenticated != true){
    //         $this->middleware('auth');
    //     }
    // }

    public function awsSetup(){

        if(session('authenticated')){
            $user_sub = session('sub');
        } else {
            $user_sub = 'not_authorized';
        } 
        $authenticated = session('authenticated');
        $email = session('email');
        // $user_sub = session()->get('sub');
       
        $sharedConfig = [
            //'profile' => 'default',
            'region' => 'us-west-1',
            'version' => 'latest'
        ];
       
        $s3Client = new S3Client($sharedConfig);
        $dynamodb = new DynamoDbClient($sharedConfig);        
        // $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();
        // $tableName = 'nefcloud_files';
        $tableName_files = 'nefcloud_files';
        $tableName_keys = 'nefcloud_file_keys';
        $tableName_shares = 'nefcloud_shares'; 
        
        $bucket = 'nefcloud.com';
        $obj_prefix = 'UserStorage/';
        $s3url = 'https://f.nefcloud.com';    
        
        $authenticated = true;
        $user_sub = '26ac4c01-c47d-4125-afc5-5747cb99d7d8';
        $share_dir = '';
    
        $dir = '';
        $bc_dir = '';
        if(isset($_GET["dir"])){
            $dir = $_GET["dir"];
            $dir = base64_decode($dir);
            $dir = preg_replace('/[\x00-\x1F\x7F]/u', '', $dir);
            $dir = array_filter(explode("/",$dir));
            $dir = implode('/',$dir);
            $bc_dir = $dir; //to use in breadcrums
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
    
        // $user_sub = '26ac4c01-c47d-4125-afc5-5747cb99d7d8';

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
            's3url' => $s3url
        ];

        return $var_array;
    }

    public function dashboard(){
        return view('pages.dashboard.dashboard', $this->awsSetup());
    }
    public function favorites(){
        return view('pages.dashboard.favorites', $this->awsSetup());
    }    
    public function trash(){
        return view('pages.dashboard.trash', $this->awsSetup());
    }
    public function sharein(){
        return view('pages.dashboard.sharein', $this->awsSetup());
    }
    public function shareout(){
        return view('pages.dashboard.shareout', $this->awsSetup());
    }
    public function sharelink(){
        return view('pages.dashboard.sharelink', $this->awsSetup());
    }    

    public function ajaxFav(){
        return view('pages.dashboard.ajax.favorites', $this->awsSetup());
    }

    public function ajaxDel(){
        return view('pages.dashboard.ajax.trash', $this->awsSetup());
    }

    public function ajaxHom(){
        return view('pages.dashboard.ajax.dashboard', $this->awsSetup());
    }    

    public function ajaxRecent(){
        return view('pages.dashboard.ajax.recent', $this->awsSetup());
    }

    public function ajaxTags(){
        return view('pages.dashboard.ajax.tags', $this->awsSetup());
    }  

    public function upload(Request $request){
        return view('inc.upload', $this->awsSetup());
    }  

    public function flagUpdate(){
        return view('pages.dashboard.ajax.flag_update', $this->awsSetup());
    }      

    // public function guestupload(){
    //     return view('inc.upload_guest', $this->awsSetup());
    // }

}

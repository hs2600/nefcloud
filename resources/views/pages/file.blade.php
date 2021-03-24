<?php

require '../vendor/autoload.php';
 
use Aws\Exception\AwsException;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$marshaler = new Marshaler();

$sharedConfig = [
    'region' => 'us-west-1',
    'version' => 'latest'
];

$s3Client = new S3Client($sharedConfig);
$dynamodb = new DynamoDbClient($sharedConfig);  
$tableName_keys = 'nefcloud_file_keys';
$tableName_files = 'nefcloud_files';
// $s3url = 'https://f.nefcloud.com';
$bucket = 'nefcloud.com';
// $user_sub = '26ac4c01-c47d-4125-afc5-5747cb99d7d8';
// $file_id = 'eff01938da4c4125842a5b0f';

$fid = '';
$r = '';
$file_name = '';
$presignedUrl = '';
$presignedUrl_inline = '';
$file_type = '';
$file_size = 0;
$st = '';
// $file_url = '';
$error = '';
$created = '';
if(isset($_GET['r'])){
    $r = base64_decode($_GET['r']);
}

if(isset($_GET['fid'])){
    $fid = $_GET['fid'];
    $file_id = $fid;

    //Return item from keys table
    $result = $dynamodb->getItem([
        'Key' => [
            'FileID' => [
                'S' => $file_id
            ],
        ],
        'TableName' => $tableName_keys,
    ]);

    if(isset($result["Item"])){
        $item_key = $marshaler->unmarshalItem($result["Item"]);
        $user_sub = explode('/',$item_key['Key'])[0];
        $key = $item_key['Key'];

        //Return item from files table
        $result = $dynamodb->getItem([
            'Key' => [
                'UserID' => [
                    'S' => $user_sub
                ],
                'Key' => [
                    'S' => $key
                ],            
            ],
            'TableName' => $tableName_files,
        ]);

        $item = $marshaler->unmarshalItem($result["Item"]);
    
        // print_r($item);

        // $key_split = explode('/',$item["Key"]);
        // $file_name = implode('',array_slice($key_split, -1));
        $created = $item["Created"];
        // $file_url = $s3url.'/'.$item["Key"];
        $file_type = $item["Type"];

        if($file_type == 'dir'){
          $error = 'Incorrect file!';
        } else {
          $file_name = $item["FileName"];
          $file_size = $item["Size"];
        }
        // $file_id = $item["FileID"];

        $timeout = '30';
        $dist_type = 'attachment;filename='.$file_name;
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => 'UserStorage/'.$item['Key'],
            'ResponseServerSideEncryption' => 'AES512',
            'ResponseContentDisposition' => $dist_type
        ]);

        // Get the actual presigned-url
        // attachment
        $request = $s3Client->createPresignedRequest($cmd, "+$timeout minutes");
        $presignedUrl = (string)$request->getUri();

        //inline
        $dist_type = 'inline';
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => 'UserStorage/'.$item['Key'],
            'ResponseContentDisposition' => $dist_type
        ]);
        $request = $s3Client->createPresignedRequest($cmd, "+$timeout minutes");
        $presignedUrl_inline = (string)$request->getUri();
        // echo $presignedUrl_inline;

        $sz = 'BKMGTP';
        if($file_size < 1024){
            $st = $file_size. ' bytes';
        } else {
            $factor = floor((strlen($file_size) - 1) / 3);
            $st = sprintf("%.2f", $file_size / pow(1024, $factor)) . ' '. @$sz[$factor]. 'B';
        }    

    } else {
        $error = 'Incorrect file!';
    }

}
else {
        $error = 'Incorrect file!';
    }

?>

<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>NefCloud.com - {{ $file_name }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link href="/assets/css/app.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/app-animate.back.css">
  <link href="/assets/font-awesome/css/all.css" rel="stylesheet">    
  <link href="/assets/css/index.web-vfl_z8r43.css" rel="stylesheet">  

<style>  

div.bw {
    word-wrap: break-word;
}

div.s-auto {  
    overflow: auto;
    height: 100vh;
}

div.s-hidden {  
    overflow: hidden;
    height: 100vh;
 }

.vh100 {  
    height: 93vh;
 }

pre {
	width: 100%;
	padding: 0;
  margin: 0;
  padding-left: 35px;
  padding-top: 13px;
	overflow: auto;
	overflow-y: hidden;
	font-size: 12px;
	line-height: 16px;
	background: #999;
  border-bottom: 1px solid #ccc;  
  background: url(/assets/images/2cOaJ.png) no-repeat;
}

@media (max-width: 992px) {                  
  .dnone {
    display: none;
  }
} 

</style>

</head>
<body cz-shortcut-listen="true" style="background-color: #F7F9FA;">

<?php

    if($error != ''){
    ?>

    <div class="container">
        <div class="row">

        <p>&nbsp;</p>
        <center>
        <img src="/assets/images/monkey.png">
        <br>
        <span class="display-3">Ooops! We can't find that file.</span><p>&nbsp;</p>
        
        @php
        if($r == ''){
            echo "<a href=\"javascript:history.go(-1)\" class=\"btn btn-sm btn-info\">";
        }else{
            echo '<a href="'.$r.'" class="btn btn-sm btn-info">';
        }
       @endphp
        Go Back</a>
        </center>
        </div>
    </div>  
    <?php
    } else {

?>    
  
  <div class="container-fluid">
    <div class="row s-hidden">
      <div class="col s-auto">
        <div class="row sticky-top pt-3" style="background-color: F7F9FA; opacity: 0.85; border-bottom: 1px solid #ccc; border-top: 2px solid #45b7f8;">
          <div class="col-1 pl-3" style="border: 0px solid green;">
            @php
            if($r == ''){
                 echo "<a href=\"javascript:history.go(-1)\">";
            }else{
                 echo '<a href="'.$r.'">';
            }
            @endphp
            <span class="p-0" data-balloon="Back" data-balloon-pos="down">
                <i class="fas fa-chevron-left" style="font-size: 20px;"></i>
            </span>
            </a>
          </div>
          <div class="col bw p-0 text-center">
            <h6 class="">
            {{ $file_name }} 
                <a href="{{ $presignedUrl_inline }}">
                  <i class="fas fa-external-link-alt" style="font-size: 20px;"></i></a>                
            </h6>
          </div>
          <div class="dnone col-1 text-right" style="">
            <span class="px-3 relative" data-balloon="Info" data-balloon-pos="down">
            <a data-bs-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
                <i class="fas fa-info-circle" style="font-size: 20px;">&nbsp;</i>
            </span>
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col p-0" style="display: flex; justify-content: center; border: 0px solid red;">
            <?php
                if($file_type == 'mp3'){
                    echo '<audio src="'.$presignedUrl_inline.'" controls="controls">';
                    echo 'Your browser does not support the audio element.';
                    echo '</audio>';
                } else if(preg_match("/\.(mp4)$/", strtolower($file_name))) {                                
                    echo '<video src="'.$presignedUrl_inline.'" controls width="100%">';
                    echo 'Your browser does not support the video element.';
                    echo '</video>';
                } else if(preg_match("/\.(gif|jpg|png|jpeg)$/", strtolower($file_name))) {
                    // echo '<img src="'.$presignedUrl_inline.'" width="100%"><br>';
                    echo '<img src="'.$presignedUrl.'"><br>';
                } else if($file_type == 'pdf'){
                    echo '<embed class="vh100" src="'.$presignedUrl_inline.'#toolbar=1" width="100%">';
                    // echo '<iframe src="https://docs.google.com/gview?url='.$presignedUrl_inline.'" width="100%" height="565px" frameborder="0" src="http://www.google.com"></iframe>';
                      //echo '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src='.$presignedUrl_inline.'" width="100%" height="565px" frameborder="0" src="http://www.google.com"></iframe>';
                    //echo '<embed src="https://s3.amazonaws.com/Horacio/KPAE+13-1.pdf#toolbar=1" width="100%" height="100%">';
                } else if($file_type == 'docx' || $file_type == 'xlsx' || $file_type == 'pptx'){
                    // echo '<iframe class="vh100" src="https://view.officeapps.live.com/op/embed.aspx?src='.$presignedUrl_inline.'" width="100%" frameborder="0"> </iframe>';
                    echo '<iframe class="vh100" src="https://docs.google.com/viewer?embedded=true&url='.urlencode($presignedUrl_inline).'" width="100%" frameborder="0"> </iframe>';
                } else if(preg_match("/(txt|dat|java|php|asm|py|json|css)$/", strtolower($file_type))) {
                // Download the contents of the object.
                $result = $s3Client->getObject([
                    'Bucket' => $bucket,
                    'Key' => 'UserStorage/'.$key
                ]);
                echo '<pre style="white-space: pre-wrap; white-space: -moz-pre-wrap; word-wrap: break-word;">';
                echo htmlspecialchars($result['Body']);
                echo '</pre>';
                } else {
                    echo '<div class="pt-5 text-center">';
                    echo '<i class="fas fa-exclamation-circle gray6 py-5" style="font-size: 50px;"></i><br>';
                    echo '<span class="display-6">
                          Preview not available for this file type.</span><p>&nbsp;</p>';
                    echo '</div>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="dnone col-2 pt-5 collapse show" id="collapseInfo" style="border: 1px solid #ccc; background-color: white; min-width: 300px;">
        <div class="bw"><h4>{{ $file_name }}</h4></div>
          <div class="pt-3"><b>Details</b>
          </div>
            <div class="pt-3">
              <div class="">Uploaded
                </div>
              <div class=""><?php
                $date_array = explode(".",$created);
                $date = date("m/d/y, h:i a", strtotime($date_array[0]));
                echo $date; ?>
                </div>
            </div>
            <div class="pt-3">
              <div class="">
                <div class="">Size
                  </div>
                <div class="">{{ $st }}
                  </div>
                  <div class="py-3">
                    <a href="{{ $presignedUrl }}" title="Download">
                      <button class="btn btn-primary py-1 px-2"><i class="fas fa-file-download"></i> Download</button></a>
                    </div>                  
              </div>
            </div>                              
          </div>                
        </div>
      </div>
    </div>
  </div>
<?php

    }
?>

  <script src="/assets/js/bootstrap.bundle.min.js"></script>  

</body>
</html>
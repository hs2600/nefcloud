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
$tableName = 'nefcloud_guest_shares';
$s3url = 'https://d3d13yzn6lly8i.cloudfront.net/';
$bucket = 'nefcloud.com';
// $user_sub = '26ac4c01-c47d-4125-afc5-5747cb99d7d8';
// $file_id = 'eff01938da4c4125842a5b0f';

$file_name = '';
$file_url = '';
$error = '';
$created = '';
$file_id = $id;

// echo $id;

//Return item from keys table
$result = $dynamodb->getItem([
    'Key' => [
        'ShareID' => [
            'S' => $file_id
        ],
    ],
    'TableName' => $tableName,
]);

if(isset($result["Item"])){
    $item = $marshaler->unmarshalItem($result["Item"]);
    $key = $item['Key'];
    // echo $key;
    $key_split = explode('/',$item["Key"]);
    $file_name = implode('',array_slice($key_split, -1));
    $created = $item["Created"];
    $file_url = $s3url.$item["Key"];
    $file_size = $item["Size"];
    $file_type = $item["Type"];

    //Creating a presigned URL
    $timeout = '10';
    $dist_type = 'attachment;filename='.$file_name;
    $cmd = $s3Client->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key' => 'VWpGV1JsVXhVVDA9/'.$item['Key'],
        'ResponseServerSideEncryption' => 'AES512',
        // 'ResponseContentDisposition' => 'inline',
        // 'SSECustomerAlgorithm' => 'AES256',
        // 'ResponseContentEncoding' => 'x-gzip',
        'ResponseContentDisposition' => $dist_type
        // 'ResponseContentType' => 'binary/Octet-stream'
    ]);

    // Get the actual presigned-url
    // attachment
    $request = $s3Client->createPresignedRequest($cmd, "+$timeout minutes");
    $presignedUrl = (string)$request->getUri();
    // $presignedUrlx = str_replace("&X-Amz","xX-Amz",$presignedUrl);
    // $presignedUrlx = base64_encode($presignedUrlx);

    //inline
    $dist_type = 'inline';
    $cmd = $s3Client->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key' => 'VWpGV1JsVXhVVDA9/'.$item['Key'],
        'ResponseContentDisposition' => $dist_type
    ]);
    $request = $s3Client->createPresignedRequest($cmd, "+$timeout minutes");
    $presignedUrl_inline = (string)$request->getUri();

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


?>

<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{{ $file_name }}</title>
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
        <a href="/" class="btn btn-sm btn-info">
        Home</a>
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
                    echo '<img src="'.$presignedUrl_inline.'"><br>';
                } else if($file_type == 'pdf'){
                    echo '<embed class="vh100" src="'.$presignedUrl_inline.'#toolbar=1" width="100%">';
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
                echo '<pre>';
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
                $created_date = new DateTime($created);
                echo $created_date->format('Y-m-d, h:i a'); ?>
                </div>
                <div class=""><?php
                    $today = new DateTime(date('Y-m-d H:i:s'));
                    $target = new DateTime($created);

                    date_add($target,date_interval_create_from_date_string("2 week"));
                    $interval = $today->diff($target);
                    echo '<i>Expires in '. $interval->format('%a days').'</i>';
                    
                    ?>
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
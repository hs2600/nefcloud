<?php

use Aws\S3\ObjectUploader;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

$error = '';

//create directory
if(isset($_GET["dname"])){
    $dname = $_GET["dname"];
    $dname = array_filter(explode("/",$dname));
    $dname = implode('/',$dname);

    $dir = $_GET['dir'];
    if(strpos($dir,"/") === false){
        $dir = base64_decode($dir);
    } 
    if($dir != ""){
        $dir_arr = array_filter(explode('/',$dir));
        $dir = implode('/',$dir_arr);
        $dir = $dir.'/';
    }

    $key = $obj_prefix.$user_sub.'/'.$dir.$dname.'/';

    $s3Client->putObject(array(
        'Bucket' => $bucket,
        'Key' => $key,
        'Body' => "",
    ));

    $response['success'] = true;
    $response['message'] = $dir.$dname.'/';

    echo json_encode($response);

    exit;
}    

//file upload
if (isset($_POST)) {
    $dir = $_POST['dir'];
    if($dir != ""){
        $dir_arr = array_filter(explode('/',$dir));
        $dir = implode('/',$dir_arr);
        $dir = $dir.'/';
    }

    if (array_key_exists('file', $_FILES)) {
        foreach ($_FILES['file']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['file']['error'][$index] !== UPLOAD_ERR_OK) {

                if ($_FILES['file']['error'][$index] === 1) {
                    $error = "Max file size exceeded! (php.ini)";
                } elseif ($_FILES['file']['error'][$index] === UPLOAD_ERR_FORM_SIZE) {
                    $error = "Max file size exceeded!";
                } elseif ($_FILES['file']['error'][$index] === UPLOAD_ERR_NO_FILE) {
                    $error = "You must choose a file!";                            
                } else {
                    $error = "An unknown error occurred during upload!";
                }
                //echo "<h3><font color=red>".$error."</font></h3>";
                continue;
                /* error list:
                1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk',
                8 => 'A PHP extension stopped the file upload'
                */                    
            }
            $filename = $_FILES["file"]["name"][$index];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            //$ext = 'png';
            $file_size = $_FILES['file']['size'][$index];
            $allowed = array("doc", "docx", "xls", "xlsx", "pptx", "pdf", "txt", "dat", "class", "css", "html", "java", "json", "jpg", "png", "mp3", "m3u", "asm", "java", "php", "py", "mp4", "rpm");
            $fname = $_FILES["file"]["tmp_name"][$index];
            // Make sure extension matches
            if (in_array($ext, $allowed)) {
                if ($_FILES['file']['size'][$index] < 600000000) { //in bytes (600MB)

                    $key = $obj_prefix.$user_sub.'/'.$dir.$filename;

                    $source = $fname;
                    $mime_type = mime_content_type($fname);
                    // $uploader = new ObjectUploader(
                    //     $s3Client,
                    //     $bucket,
                    //     $key,
                    //     $source,
                    //     '',
                    //     array(
                    //         'ContentType' => $mime_type
                    //     )
                    // );

                    $uploader = new MultipartUploader($s3Client, $source, [
                        'bucket' => $bucket,
                        'key'    => $key,
                        'concurrency' => 5,
                        'part_size' => (100 * 1024 * 1024),
                        'before_initiate' => function(\Aws\Command $command) use ($mime_type) {
                            $command['ContentType'] = $mime_type;
                        }
                    ]);
                    
                    do {
                        try {
                            $result = $uploader->upload();
                            // $result = $s3Client->putObject(array(
                            // 'Bucket' => $bucket, // Defines name of Bucket
                            // 'Key' => $key, //Defines Folder name
                            // 'SourceFile' => $source,
                            // 'ContentType' => $mime_type,
                            // ));
                            //print_r($result);
                            
                            // if ($result["@metadata"]["statusCode"] == '200') {
                            //     // print('<p><font color=green>File successfully uploaded to ' . $result["ObjectURL"] . '</font><br>');
                            //     //echo "Upload complete: {$result['ObjectURL']}" . PHP_EOL;
                            //     print($result);
                            //     // echo 'success';
                            // }
                            //print($result);
                        } catch (MultipartUploadException $e) {
                            echo $e->getMessage() . "\n";
                        }
                    } while (!isset($result));
                } else {
                    $error = "Max file size exceeded!";
                }
            } else {
                $error = "Bad file extension!";
            }
        }
        //wait for lambda to finish adding records to DynamoDB table 
        if($error != "") {
            $response['success'] = false;
            $response['message'] = $error;
        } else {
            $response['success'] = true;
            $response['message'] = 'okay';
        }

        echo json_encode($response);
        // sleep(3);
    }
}
?>
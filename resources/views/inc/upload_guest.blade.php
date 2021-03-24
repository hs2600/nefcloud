<?php

    $user_sub = 'VWpGV1JsVXhVVDA9';
    
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $share_id = substr(str_shuffle($permitted_chars), 0, 7);

    $dir = $share_id;
    if($dir != ""){
        $dir_arr = array_filter(explode('/',$dir));
        $dir = implode('/',$dir_arr);
        $dir = $dir.'/';
    }


//-------------------------
//upload code
//-------------------------
$error = '';
if (array_key_exists('file', $_FILES)) {
    // print_r($_FILES).'<br>';

    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {

        if ($_FILES['error'] === 1) {
            $error = "Max file size exceeded! (php.ini)";
        } elseif ($_FILES['error'] === UPLOAD_ERR_FORM_SIZE) {
            $error = "Max file size exceeded!";
        } elseif ($_FILES['error'] === UPLOAD_ERR_NO_FILE) {
            $error = "You must choose a file!";                            
        } else {
            $error = "An unknown error occurred during upload!";
        }
        echo "<h3><font color=red>".$error."</font></h3>";
        // continue;
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

    $filename = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $file_size = $_FILES['file']['size'];
    $allowed = array("doc", "docx", "xls", "xlsx", "pptx", "pdf", "txt", "dat", "class", "css", "html", "java", "json", "jpg", "png", "mp3", "m3u", "asm", "java", "php", "py", "mp4");
    $type = $_FILES["file"]["type"];

    // Make sure extension matches
    if (in_array($ext, $allowed)) {
        if ($file_size < 60000000) { //in bytes (60MB)

            $s3_key = $user_sub.'/'.$dir.$filename;
            $key = $dir.$filename;

            // $mime_type = mime_content_type($fname);
            
            do {
                try {
                    //$result = $uploader->upload();
                    $result = $s3Client->putObject(array(
                    'Bucket' => $bucket, // Defines name of Bucket
                    'Key' => $s3_key, //Defines path and file name
                    'SourceFile' => $tmp_name,
                    'ContentType' => $type,
                    // 'ContentType' => 'binary/Octet-stream',
                    'CacheControl' => 'max-age=1209600',
                    // 'ContentDisposition' => 'attachment;filename='.$filename
                    'Metadata' => array(
                        'shareid' => $share_id
                    )
                    ));
                    if ($result["@metadata"]["statusCode"] == '200') {
                        // print('File successfully uploaded to ' . $result["ObjectURL"]);
                        // echo "Upload complete: {$result['ObjectURL']}" . PHP_EOL;
                        // print($result);

                        $i = $marshaler->marshalJson('
                        {
                            "ShareID": "' . $share_id . '",
                            "Key": "' . $key . '",
                            "Created": "'.date("Y-m-d h:i:s a").'",
                            "Type": "' . $ext . '",
                            "Size": "' . $file_size . '"
                        }
                        ');
            
                        $params = [
                            'TableName' => 'nefcloud_guest_shares',
                            'Item' => $i
                        ];
            
                        try {
                            $result = $dynamodb->putItem($params);
                            // echo "Added item: $key [$share_id]\n";
            
                        } catch (DynamoDbException $e) {
                            // echo "Unable to add item:\n";
                            // echo $e->getMessage() . "\n";
                            $error = $e->getMessage();
                        }

                    }
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


if($error != "") {
    $response['success'] = false;
    $response['message'] = $error;
} else {
    $response['success'] = true;
    $response['file_id'] = $share_id;
}

echo json_encode($response);

?>

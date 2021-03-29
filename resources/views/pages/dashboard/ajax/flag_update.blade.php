<?php 

  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }

if(!is_ajax_request()){ exit;}

$fid = '';
$t = '';
$error = '';
// $message = '';
if(isset($_POST['fid']) && isset($_POST['t'])){
    $fid = $_POST['fid'];
    $t = $_POST['t'];

    $file_id = $fid;

    if(strpos($file_id,'/') === false){
        //fid is a file
        //Return item from keys table
        $result = $dynamodb->getItem([
            'Key' => [
                'FileID' => [
                    'S' => $file_id
                ],
            ],
            'TableName' => $tableName_keys,
        ]);

        $item_key = $marshaler->unmarshalItem($result["Item"]);
        // $user_sub = explode('/',$item_key['Key'])[0];
        $key = $item_key['Key'];    

    } else {
        //fid is a directory and most likely missing from table.
        //check if directory exists in dynamodb table
        $key = $user_sub.'/'.$fid;
        $key = array_filter(explode("/",$key));
        $key = implode('/',$key).'/';

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

        //add directory to table if missing
        if(empty($result["Item"])){
            $id = bin2hex(random_bytes(12));
            $i = $marshaler->marshalJson('
                {
                    "UserID": "' . $user_sub . '",
                    "Key": "' . $key . '",
                    "Type": "dir",
                    "Created": "'.date("Y-m-d h:i:s a").'",
                    "FileID": "'.$id.'"
                }
            ');

            $params = [
                'TableName' => $tableName_files,
                'Item' => $i
            ];

            $i2 = $marshaler->marshalJson('
                {
                    "FileID": "'.$id.'",
                    "Key": "' . $key . '",
                    "Created": "'.date("Y-m-d h:i:s a").'"
                }
            ');            
            $params2 = [
                'TableName' => $tableName_keys,
                'Item' => $i
            ];            

            try {
                $result = $dynamodb->putItem($params);
                $result2 = $dynamodb->putItem($params2);
                // echo "Added! ";

            } catch (DynamoDbException $e) {
                $error = "Unable to add directory";
                // echo $e->getMessage() . "\n";
            }
        }
    }

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

    $flag = '';
    if(isset($item[ucwords($t)])){
        $flag = $item[ucwords($t)];
    } else {
        $flag = 0;
    }

    if($flag){
        if($t == 'shared'){
            echo 'null';
            exit;
        }
        $flag = 'false';
        // $message = 'false';
    } else {
        $flag = 'true';
        // $message = 'true';
    }

    //update item
    $mkey = $marshaler->marshalJson('
    {
        "UserID": "' . $user_sub . '", 
        "Key": "' . $key . '"
    }
    ');

    $eav = $marshaler->marshalJson('
    {
        ":v": ' . $flag .'
    }
    ');

    $u_expression = 'set #m = :v';

    $params = [
        'TableName' => $tableName_files,
        'Key' => $mkey,
        'UpdateExpression' => $u_expression,
        'ExpressionAttributeValues'=> $eav,
        'ExpressionAttributeNames' => ['#m' => ucwords($t)],
        'ReturnValues' => 'UPDATED_NEW'
    ];

    try {
        $result = $dynamodb->updateItem($params);
        // echo 'Updated item.'. $item['FileID'].'\n';
        // echo '['.$item['FileID'].']  ';
        // print_r($result['Attributes']);

    } catch (DynamoDbException $e) {
        // Fail quietly
        $error = "Unable to update item";
        // echo $e->getMessage() . "\n";
    }
}

    if($error != "") {
        $response['success'] = false;
        $response['message'] = $error;
    } else {
        $response['success'] = true;
        $response['message'] = $flag;
    }

    echo json_encode($response);
 
?> 
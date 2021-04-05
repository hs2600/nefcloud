<?php

$folder_count = 0;
$files_count = 0;
$root_size = 0;
$total_size = 0;

$r = str_replace("/ajax","",$_SERVER['REQUEST_URI']);
$r = base64_encode($r);

$key = $user_sub.'/'.$dir.'/';
$key = array_filter(explode("/",$key));
$key = implode('/',$key).'/';

//List files
$dir_array = explode("/",$dir);
array_pop($dir_array);
$x = '';

foreach($dir_array as $i)
{
    $x = $x.$i.'/';
}

echo '<ul class="p-0 m-0 list flex flex-wrap" style="border: 0px solid green;">';    

//Get lisf of file names from DynamoDb
// //favorites
// $j_filter = '{
//     ":uid": "26ac4c01-c47d-4125-afc5-5747cb99d7d8",
//     ":favorite": true
// }';
// $filter_expression = 'UserID = :uid  and Favorite = :favorite';

//Expression attribute values
$eav = $marshaler->marshalJson($j_filter);
$params = [
    'TableName' => $tableName_files,
    'ProjectionExpression' => '#key, FileID, FileName, #type, Size, #shared, Deleted, Favorite',// 'Limit' => 10,
    'FilterExpression' => $filter_expression,
    'ExpressionAttributeNames'=> ['#key' => 'Key','#type' => 'Type', '#shared' => 'Shared'],
    'ExpressionAttributeValues'=> $eav
];

try {
    while (true) {
        $result = $dynamodb->scan($params);
        // print_r($result);
        foreach ($result['Items'] as $i) {
            $item = $marshaler->unmarshalItem($i);

            //check if deleted flag is true and we're in trash folder
            $flag = '';
            if(isset($item['Deleted'])){
                $flag = $item['Deleted'];
            } else {
                $flag = 0;
            }
            if ($_SERVER['REQUEST_URI'] != '/dashboard/trash/' && $_SERVER['REQUEST_URI'] != '/ajax/dashboard/trash/'){
                if($flag){
                    continue;
                }
            }

            //check if favorite flag is true
            $fflag = '';
            if(isset($item['Favorite'])){
                $fflag = $item['Favorite'];
            } else {
                $fflag = 0;
            }
            $fclass = '';
            if($fflag){
                $fclass = 'favorite';
            }   
            // echo 'Key: ' . $item['Key'] . "<br>";
            // echo 'Key: ' . $item['Key'] . "<br>";
            if(isset($item["Size"])){
                $file_size = $item["Size"];
            } else $file_size = 0;
            $total_size = $total_size + $file_size;
            $fonta_color = 'gray5';
            // echo '<b>('.substr_count($item['Key'], '/').') </b>';

            // echo '<b>('.substr_count($item['Key'], '/').') </b>';
            $file_type = $item["Type"];
            $file_id = $item["FileID"];
            // $file_size = $item["Size"];
            $key_split = explode('/',$item["Key"]);
            $file_name = implode('',array_slice($key_split, -1));
            $file_url = $s3url.'/'.$item["Key"];
            $root_size = $root_size + $file_size;
            
            $sz = 'BKMGTP';
            if($file_size < 1024){
                $st = $file_size. ' bytes';
            } else {
                $factor = floor((strlen($file_size) - 1) / 3);
                $st = sprintf("%.2f", $file_size / pow(1024, $factor)) . @$sz[$factor];
            }    

            if($file_type == 'dir'){
                $folder_count++;
                $st = '';
                $path_arr = array_slice(explode('/',$item["Key"]), 1, -1);
                $path = implode('/',$path_arr);
                // $path = str_replace($share_dir, '', $path); //share edit
                $file_name = array_slice($path_arr, -1)[0];
            } else {
                $files_count++;
            }
            
            // echo $item['Key'] . '<br>';
            $font_awesome = 'fas fa-file-'.$file_type;

            if($file_type == "docx" || $file_type == "doc"){
                $font_awesome = 'fas fa-file-word';
                $file_type = 'word';
                $fonta_color = 'blue5';
            }
            if($file_type == "xlsx" || $file_type == "xls"){
                $font_awesome = 'fas fa-file-excel';
                $file_type = 'excel';   
                $fonta_color = 'green5';
            }
            if($file_type == "pptx" || $file_type == "ppt"){
                $font_awesome = 'fas fa-file-powerpoint';
                $file_type = 'powerpoint';
            }
            if($file_type == "txt" || $file_type == "dat"){
                $font_awesome = 'fas fa-file-alt';
                $fonta_color = 'gray';
            }
            if($file_type == "py"){
                $font_awesome = 'fab fa-python';
                $fonta_color = 'yellow5';
            }
            if($file_type == "mp3"){
                $font_awesome = 'fas fa-file-audio';
                $fonta_color = 'grape5';
            }
            if($file_type == "mp4"){
                $font_awesome = 'fas fa-file-video';
                $fonta_color = 'cyan5';
            }                    
            if($file_type == "json"
            || $file_type == "asm"
            || $file_type == "css"
            || $file_type == "java"
            || $file_type == "php"){
                $font_awesome = 'fas fa-file-code';
                $file_type = "code";
                $fonta_color = 'orange5';
            }
            // echo $file_name . '-' .$file_type;

            if($file_type == 'pdf'){
                $fonta_color = 'red5';
            }
                    // echo $file_type;

            $ext_arr = array('code', 'word', 'excel', 'powerpoint', 'mp3','mp4','pdf','ppt','txt','dat', 'py');
            $ext_img_arr = array('gif','png','jpg','jpeg');

            if(!in_array($file_type, $ext_arr) && !in_array($file_type, $ext_img_arr)) {
                $font_awesome = 'fas fa-file';
            }

            echo '<li style="width: 155px; height:180px; border:0px solid #e5e5e5; padding: 5px">';
            echo '<div id="'.$file_id.'" class="'.$fclass.'" style="transform: translateZ(0); border: 1px solid #e5e5e5; border-radius: 5px; background-color: #fcfcfc; height: 100%">';

            if(in_array($file_type, $ext_img_arr)) {
                echo '  <a href="/dashboard/file/?fid='.$file_id.'&r='.$r.'" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top" style="height: 135px;">';
                echo '<div style="background-image: url(\''.$file_url.'\'); width: 100%;
                height: 100%;
                background-repeat: no-repeat;
                background-size: cover;
                " ></div>';
            } else if(in_array($file_type, $ext_arr)) {
                echo '  <a href="/dashboard/file/?fid='.$file_id.'&r='.$r.'" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top no-underline hover-bg-blue3 hover-white ' . $fonta_color . '" style="height: 135px;">';
                echo '  <span class=""><i class="'.$font_awesome.'" style="font-size: 90px"></i></span>';
            } else if ($file_type == 'dir'){
                echo '  <a href="/dashboard/?dir='.base64_encode($path).'" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top no-underline hover-bg-blue3 hover-white gray6" style="height: 135px;">';
                echo '  <span class=""><i class="fas fa-folder" style="font-size: 90px"></i></span>';
            } else {
                echo '  <a href="/dashboard/file/?fid='.$file_id.'&r='.$r.'" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top no-underline hover-bg-blue3 hover-white ' . $fonta_color . '" style="height: 135px;">';
                echo '  <span class=""><i class="'.$font_awesome.'" style="font-size: 90px"></i></span>';
            }

            echo '  </a>';
            echo '  <div class="w-100 ph1 pv2 tc f2">';
            echo '  <span class="db gray5 hover-blue7" style=" width: 135px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;" title="'.$file_name.'">'.$file_name.'</span>';
            echo '  </div>';
            if ($file_type == 'dir'){
            echo '  <a href="#" class="absolute top-025 right-025 gray4 hover-gray7" data-bs-toggle="modal" data-bs-target="#exModal" onclick="fileMenu(\''.$file_name.'\',\''. $file_id .'\')">';
            } else  {
            echo '  <a href="#" class="absolute top-025 right-025 gray4 hover-gray7" data-bs-toggle="modal" data-bs-target="#exModal" onclick="fileMenu(\''.$file_name.' ('.$st.')\',\''.$file_id.'\')">';
            }
            echo '  <span data-balloon="More" data-balloon-pos="left" class="relative badge hover-bg-gray4 gray5 hover-gray7"><i class="fas fa-ellipsis-h" style="font-size: 12px;"></i></span>';
            echo '  </a>';
            echo '  <span class="favorite-button absolute bottom-075 right-025 gray2 hover-yellow3" style="background-color: transparent; border: 0; cursor: pointer;">';
            echo '  <i class="fas fa-star" style="font-size: 12px;"></i>';
            echo '  </span>';                    
            echo '</div></li>';

        }
        if (isset($result['LastEvaluatedKey'])) {
            $params['ExclusiveStartKey'] = $result['LastEvaluatedKey'];
        } else {
            break;
        }
    }
} catch (DynamoDbException $e) {
    echo "Unable to scan:\n";
    echo $e->getMessage() . "\n";
}

echo '</ul>';

//folder info -->
$sz = 'BKMGT';

if($root_size < 1024){
    $st_root = $root_size. ' bytes';
} else {
    $factor_root = floor((strlen($root_size) - 1) / 3);
    $st_root = sprintf("%.2f", $root_size / pow(1024, $factor_root)) . ' ' . @$sz[$factor_root].'B';
}    

if($total_size < 1024){
    $st_total = $total_size. ' bytes';
} else {
    $factor_total = floor((strlen($total_size) - 1) / 3);
    $st_total = sprintf("%.2f", $total_size / pow(1024, $factor_total)) . ' ' . @$sz[$factor_total].'B';
}

if($folder_count > 0 || $files_count > 0){
    echo '<div class="bg-gray1 f2 mx-2 m-0 px-3 p-0" style="border: 0px solid green;">';        
}

if($folder_count > 0){
    echo $folder_count;
    if($folder_count == 1){ echo ' folder'; } else { echo ' folders';};
}
if($folder_count > 0 && $files_count > 0){
    echo ' and ';
}
if($files_count > 0){
    echo $files_count;
    if($files_count == 1){ echo ' file'; } else { echo ' files';};
}

if($root_size > 0){
    echo ' ('. $st_root.')';
}
if($st_root != $st_total){
echo '&nbsp;&nbsp;| <i><b>Total: '. $st_total.'</i></b>';
}

if($folder_count > 0 || $files_count > 0){
    echo '</div>';
}

?>

<?php 
$uri_split = array_filter(explode("/",$_SERVER['REQUEST_URI']));
$loc = implode('/',array_slice($uri_split, -1));
//  echo $loc;
?>

<?php

if($folder_count == 0 && $files_count == 0){
    echo '<div id="emptycontent">';
    if($loc == 'trash'){
        echo '<i class="fas fa-trash" style="font-size: 60px;"></i>';
        echo '<h2>No deleted files</h2>';
        echo '<h3>You will be able to recover deleted files from here</h3>';
    } else if ($loc == 'favorites'){
        echo '<i class="fas fa-star" style="font-size: 60px;"></i>';
        echo '<h2>No favorites</h2>';
        echo '<h3>Files and folders you mark as favorite will show up here</h3>';
    } else if ($loc == 'sharein'){
        echo '<i class="fas fa-share-square" style="font-size: 60px;"></i>';
        echo '<h2>Nothing shared with you yet</h2>';
        echo '<h3>Files and folders others share with you will show up here</h3>';
    } else if ($loc == 'shareout'){
        echo '<i class="fas fa-external-link-alt" style="font-size: 60px;"></i>';
        echo '<h2>Nothing shared yet</h2>';
        echo '<h3>Files and folders you share will show up here</h3>';
    } else if ($loc == 'sharelink'){
        echo '<i class="fas fa-link" style="font-size: 60px;"></i>';
        echo '<h2>No shared links</h2>';
        echo '<h3>Files and folders you share by link will show up here</h3>';
    }
    echo '</div>';
}
 ?>



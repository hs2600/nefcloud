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

if (isset($_GET['dir'])){
echo '<li style="width: 155px; height:180px; border:0px solid #e5e5e5; padding: 5px">';
echo '<div class="shadow-hover hover-bg-white" style="border: 1px solid #e5e5e5; border-radius: 5px; background-color: #fcfcfc; height: 100%">';
if(!$x == ''){
    echo '<a href="?dir='.base64_encode($x).'" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top no-underline hover-bg-blue3 hover-white gray6" style="height: 100%;">';
} else {
    echo '<a href="/dashboard/" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top no-underline hover-bg-blue3 hover-white gray6" style="height: 100%;">';
}

echo '<span class=""><i class="fas fa-level-up-alt" style="font-size: 50px"> ..</i>';
echo '</span></a>';
echo '</div>';
echo '</li>'; 
}

//Get S3 directories from provided root directory
try {
    $results = $s3Client->getPaginator('ListObjects', [
        'Bucket' => $bucket,
        'Delimiter' => '/',
        'Prefix' => $obj_prefix.$key
    ]);

} catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

$expression = '[CommonPrefixes[].Prefix, Contents[].Key][]';
foreach ($results->search($expression) as $i) {
    $i = str_replace($obj_prefix, "", $i);

    if(substr($i, -1) == "/"){
        if(substr_count($key, '/')+1 == substr_count($i, '/')){

            $path_arr = array_slice(explode('/',$i), 1, -1);
            $path = implode('/',$path_arr);
            $path = str_replace($share_dir, '', $path); //share edit
            $dirname = array_slice($path_arr, -1);
            $afid = '/'.$path.'/'; //to send to ajax

            $result = $dynamodb->getItem([
            'Key' => [
                'UserID' => [
                    'S' => $user_sub
                ],
                'Key' => [
                    'S' => $i
                ],
            ],
            'TableName' => $tableName_files,
            ]);

            if(isset($result["Item"])){
                $item = $marshaler->unmarshalItem($result["Item"]);
                $file_id = $item["FileID"];
                $afid = $file_id;
            }

            //check if deleted flag is true
            $dflag = '';
            if(isset($item['Deleted'])){
                $dflag = $item['Deleted'];
            } else {
                $dflag = 0;
            }
            if($dflag){
                continue;
            } else {
                $folder_count++;
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

            echo '<li style="width: 155px; height:180px; border:0px solid #e5e5e5; padding: 5px">';
            echo '<div id="'.$file_id.'" class="'.$fclass.'" style="transform: translateZ(0); border: 1px solid #e5e5e5; border-radius: 5px; background-color: #fcfcfc; height: 100%">';
            echo '  <a href="?dir='.base64_encode($path).'" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top no-underline hover-bg-blue3 hover-white gray5" style="height: 135px;">';
            echo '  <span class=""><i class="fas fa-folder" style="font-size: 90px"></i></span>';
            echo '  </a>';
            echo '  <div class="w-100 ph1 pv2 tc f2">';
            echo '  <span class="db gray5 hover-blue7 text select-all" style=" width: 135px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;" title="'.$dirname[0].'">'.$dirname[0].'</span>';
            echo '  </div>';
            echo '  <a href="#" class="absolute top-025 right-025 gray4 hover-gray7" data-bs-toggle="modal" data-bs-target="#exModal" onclick="fileMenu(\''.$dirname[0].'\',\''. $afid .'\')">';
            echo '  <span data-balloon="More" data-balloon-pos="left" class="relative badge hover-bg-gray5 gray5 hover-white active-bg-red"><i class="fas fa-ellipsis-h" style="font-size: 12px;"></i></span>';
            echo '  </a>';
            echo '  <span class="favorite-button absolute bottom-075 right-025 gray2 hover-yellow3">';
            echo '  <i class="fas fa-star" style="font-size: 12px;"></i>';
            echo '  </span>';
            echo '  </div></li>';

        }
    } 
}

//Get lisf of file names from DynamoDb
//all files
$j_filter = '{
    ":uid": "'. $user_sub . '",
    ":key": "'. $key . '"
}';
$filter_expression = 'UserID = :uid and begins_with(#key, :key)';

//Expression attribute values
$eav = $marshaler->marshalJson($j_filter);
$params = [
    'TableName' => $tableName_files,
    'ProjectionExpression' => '#key, FileID, FileName, #type, Size, Deleted, Favorite',// 'Limit' => 10,
    'FilterExpression' => $filter_expression,
    'ExpressionAttributeNames'=> ['#key' => 'Key','#type' => 'Type'],
    'ExpressionAttributeValues'=> $eav
];

try {
    while (true) {
        $result = $dynamodb->scan($params);
        // print_r($result);
        foreach ($result['Items'] as $i) {
            $item = $marshaler->unmarshalItem($i);
            // echo 'Key: ' . $item['Key'] . "<br>";
            if(substr($item['Key'], -1) != "/"){
                // echo 'Key: ' . $item['Key'] . "<br>";
                
                //check if deleted flag is true
                $dflag = '';
                if(isset($item['Deleted'])){
                    $dflag = $item['Deleted'];
                } else {
                    $dflag = 0;
                }
                if($dflag){
                    continue;
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

                $file_size = $item["Size"];
                $total_size = $total_size + $file_size;
                $fonta_color = 'gray5';

                // echo '<b>('.substr_count($item['Key'], '/').') </b>';
                if(substr_count($key, '/') == substr_count($item['Key'], '/')){
                    // echo '<b>('.substr_count($item['Key'], '/').') </b>';
                    $file_type = $item["Type"];
                    $file_id = $item["FileID"];
                    // $file_size = $item["Size"];
                    $key_split = explode('/',$item["Key"]);
                    $file_name = implode('',array_slice($key_split, -1));
                    $file_url = $s3url.'/'.$item["Key"];
                    $files_count++;
                    $root_size = $root_size + $file_size;
                    
                    $sz = 'BKMGTP';
                    if($file_size < 1024){
                        $st = $file_size. ' bytes';
                    } else {
                        $factor = floor((strlen($file_size) - 1) / 3);
                        $st = sprintf("%.2f", $file_size / pow(1024, $factor)) . @$sz[$factor];
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
                    } else {
                        echo '  <a href="/dashboard/file/?fid='.$file_id.'&r='.$r.'" class="flex flex-column items-center justify-center color-inherit w-100 pa2 br2 br--top no-underline hover-bg-blue3 hover-white ' . $fonta_color . '" style="height: 135px;">';
                        echo '  <span class=""><i class="'.$font_awesome.'" style="font-size: 90px"></i></span>';
                    }

                    echo '  </a>';
                    echo '  <div class="w-100 ph1 pv2 tc f2">';
                    echo '  <span class="db gray5 hover-blue7" style=" width: 135px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;" title="'.$file_name.'">'.$file_name.'</span>';
                    echo '  </div>';
                    echo '  <a href="#" class="absolute top-025 right-025 gray4 hover-gray7" data-bs-toggle="modal" data-bs-target="#exModal" onclick="fileMenu(\''.$file_name.' ('.$st.')\',\''.$file_id.'\')">';
                    echo '  <span data-balloon="More" data-balloon-pos="left" class="relative badge hover-bg-gray4 gray5 hover-gray7"><i class="fas fa-ellipsis-h" style="font-size: 12px;"></i></span>';
                    echo '  </a>';
                    // echo '  <a href="#" class="absolute bottom-075 right-025 '.$fav_color.' hover-yellow3" onclick="ajaxDBUpdate(\''.$file_id.'\',\'favorite\')">';
                    echo '  <span class="favorite-button absolute bottom-075 right-025 gray2 hover-yellow3" style="background-color: transparent; border: 0; cursor: pointer;">';
                    echo '  <i class="fas fa-star" style="font-size: 12px;"></i>';
                    echo '  </span>';                    
                    echo '</div></li>';
                }
            }
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
// echo '</form>';
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
$gb = sprintf("%.2f", $total_size / pow(1024, 3));
$used_percent = sprintf("%.2f", $gb / $storage_total) * 100;    

if($folder_count == 0 && $files_count == 0){
    echo '<div id="emptycontent">';
    echo '<i class="fas fa-cloud-upload-alt" style="font-size: 60px;"></i>';
    echo '<h2>This folder is empty</h2>';
    echo '<h3>How about uploading some content?</h3>';
    // echo '</div>';
}

// echo '</form>';
// echo '</div>';

if($folder_count > 0 || $files_count > 0){
    echo '<div class="bg-gray1 f2 mx-2 mt-2 px-3 p-0" style="border: 0px solid green;">';
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

    $uri_split = array_filter(explode("?",$_SERVER['REQUEST_URI']));
    $uri_split = array_filter(explode("/",$uri_split[0]));
    $loc = implode('/',array_slice($uri_split, -1));
    $gb = sprintf("%.2f", $total_size / pow(1024, 3));
    $ratio = sprintf("%.2f", $gb / $storage_total) * 100;
if($loc == 'dashboard' && $dir == ''){
    echo '<script> storageUpdate("' . $st_total.'"' . ', "' . $used_percent . '" );</script>';
    setcookie('st_info', $total_size, time() + 3600, "/");
}


if($folder_count > 0 || $files_count > 0){
    echo '</div>';
}

 ?>

<!-- upload progress bar -->
<div class="mx-2 mt-1 progress" style="height: 10px; visibility: hidden;" id="pb">
<div class="px-2 f1 progress-bar progress-bar-striped" role="progressbar" style="width: 50%;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="the-progress-div"></div>
</div>


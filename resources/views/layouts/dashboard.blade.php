<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//get current url path
$uri_split = array_filter(explode("?",$_SERVER['REQUEST_URI']));
$urix = '';
if(isset($_GET['dir'])){
  $urix = array_filter(explode("dir=",$_SERVER['REQUEST_URI']))[1];
}
$uri_split = array_filter(explode("/",$uri_split[0]));
$loc = implode('/',array_slice($uri_split, -1));
$loc_home = implode('/',array_slice($uri_split, 0));

// if(!$authenticated && strpos($loc_home,'dashboard') !== false){
//   return Redirect::to('login')->send();
// }

// echo $authenticated.'<br>';
// echo $user_sub.'<br>';
// echo $email.'<br>';

?>

<!doctype html>
<html lang="en">
  <head>

    <title>{{ config('app.name') }}</title>
    @include('inc.head')

<style>

/* body{
    padding-top: 0rem;
} */

.bd-placeholder-img {
font-size: 1.125rem;
text-anchor: middle;
-webkit-user-select: none;
-moz-user-select: none;
user-select: none;
}

div.bw {
    word-wrap: break-word;
}

div.s-auto {  
    overflow: auto;
    height: 90vh;
}

div.s-hidden {  
    overflow: hidden;
    height: 100vh;
 }

@media (min-width: 768px) {
.bd-placeholder-img-lg {
    font-size: 3.5rem;
}
}

div.crumb {
    float: left;
    display: block;
    background-image: url(/assets/images/breadcrumb.svg);
    background-repeat: no-repeat;
    background-position: left center;
    background-size: auto 20px;
}

/* .list{
  min-height: 375px;
} */

.dropzone {
    /* display: flex; */
    /* position: relative; */
    /* justify-content: flex-end; */
    /* justify-content:space-between;     */
    /* flex-direction: column; */
    /* clear: both; */
    /* background-color: rgba(255,255,255,0.2); */
    /* margin-bottom: 25px; */
    border: 2px dashed #eee ;
    padding: 5px !important;
    margin-bottom: 10px;
    /* padding-bottom: 10px !important; */
    min-height: 375px;
    /* height: 100%; */
    /* font-size: 0px; */
    /* position: absolute; */
    /* z-index: 2 */
    /* padding: 20px 20px; */
}

.dropzone .dz-preview .dz-details .dz-size {
    margin: 5px;
    padding: 5px;
    /* font-size: 25px; */
}

.dropzone .dz-preview .dz-image {
  border-radius: 0;
  /* height: 100%; */
}

.dropzone .dz-preview .dz-details {
    position: absolute;
    top: 70px;
    opacity: 0.75;
    /* font-size: 13px; */
    padding: 0;
}

.dropzone.dz-drag-hover {
  /* border-style: solid; */
  border: 3px dashed #45b7f8;
}
.dropzone.dz-drag-hover .dz-message {
  opacity: 0.5;
}

.dropzone.dz-clickable { cursor: default; }

.dropzone .dz-message {
  /* position: absolute; */
  /* bottom: 0; */
  color: #e5e5e5;
  /* position: absolute; */
  /* bottom: 0; */
  font-size: 20px; 
  /* border: 1px solid red; */
  /* height: 400px;
  width: 100%;
  vertical-align: bottom; */
  /* padding: 0; */
  /* padding-bottom: 10px !important; */
}

.favorite .favorite-button {
  color: #fcc419;
}

</style>

<script>
  function storageUpdate(size, ratio){

    document.getElementById('storage_info').innerHTML = "Using " + size + " of {{ $storage_total }} GB storage"
    document.getElementById('progress').innerHTML = "<div class=\"f2 progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width: " + ratio + "%;\" aria-valuenow=\"" + ratio + "\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>"
    document.getElementById('storage_info2').innerHTML = "Using " + size + " of {{ $storage_total }} GB storage"
    document.getElementById('progress2').innerHTML = "<div class=\"f2 progress-bar progress-bar-striped\" role=\"progressbar\" style=\"width: " + ratio + "%;\" aria-valuenow=\"" + ratio + "\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>"
  
}
</script>
       
  </head>
<body>
    
<div class="container-fluid m-0 p-0 bg-light border-bottom shadow-sm fixed-top" style="border: 0px solid green;">
  <div class="row align-items-start m-0 p-0" style="border-top: 2px solid #45b7f8;">
    <div class="col-6 p-0">
      <nav class="p-1 m-0 navbar navbar-expand-md navbar-light">
        <a class="p-0" href="/">
        <img src="/assets/images/logo.png" height="30px;"></a>
        <button class="navbar-toggler position-absolute d-md-none m-0 p-0 px-1" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon m-0 p-0" style="height: 30px;"></span>
        </button>
      </nav>
    </div>
    <div class="col p-0 d-flex justify-content-end" style="border: 0px solid red;">
      <div class="" style="border: 0px solid red;">
        {{-- <form class="d-flex p-1" method="GET" action="search.php" style="height: 32px;">
          <input class="form-control me-2 px-2" type="search" name="query" placeholder="Search" aria-label="Search" minlength="5" required="" style="border: 1px solid #c5c5c5; height: 32px;">
          <button class="btn btn-outline-primary p-0 px-2" type="submit" style="height: 32px;"><i class="fas fa-search"></i></button>
        </form> --}}
      </div>
      <?php if($authenticated){
        ?>
      <div class="navbar-nav p-0" style="border: 0px solid red;">
        <div class="nav-item dropdown m-0 p-1">
          <a class="pr-3 p-0 nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="" src="/assets/images/empty_profile.png" style="border: 0px solid #000; border-radius: 20px; width: 32px;">
          </a>
          <div class="dropdown-menu dropdown-menu-left dropdown-menu-big p-1" aria-labelledby="dropdown02">
            <div class="p-2 dig-Menu-canvas" role="presentation" style="width: 200px;">
              <div class="dig-Menu-segment p-0">
                <div class="f2">
                    {{-- Horacio Santoyo --}}
                  <div class="f2">
                    <span class="dig-Text dig-Text--variant-paragraph dig-Text--size-xsmall dig-Text--color-faint">
                    {{ $email }}</span>
                  </div>
                </div>

                <?php
                echo '<div>';
                echo '  <span class="f2 dig-Text dig-Text--variant-paragraph dig-Text--size-xsmall dig-Text--color-faint" id="storage_info2">';
                echo '  Using '. $storage_used .' of ' . $storage_total . ' GB storage</span>';
                echo '</div>';
                echo '<div class="progress" style="height: 4px;" id="progress2">';
                echo '  <div class="f2 progress-bar" role="progressbar" style="width: '.$used_percent.'%;" aria-valuenow="'.$used_percent.'" aria-valuemin="0" aria-valuemax="100"></div>';
                echo '</div>';
                ?>
              </div>
              <div class="p-0 dig-Menu-segment">
                <div>
                  <div class="m-0 py-0 f2 dig-Menu-row dig-Menu-row" role="menuitem" tabindex="-1" aria-disabled="false">
                    <div class="dig-Menu-row-content">
                      <div class="dig-Menu-row-title">
                        <a href="/logout">Sign out</a>
                      </div>
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
    </div>

  </div>
</div>

<div class="s-hidden container-fluid m-0 px-0" style="border: 0px solid green; padding-top: 32px;">
  <div class="row m-0" style="border: 0px solid green;">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="position: fixed;">
      <?php if($authenticated){ ?>
      <div class="m-0 p-2">
        <ul class="nav flex-column pb-4">
          <li class="nav-item">
            <a class="nav-link <?php if($loc == 'dashboard' && $dir == '') echo 'active'; ?>
              " aria-current="page" href="/dashboard/">
              <i class="fas fa-house-user" style="font-size: 12px;">&nbsp;</i>
             Home
            </a>
            <div class="m-0 px-3">
              <ul class="nav flex-column">
                <li class="nav-item hover-bg-gray1 f3">
                  <a class="nav-link <?php if(strpos($_SERVER['REQUEST_URI'], '=Documents') == true) echo 'active'; ?> p-0 px-3 pb-1 gray2" href="/dashboard/?dir={{ base64_encode('Documents') }}">
                  <i class="fas fa-copy" style="font-size: 12px;">&nbsp;</i>
                  Documents</a>
                </li>
                <li class="nav-item hover-bg-gray1 f3">
                  <a class="nav-link <?php if(strpos($_SERVER['REQUEST_URI'], '=Pictures') == true) echo 'active'; ?> p-0 px-3 pb-1 gray2" href="/dashboard/?dir={{ base64_encode('Pictures') }}">
                  <i class="fas fa-images" style="font-size: 12px;">&nbsp;</i>
                    Pictures</a>
                </li>                
                <li class="nav-item hover-bg-gray1 f3">
                  <a class="nav-link <?php if(strpos($_SERVER['REQUEST_URI'], '=Music') == true) echo 'active'; ?> p-0 px-3 pb-1 gray2" href="/dashboard/?dir={{ base64_encode('Music') }}">
                  <i class="fas fa-music" style="font-size: 12px;">&nbsp;</i>
                  Music</a>
                </li>
                <li class="nav-item hover-bg-gray1 f3">
                  <a class="nav-link <?php if(strpos($_SERVER['REQUEST_URI'], '=Videos') == true) echo 'active'; ?> p-0 px-3 pb-1 gray2" href="/dashboard/?dir={{ base64_encode('Videos') }}">
                  <i class="fas fa-film" style="font-size: 12px;">&nbsp;</i>
                  Videos</a>
                </li>                
              </ul>
            </div>
          </li>

          <li class="nav-item" style="border: 0px solid red;">
            <a class="nav-link" data-bs-toggle="collapse" href="#collapseShare" role="button" aria-expanded="false" aria-controls="collapseShare">
              <i class="fas fa-share-alt" style="font-size: 12px;">&nbsp;</i>
              Shares
            </a>
            <div class="collapse m-0 px-3 <?php if(strpos($loc, 'share') !== false) echo 'show'; ?>" id="collapseShare">
              <ul class="nav flex-column">
                <li class="nav-item hover-bg-gray1 f3">
                  <a class="nav-link <?php if($loc == 'sharein') echo 'active'; ?> p-0 px-3 pb-1 gray2" href="/dashboard/sharein/">
                  <i class="fas fa-share-square" style="font-size: 12px;">&nbsp;</i>
                  <span style="text-decoration: line-through">
                  Shared with you</a></span>
                </li>
                <li class="nav-item hover-bg-gray1 f3">
                  <a class="nav-link <?php if($loc == 'shareout') echo 'active'; ?> p-0 px-3 pb-1" href="/dashboard/shareout/">
                  <i class="fas fa-external-link-alt" style="font-size: 12px;">&nbsp;</i>
                  <span style="text-decoration: line-through">
                    Shared with others</a></span>
                </li>                
                <li class="nav-item hover-bg-gray1 f3">
                  <a class="nav-link <?php if($loc == 'sharelink') echo 'active'; ?> p-0 px-3 pb-1" href="/dashboard/sharelink/">
                  <i class="fas fa-link" style="font-size: 12px;">&nbsp;</i>
                    Shared by link</a>
                </li>
              </ul>
            </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link <?php if($loc == 'favorites') echo 'active'; ?>" href="/dashboard/favorites/">
              <i class="fas fa-star" style="font-size: 12px;">&nbsp;</i>
              Favorites
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if($loc == 'recent') echo 'active'; ?>" href="/dashboard/recent/">
              <i class="fas fa-history" style="font-size: 12px;">&nbsp;</i>
              <span style="text-decoration: line-through">
                Recent</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if($loc == 'tags') echo 'active'; ?>" href="/dashboard/tags/">
              <i class="fas fa-tags" style="font-size: 12px;">&nbsp;</i>
              <span style="text-decoration: line-through">
              Tags</span>
            </a>
          </li>          
        </ul>

        <?php
        echo '<div class="p-2 px-3">';
        echo '  <span class="f2 dig-Text dig-Text--variant-paragraph dig-Text--size-xsmall dig-Text--color-faint" id="storage_info">';
        echo '  Using '. $storage_used .' of ' . $storage_total . ' GB storage</span>';
        echo '  <div class="progress" style="height: 7px;" id="progress">';
        echo '    <div class="f2 progress-bar progress-bar-striped" role="progressbar" style="width: '.$used_percent.'%;" aria-valuenow="'.$used_percent.'" aria-valuemin="0" aria-valuemax="100"></div>';
        echo '  </div>';
        echo '</div>';
  
        ?>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link <?php if($loc == 'trash') echo 'active'; ?>" href="/dashboard/trash/">
              <i class="fas fa-trash" style="font-size: 12px;">&nbsp;</i>
              Trash
            </a>
          </li>

          {{-- <li class="nav-item" style="border: 0px solid red;">
            <a class="nav-link" data-bs-toggle="collapse" href="#collapseSettings" role="button" aria-expanded="false" aria-controls="collapseSettings">
              <i class="fas fa-cog" style="font-size: 12px;">&nbsp;</i>
              Settings
            </a>
            <div class="collapse m-0 px-3" id="collapseSettings">
              <ul class="nav flex-column">
                <li class="nav-item p-0 pl-3 f2" style="border: 0px solid red;">
                  <i class="fas fa-hdd" style="font-size: 12px;">&nbsp;</i>
                  WebDav
                    <input type="text" readonly="readonly" value="https://nefcloud.com/dav.php/dav/files/" style="width: 100%; border: 1px solid #c5c5c5;">
                    <br>
                    <em>Use this address to access your files via <a href="https://nefcloud.com/docs/webdav" target="_blank" rel="noreferrer"> WebDAV</a></em>
                </li>

              </ul>
            </div>
          </li> --}}

        </ul>
      </div>
      <?php } ?>
    </nav>


    <main class="s-autox col-md-9 ms-sm-auto col-lg-10 p-0" style="border: 0px solid red; background-color: #fff;">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-3 pb-1 pt-3 border-bottom" style="border: 0px solid red; background-color: #F8F9FA;">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb m-0 p-0" style="border: 0px solid blue; background-color: transparent; font-size: 13px;">

                <?php
                //breadcrumbs
                echo '<li class="breadcrumb-item pr-2"><a href="/'.$loc_home.'"><i class="fas fa-home"></i></a></li>';
                $dir_array = explode("/",$bc_dir);
                $x = '';
            
                if($bc_dir != ""){
                    foreach($dir_array as $key => $value)
                    {
                      $x = $x.$value.'/';
                      // echo $x;
                      // echo '<a href="?dir='.base64_encode($x).'" style="text-decoration: none;">'.$value.'</a>';
                      // echo '<li class="breadcrumb-item"><a href="?dir='.$x.'">'.$value.'</a></li>';
                      echo '<svg viewBox="0 0 24 24" fill="none" class="p-0 dig-UIIcon dig-UIIcon--small breadcrumb__spacer" width="16" height="18" focusable="false"><path d="M9.25 5.75L15.5 12.25L9.25 18.75" stroke="#666" stroke-width="1.25" stroke-miterlimit="10"></path></svg>
                      <li class="breadcrumb-item px-1"><a href="?dir='.base64_encode($x).'">'.$value.'</a></li>';
                      // echo '<div class="crumb pl-3 pr-2" style="border: 0px solid red;"><li class="breadcrumb-item"><a href="?dir='.$x.'">'.$value.'</a></li></div>';                      
                    }
                }
               if($loc == 'dashboard'){
                echo '<button class="btn btn-sm p-0 m-0 ml-1" type="button" data-bs-toggle="modal" data-bs-target="#Modal1" style="border: none; background-color: transparent; cursor: pointer; height: 15px;"><i class="f4 p-0 m-0 fas fa-folder-plus" style="color: #3490dc;"></i></button>';
               }
            
              ?>

            </ol>
            </nav>
            <?php 
              if($loc == 'dashboard'){
              ?>
              <div class="btn-toolbar">
              <div class="btn-group">
                {{-- <button type="button" class="btn btn-sm btn-outline-primary mx-2 px-2"><i class="fas fa-plus-circle"></i>&nbsp;Create</button> --}}
                  {{-- <button type="button" class="btn btn-sm btn-outline-primary px-2"><i class="fas fa-upload"></i>&nbsp;Upload</button> --}}
              </div>
              </div>
              <?php
              }
              ?>
        </div>
      

      <div class="s-auto m-0" id="listFiles">
      <!-- List files section start -->
      <!-- The file upload form used as target for the file upload widget -->
      <?php
        $dclass = "";
        if($loc == 'dashboard'){
            $dclass = "dropzone";
        }
      ?>

      <form class=" {{ $dclass }} " id="dzUpload">
      <input type="hidden" name="dir" value="{{ $dir }}">
      @csrf

      @yield('content')

      </form>

      <!-- List files section end -->
      </div>

    </main>
  </div>
</div>


    {{-- <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script> --}}

<!-- modal -->
<div class="modal fade p-5" id="exModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 250px;">
    <div class="modal-content">
      <div class="modal-header" style="overflow-wrap: anywhere;">
        <span class="f2 modal-title" id="title"></span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        {{-- <div class="dig-Menu-row dig-Menu-row--interactive" role="menuitem" tabindex="-1" aria-disabled="false">
          <div class="dig-Menu-row-accessory">
            <i class="fas fa-share-alt" style="font-size: 12px;">&nbsp;</i>
          </div>
          <div class="dig-Menu-row-content">
            <div class="dig-Menu-row-title" id="share">
              <a href="https://google.com" style="color: #000; text-decoration: none;">
                Share</a>
            </div>
          </div>
        </div> --}}
        <a href="#" class="" style="color: #000; text-decoration: none;" id='copylink'>
        <div class="dig-Menu-row dig-Menu-row--interactive" role="menuitem" tabindex="-1" aria-disabled="false">
          <div class="dig-Menu-row-accessory">
            <i class="fas fa-link" style="font-size: 12px;">&nbsp;</i>
          </div>
          <div class="dig-Menu-row-content">
              <div class="dig-Menu-row-title" id="copy">
              {{-- <a href="#" class="px-2 py-1 hover-bg-blue2" style="color: #000; text-decoration: none;" id='copylink'> --}}
                Copy link
            </div>
          </div>
        </div>
        </a>        

        {{-- <div class="dig-Menu-row dig-Menu-row--interactive" role="menuitem" tabindex="-1" aria-disabled="false">
          <div class="dig-Menu-row-accessory">
            <i class="far fa-arrow-alt-circle-down" style="font-size: 12px;">&nbsp;</i>
          </div>
          <div class="dig-Menu-row-content">
            <div class="dig-Menu-row-title" id="download">
              <a href="#" class="px-2 py-1 hover-bg-blue2" data-bs-dismiss="modal" style="color: #000; text-decoration: none;">
                Download</a>
            </div>
          </div>
        </div> --}}
      
        <a href="#" class="" style="color: #000; text-decoration: none;" data-bs-dismiss="modal" id="delete">
        <div class="dig-Menu-row dig-Menu-row--interactive" role="menuitem" tabindex="-1" aria-disabled="false">
          <div class="dig-Menu-row-accessory">
            <i class="far fa-trash-alt" style="font-size: 12px;">&nbsp;</i>
          </div>
          <div class="dig-Menu-row-content" style="border: 0px solid red;">
            <div class="dig-Menu-row-title">
                Delete
            </div>
          </div>
        </div>
        </a>
        
        <a href="#" class="" style="color: #000; text-decoration: none;" data-bs-dismiss="modal" id="star">
        <div class="dig-Menu-row dig-Menu-row--interactive" role="menuitem" tabindex="-1" aria-disabled="false">
          <div class="dig-Menu-row-accessory">
            <i class="far fa-star" style="font-size: 12px;">&nbsp;</i>
          </div>
          <div class="dig-Menu-row-content">
            <div class="dig-Menu-row-title">
                Star
            </div>
          </div>
        </div>
        </a>
        <input id="dummy_id" style="display:none;">

      </div>
    </div>
  </div>
</div>

<div id="tpl" style="display:none">
  <div class="p-0 m-0 dz-preview dz-processing dz-success dz-complete dz-image-preview"">  
    <ul class="p-0 m-0 list flex flex-wrap">
      <li style="width: 155px; border:0px solid #e5e5e5; padding: 5px">
        <div class="" style="border: 1px solid #e5e5e5; border-radius: 5px; background-color: #fcfcfc;">
          <div class="dz-image p-1" style="width: 100%; min-height: 135px;">
            <img width="100%" data-dz-thumbnail="" alt="" src="" style="border-radius: 5px;">
          </div>  
          <div class="dz-details" style="color: #000"">
            <div class="dz-size">
              <span data-dz-size="">
              </span>
            </div>    
            <div class="dz-filename">
              <div class="w-100 ph1 pv2 tc f2">
                <span data-dz-name="" style="width: 135px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;" title="">
                </span>
              </div>
            </div>  
          </div>  
          <div class="dz-progress">
            <span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;">
            </span>
          </div>  
          <div class="dz-error-message">
            <span data-dz-errormessage="">
            </span>
          </div>  
          <div class="dz-success-mark">    
            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <title></title>      
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">        
                <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF">
                </path>      
              </g>    
            </svg>  
          </div>  
          <div class="dz-error-mark">    
            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">      
              <title></title>      
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">        
                <g stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">          
                  <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z">
                  </path>        
                </g>      
              </g>    
            </svg>  
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>

<!-- Modal1 -->
<div class="modal fade" id="Modal1" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Directory Name?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input name="dname" type="text" value="" class="form-control" placeholder="directory" required="" autocomplete="off" id="dirname">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onClick="getDname()">Submit</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>

// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': "{{ csrf_token() }}"
//     }
// });

function ajaxFolder(dname){
   
    var xhr = new XMLHttpRequest();

    var queryString = "?dname=" + dname + "&dir={{ $urix }}";
    // window.alert(queryString);
    xhr.open("GET", "/dashboard/upload/" + queryString, true);
    xhr.send();

    xhr.onreadystatechange = function(){
      if(xhr.readyState == 4){
        location.reload(true);
      }
    }

    // setTimeout(function() {  
    //   location.reload(true);
    // }, 2000);
  }

  function ajaxRefresh(){
    var xhr = new XMLHttpRequest();

    xhr.open("GET", "/ajax{{ $_SERVER['REQUEST_URI'] }}", true);
    xhr.onreadystatechange = function(){
      if(xhr.readyState == 4){
      var ajaxDisplay = document.getElementById('listFiles');
      ajaxDisplay.innerHTML = xhr.responseText;
      }
    }
    xhr.send();

  }

</script>

<script>

var progdiv = document.getElementById('the-progress-div');
var pb = document.getElementById('pb');

Dropzone.options.dzUpload = {
  previewTemplate: document.querySelector('#tpl').innerHTML,
  parallelUploads: 20,
  uploadMultiple: true,
  // chunking: true,
  createImageThumbnails: true,
  maxFilesize: 200,
  dictDefaultMessage: "Drag and drop files or click here to upload",
  // maxFiles: 10,
  // previewsContainer: null,
  disablePreviews: false,
  url: "/dashboard/upload",

  totaluploadprogress: function totaluploadprogress(totaluploadprogress) {
    pb.style.visibility = 'visible';
    progdiv.style.width = totaluploadprogress + "%";
    progdiv.innerHTML = totaluploadprogress + "%";
  },

  // uploadprogress: function(file, progress, bytesSent) {
  //       progdiv.innerHTML = file.name + " (" + progress + "%)";
  // },

  completemultiple: function completemultiple() {
    // window.alert('complete multiple');
    console.log('complete');
    setTimeout(function() {  
      // Using "_this" here, because "this" doesn't point to the dropzone anymore
      // $(’dz-preview’).remove();
      // ajaxRefresh();
      location.reload(true);
    }, 2000);
  }
}

</script>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/scripts.js"></script>

<script>

function test(){
  console.log('test');
}

function favorite() {
  var parent = this.parentElement;

  var xhr = new XMLHttpRequest();
  xhr.open('POST', '/dashboard/ajax/flag_update', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}" );
  xhr.onreadystatechange = function () {
    if(xhr.readyState == 4 && xhr.status == 200) {
      var result = xhr.responseText;
      var data = JSON.parse(result);
      if(data.success == true && data.message == 'true'){
        // console.log('favorite = true');
        parent.classList.add('favorite');
      }
      if(data.success == true && data.message == 'false'){
        // console.log('favorite = false');
        parent.classList.remove('favorite');
      } 
    }
  };
  xhr.send("fid=" + parent.id + "&t=favorite");
}

function ajaxFlagUpdate(fid,type){
  var xhr = new XMLHttpRequest();
  var fdiv = document.getElementById(fid);

  xhr.open('POST', '/dashboard/ajax/flag_update', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}" );
  
  xhr.onreadystatechange = function () {
    if(xhr.readyState == 4 && xhr.status == 200) {
      // console.log(type);
      // if(type != 'shared'){
      //   ajaxRefresh();
      // }
      if(type == 'deleted'){
        location.reload(true);
      }
      if(type == 'favorite'){
        var result = xhr.responseText;
        var data = JSON.parse(result);
        if(data.success == true){
          if(data.message == 'true'){
            fdiv.classList.add('favorite');
          } else {
            fdiv.classList.remove('favorite'); 
          }
        }
      }
    }
   }
  xhr.send("fid=" + fid + "&t=" + type);
}

var buttons = document.getElementsByClassName("favorite-button");
for(i=0; i < buttons.length; i++) {
  buttons.item(i).addEventListener("click", favorite);
}

</script>

</body>
</html>

<?php

//Process token from AWS
require_once('../vendor/autoload.php');
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

$reauth = 'true';
if(!isset($_GET["access_token"]))
{

  ?>
  <script>
  var url_str = window.location.href;
  //On successful authentication, AWS Cognito will redirect to Call-back URL and pass the access_token as a request parameter. 
  //If you notice the URL, a “#” symbol is used to separate the query parameters instead of the “?” symbol. 
  //So we need to replace the “#” with “?” in the URL and call the page again.
  
  if(url_str.includes("#")){
    // console.log('#');
    var url_str_hash_replaced = url_str.replace("#", "?");
    window.location.href = url_str_hash_replaced;
  }

  </script>

  <?php
} else {


  $access_token = $_GET["access_token"];
  session()->invalidate();
  session()->put('authenticated', false);
  // echo $access_token;

  $region = 'us-west-1';
  $version = 'latest';

  //Authenticate with AWS Acess Key and Secret
  $client = new CognitoIdentityProviderClient([
      'version' => $version,
      'region' => $region,
  ]);

  try {
    //Get the User data by passing the access token received from Cognito
      $result = $client->getUser([
          'AccessToken' => $access_token,
      ]);
    
    // echo $result;

    //Iterate all the user attributes and get email and phone number
    $userAttributesArray = $result["UserAttributes"];
    foreach ($userAttributesArray as $key => $val) {
      if($val["Name"] == "email"){
        $user_email = $val["Value"];
      }
      if($val["Name"] == "sub"){
        $user_sub = $val["Value"];
      }
    }	

    //laravel
    session()->put('authenticated', true);
    session()->put('email', $user_email);
    session()->put('sub', $user_sub);

    $reauth = 'false';

  } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {
      echo '<font color=red><i>FAILED TO VALIDATE THE ACCESS TOKEN. ERROR = ' . $e->getMessage() . '</i></font>';
      $reauth = 'true';
    }
    catch (\Aws\Exception\CredentialsException $e) {
      echo 'FAILED TO AUTHENTICATE AWS KEY AND SECRET. ERROR = ' . $e->getMessage();
      $reauth = 'true';
    }
}  
  ?>


<!DOCTYPE html>
<html>
  <head>

  <?php 

    if($reauth == 'true'){
      echo '<meta http-equiv="refresh" content="2; url=/login" />';
      // echo 'not authenticated...';
    } else {
      echo '<meta http-equiv="refresh" content="2; url=/dashboard" />';
      //echo 'authenticated...';
    }

  ?>

  
<title>Please wait...</title>

</head>

<body>

<main role="main">

<div class="container" style="padding:20px;">
<div class="row" style="text-align: center;">
<!-- <h3 style="color:gray">Please wait...</h3> -->
<img style="-webkit-user-select: none;margin: auto;" src="https://i.giphy.com/media/PUYgk3wpNk0WA/giphy.webp">
<!-- <hr style="width:100%; margin:0px;"> -->
</div>
</div>

</body>
</html>
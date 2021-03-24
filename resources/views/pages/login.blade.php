<?php

require '../vendor/autoload.php';

if(session()->get('authenticated')){
    return Redirect::to('/dashboard')->send();
}

$url = 'https://auth.nefcloud.com/login?client_id=7shjisgsab3nh29de7sb77c29v&response_type=token&scope=aws.cognito.signin.user.admin+email+openid+profile&redirect_uri=https://nefcloud.com/auth/';
if(isset($_GET["register"]) && $_GET["register"] == 1){
    $url = 'https://auth.nefcloud.com/signup?client_id=7shjisgsab3nh29de7sb77c29v&response_type=token&scope=aws.cognito.signin.user.admin+email+openid+profile&redirect_uri=https://nefcloud.com/auth/';
}
// if(isset($_GET["logout"])){
//     $url = 'https://auth.nefcloud.com/logout?client_id=7shjisgsab3nh29de7sb77c29v&logout_uri=https://nefcloud.com';
//     Session::forget(['authenticated','email','sub']);
//     Session::flush();
// }
if(isset($_GET["google"])){
    $url = 'https://auth.nefcloud.com/oauth2/authorize?identity_provider=Google&redirect_uri=https://nefcloud.com/auth/&response_type=TOKEN&client_id=7shjisgsab3nh29de7sb77c29v&scope=aws.cognito.signin.user.admin email openid profile';
}

return Redirect::to($url)->send();

?>

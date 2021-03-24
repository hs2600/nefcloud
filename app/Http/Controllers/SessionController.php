<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// require '../vendor/autoload.php';

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class SessionController extends Controller
{
    public function accessSessionData(Request $request) {
        if($request->session()->has('email')){
            echo $request->session()->get('authenticated');   
            echo $request->session()->get('email');
            echo $request->session()->get('user_sub');
        } else
            echo 'No data in the session';
     }
    //  public function storeSessionData(Request $request) {
    //         $request->session()->put('name','');
    //         echo "Data has been added to session";
    //  }
     public function deleteSessionData(Request $request) {
        $request->session()->forget('authenticated');
        echo "Data has been removed from session.";
     }

    public function logout(Request $request) {
        $request->session()->invalidate();
        echo "Session has been cleared.";

        $url = 'https://auth.nefcloud.com/logout?client_id=7shjisgsab3nh29de7sb77c29v&logout_uri=https://nefcloud.com';
        return redirect($url)->send();
    }

     public function authenticate(Request $request) {


        // $request->session()->regenerate();
        $request->session()->invalidate();
        $request->session()->put('authenticated', false);

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
            $request->session()->put('authenticated', true);
            $request->session()->put('email', $user_email);
            $request->session()->put('sub', $user_sub);
        
            $reauth = 'false';
        
            // exit;
        
          } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {
              echo '<font color=red><i>FAILED TO VALIDATE THE ACCESS TOKEN. ERROR = ' . $e->getMessage() . '</i></font>';
              $reauth = 'true';
            }
            catch (\Aws\Exception\CredentialsException $e) {
              echo 'FAILED TO AUTHENTICATE AWS KEY AND SECRET. ERROR = ' . $e->getMessage();
              $reauth = 'true';
            }
        
        }  

        // echo $request->session()->get('authenticated');   
        // echo $request->session()->get('email');
        // echo $request->session()->get('user_sub');

        if($reauth == 'true'){
            return redirect()->intended('/login');
            // echo 'not authenticated...';
        } else {
            return redirect()->intended('/dashboard');
            // echo 'authenticated...';
        }
    }

}

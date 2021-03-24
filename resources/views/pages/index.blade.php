<?php

  $imageArray = array(
    1 => "andrew-ruiz-JJwS0NMCJYc-unsplash.jpg",
    2 => "cristina-gottardi-JiLTJODH5j4-unsplash.jpg",
    3 => "dave-hoefler-4pvfpUkmR6I-unsplash.jpg",
    4 => "divya-agrawal-UiYUVKe3gPs-unsplash.jpg",
    5 => "james-wheeler-XuAxyq0uRT0-unsplash.jpg",
    6 => "jeff-hopper-L_b17zx5H-c-unsplash.jpg",
    7 => "jonathan-bean-sbZU1j31ggE-unsplash.jpg",
    8 => "lucas-benjamin-wQLAGv4_OYs-unsplash.jpg",
    9 => "lucas-ludwig-8ARg12PU8nE-unsplash.jpg",
    10 => "mehmet-turgut-kirkgoz-aeEDNKxuWjE-unsplash.jpg",
    11 => "michael-d-rnKqWvO80Y4-unsplash.jpg",
    12 => "nancy-reid-e8N7qnXVErs-unsplash.jpg",
    13 => "paul-bryan-Uo2pXm4u40E-unsplash.jpg",
    14 => "peter-vanosdall-ktpyjH2h9xs-unsplash.jpg",
    15 => "piermanuele-sberni-2mMMEVUdXcs-unsplash.jpg",
    16 => "rohan-aggarwal-EShGwCZs3Nc-unsplash.jpg",
    17 => "sepp-rutz-hZQOby6ZdIE-unsplash.jpg",
    18 => "thought-catalog-wyEinDRV88I-unsplash.jpg",
    19 => "tianshu-liu-aqZ3UAjs_M4-unsplash.jpg",
    20 => "vitalis-hirschmann-mG5jTwsXcEI-unsplash.jpg",
    21 => "wong-zihoo-DBtgQI-9XdM-unsplash.jpg",
    22 => "zetong-li-rXXSIr8-f9w-unsplash.jpg",
    23 => "CA/designcue-z6oS9pVZghE-unsplash.jpg",
    24 => "CA/maarten-van-den-heuvel-gZXx8lKAb7Y-unsplash.jpg",
    25 => "CA/matthew-hamilton-3RlGBpFeoQg-unsplash.jpg",
    26 => "CA/mat-weller-8_Rr7Opvc8Q-unsplash.jpg",
    27 => "CA/patrick-perkins-Pg44v2M2S6k-unsplash.jpg",
    28 => "CA/sterling-davis-4iXagiKXn3Y-unsplash.jpg"

    );  

    $randomImageNumber = array_rand($imageArray, 1);
    $image = $imageArray[$randomImageNumber];
    // echo $image; 
    // $image = 'CA/patrick-perkins-Pg44v2M2S6k-unsplash.jpg';

?>


<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.80.0">
    <title>Nefcloud.com</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">    
    <link href="/assets/font-awesome/css/all.css" rel="stylesheet">    
    <link href="/assets/css/dropzone.css" rel="stylesheet">
    <script src="/assets/js/dropzone.js"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Abril Fatface">
    
    <style>
    
    .landing-headline{
        font-family:Abril Fatface, serif;
        font-weight:400;
        font-size:4.5rem;
        line-height:1;
        text-align:center;
    }
    .footer-headline{
        font-family:Abril Fatface, serif;
        font-size:2.5rem;
        line-height:1;
    }    
    
    .main-div{
        background-image: url("/assets/images/bg/{{ $image }}");
        background-attachment: fixed;
        background-size: cover;
        padding: 0px; 
        margin: 0px; 
    }
    .transparent-bg{
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        text-align: center;
    }

    .height-100 {
    min-height: 100vh;
}

.share a {
    /* --tw-text-opacity: 1; */
    color: rgba(255, 255, 255, var(--tw-text-opacity));
    /* --tw-border-opacity: 0.5; */
    border: 0;
    border-bottom-width: 1px;
    border-style: dotted;
    text-decoration: inherit;
    font-size: 1.5rem;
    line-height: 2rem;
    font-family: "Courier New", monospace;
    font-weight: 700;
    margin-top: 1.5rem;
}

.share {
    border-radius: 5px;
    background-color: rgba(255,255,255,0.2);
    border: 1px solid white;
    border-style: dotted;
}


.dropzone {
    clear: both;
    background-color: rgba(255,255,255,0.2);
    /* margin-bottom: 25px; */
    border: 2px dashed #fff;
    border-radius: 5px;
    padding-top: 30px !important;
    min-height: 120px;
    /* padding: 20px 20px; */
}

html {
  scroll-behavior: smooth;
}

</style>
    
</head>
<body class="text-center">

<div class="container-fluid main-div">
  <div class="transparent-bg cover-container height-100 d-flex w-100 h-100 p-3 mx-auto flex-column">
   <div class="container" style="border: 0px solid red;">
    <header class="d-flex flex-column flex-md-row">
      <div class="container">
        <div class="row justify-content-between" style="border: 0px solid red;">
          <div class="col-6" style="border: 0px solid red;">
            <nav class="navbar navbar-expand-md p-0">
              <a class="navbar-brand p-0" href="/">
                <img src="/assets/images/logo_icon.png" height="45" class="d-inline-block align-top" alt="" loading="lazy">
              </a>
		<button class="navbar-toggler" type="button"
data-bs-toggle="collapse" data-bs-target="#navbarDefault"
aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
		<i class="fas fa-bars m-0 p-1 px-2" style="font-size: 30px; color: white; border: 1px solid #e5e5e5; border-radius: 5px;"></i>
	    </button>
              <div class="navbar-collapse collapse text-right" id="navbarDefault" style="">
	<a class="nav-link text-light pt-3" href="#">About</a>
              <a class="nav-link text-light pt-3" href="#">Features</a>
              <a class="nav-link text-light pt-3" href="#">Support</a>
              <a class="nav-link text-light pt-3" href="#">Pricing</a>
	    </div>
            </nav>
          </div>
          <div class="col text-right pt-2 p-0" style="border: 0px solid green;">
          <?php if(session()->get('authenticated')){
            ?>
            <a class="py-1 px-2 btn border-white btn-primary text-white" href="/dashboard">Dashboard</a>
            <a class="py-1 px-2 btn border-white btn-outline-primary text-white" href="/logout">Logout</a>
            <?php
          } else {
            ?>
            <a class="py-1 px-2 btn border-white btn-outline-primary text-white" href="/login" style="width: 75px;">Login</a>
            <a class="py-1 px-2 btn btn-primary" href="/login?register=1" style="width: 75px;">Sign up</a>
            <?php
          }
          ?>
          </div>
        </div>       
      </div>

    </header>
    <div class="container share mt-3 p-1" style="border: 1px solid red;">
      <div class="row d-flex justify-content-center p-0">
        <p class="m-0">
        <i class="fas fa-info-circle p-1"></i> Note: This website is currently in an Alpha version and it's <b>invite only</b>
        <br><i>Last update: 03/09/21</i>
        </p>

      </div>
    </div>      
    <main class="">
      <div class="row justify-content-center">
        <div class="py-5" data-aos="zoom-in-down" style="border: 0px solid green; max-width: 680px;">
        <span class="landing-headline">Easy and secure access to all of your content.</span>
        </div>
      </div>
      <p class="lead">From documents, images, music, videos and more - NefCloud makes it easy to store and share your data.</p>
      <div  class="share py-4 m-5" id="share-cont" style="display: none;">
        <div class="p-0">
          <h2>Your file is ready to share!</h2>
          <div class="pb-3 py-2" id="url">
            <a id="download-url" href="https://nefcloud.com" target="_blank" rel="noopener noreferrer">https://nefcloud.com</a>
          </div>
        </div>
        <div class="actions">
          <div class="" data-clipboard-target="#download-url">
            <button class="btn border-white btn-outline-secondary text-white mx-2" id='copy' style="width: 120px;" onClick="copyToClipboard()">
              <i class="far fa-copy"></i> Copy Link</button>
            <button class="btn border-white btn-outline-primary text-white" id='reset' style="width: 100px;">
              <i class="fas fa-times"></i> Done</button>
          </div>
        </div>
      </div>

      <div class="m-0 pb-4 py-3 rounded"> 
        <div class="f2 text-center p-0">As a guest, you can upload and share files. Register to access more features.</div>
        <form action="/guest/upload"
        class="dropzone text-left p-0" id="dzUpload" style="display: block; font-size: 25px;">
        @csrf 
      </form>
      </div>
      <p>
        <a href="#features" class="btn btn-lg btn-light scroll-link">Learn more</a>
      </p>
    </main>

  </div>
</div>
</div>

<div class="container" id="features">
  {{-- <div class="row">
    <div class="col-sm-6 mb-11">
      <div class="card" data-aos="fade-up">
        <div class="card-body" data-aos="fade-up">
          <pre><code class="html"><div data-aos="fade-up"></div></code></pre>
        </div>
      </div>
    </div>
    <div class="col-sm-6 mb-11">
      <div class="card" data-aos="fade-down">
        <div class="card-body" data-aos="fade-down">
          <pre><code><div data-aos="fade-down"></div></code></pre>
        </div>
      </div>
    </div>
  </div> --}}

  <div class="row py-5" style="border: 0px solid red;">
    <div class="col-md card p-2 m-1" data-aos="fade-up">
      <i class="fas fa-download" style="font-size:50px; color: #322E2B;"></i>
      <h2>Access</h2>
      <p>Always have your important files with you. Never forget your work at home. View, manage, and share from anywhere.</p>      
    </div>
    <div class="col-md card p-2 m-1" data-aos="fade-down">
      <i class="fas fa-share-square" style="font-size:50px; color: #322E2B"></i>
      <h2>Easy sharing</h2>
      <p>Share folders and files - big or small - with anyone. NefCloud makes it easy to share with a link. </p>
    </div>
    <div class="col-md card p-2 m-1" data-aos="fade-up">
      <i class="fas fa-shield-alt" style="font-size:50px; color: #322E2B"></i>
      <h2>Stay secure</h2>
      <p>
        Keep your files private with multiple layers of protection. The files are encrypted using server-side encryption.
      </p>
    </div>
    <div class="col-md card p-2 m-1" data-aos="fade-down">
      <i class="fas fa-hdd" aria-hidden="true" style="font-size:50px; color: #322E2B;"></i>
      <h2>20GB Free</h2>
      <p>With up to 20GB of free space, you can use NefCloud to back up all your important files.</p>
    </div>
  </div>
</div>

<div class="container-fluid py-4" style="background-color: #45B7F8;">
  <div class="container">
    <div class="row">
      <div class="col-md-8 pr-5 text-justify">
        <h1>Secure</h1>
        <h4>
        Your files are safe with us.
        <br>
        All files stored on our servers are encrypted when they are uploaded. The files are encrypted using server-side encryption (SSE).
        With server-side encryption, we encrypt a file before saving it to disk and decrypt it when you download it.
        </h4>
      </div>
      <div class="col-md-4" data-aos="fade-right">
        <img src="/assets/images/secure-file-sharing.png" width="100%">
      </div>        
    </div>
  </div>
</div>

<div class="container-fluid py-5" style="background-color: #fff;">
  <div class="container">
    <div class="row">
      <div class="col-auto col-md-9 text-left" data-aos="fade-right">
        <span class="footer-headline">Convenient, anonymous and secure. Get NefCloud.</span>
      </div>
      <div class="col-auto col-md-3 text-center">
        <?php if(session()->get('authenticated')){
          ?>
          <a class="btn btn-lg btn-primary" href="/dashboard">Dashboard</a>
          <?php
        } else {
          ?>
        <a class="btn btn-lg btn-primary" href="/login?register=1">Register</a>
        {{-- <a href="#" class="btn btn-lg btn-secondary">Demo</a> --}}
          <?php
        }
        ?>
      </div>        
    </div>
  </div>
</div>

@include('inc.footer')

<script>
    var sharecont = document.getElementById('share-cont');
    var form = document.getElementById('dzUpload');

    Dropzone.options.dzUpload = {
    uploadMultiple: false,
    // createImageThumbnails: false,
    maxFiles: 1,
    maxFilesize: 100,
    // previewsContainer: null,
    // disablePreviews: true,
    init: function() {
      this.on("success", function(file, response) {
          console.log(response);
          console.log(file);
          var obj = JSON.parse(response);
          document.getElementById("url").innerHTML = "<a id='download-url' href='http://localhost:8000/sh/" + obj.file_id + "' target='_blank' rel='noopener noreferrer'>http://localhost:8000/sh/" + obj.file_id + "</a>";
          var input = document.getElementById("dummy_id")
          input.style.display = 'block';
          input.value="http://localhost:8000/sh/" + obj.file_id;
          input.style.display = 'none';
          setTimeout(function() {            
            sharecont.style.display = 'block';
            form.style.display = 'none';
          }, 1500);
      });
      this.on("addedfile", function(file) {
        // window.alert('addedfile');
        var _this = this;
        var resetButton = document.getElementById('reset');
        resetButton.addEventListener("click", function(e) {
          setTimeout(function() {  
            form.style.display = 'block';
            sharecont.style.display = 'none';
            _this.removeFile(file);
          }, 200);
        });
      });
  }
};
</script>

<script>
  function copyToClipboard(val){
    var input = document.getElementById("dummy_id")
    input.style.display = 'block';
    input.select();
    document.execCommand("copy");
    input.style.display = 'none';

    setTimeout(function(){
      document.getElementById('copy').innerHTML = "<i class='far fa-copy'></i> Copied"
    }, 100); // seconds
    setTimeout(function(){
      document.getElementById('copy').innerHTML = "<i class='far fa-copy'></i> Copy Link"
    }, 1000); // seconds
    // window.alert('copied!');
}
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<input id="dummy_id" style="display:none;">

<script>
    // INITIALIZATION OF AOS
    // =======================================================
    AOS.init({
      duration: 1000,
      once: true
    });
</script>

</body>
</html>

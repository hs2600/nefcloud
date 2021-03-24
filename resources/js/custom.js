


  function addCode(name, ID) { 
    document.getElementById("title").innerHTML =  name;
    // document.getElementById("share").innerHTML =  
    //   "<a href=\"/" + ID + "\" style=\"color: #000; text-decoration: none;\">Share</a>";
    document.getElementById("copy").innerHTML =  
      "<a href=\"#\" class=\"px-2 py-1 hover-bg-blue2\" style=\"color: #000; text-decoration: none;\" onclick=\"copyToClipboard('" + ID + "')\" id=\"copylink\">Copy link</a>";
    document.getElementById("delete").innerHTML =  
      "<a href=\"#\" class=\"px-2 py-1 hover-bg-blue2\" data-bs-dismiss=\"modal\" style=\"color: #000; text-decoration: none;\" onclick=\"ajaxDBUpdate('" + ID + "','deleted')\">Delete</a>";
    document.getElementById("star").innerHTML =  
      "<a href=\"#\" class=\"px-2 py-1 hover-bg-blue2\" data-bs-dismiss=\"modal\" style=\"color: #000; text-decoration: none;\" onclick=\"ajaxDBUpdate('" + ID + "','favorite')\">Star</a>";
       
  } 


  function copyToClipboard(val){
    var input = document.getElementById("dummy_id")

    input.style.display = 'block';
    // input.value="https://nefcloud.com/file/?file="+val;
    input.value="localhost:8000/file/?fid="+val;
    input.select();
    document.execCommand("copy");
    input.style.display = 'none';
    ajaxDBUpdate(val,'shared');
    setTimeout(function(){
      document.getElementById('copylink').innerHTML = "<b>Copied!</b>"
    }, 100); // seconds
    setTimeout(function(){
      document.getElementById('copylink').innerHTML = "Copy Link"
    }, 2000); // seconds
}

function ajaxDBUpdate(fid,type){
  var ajaxRequest;  // The variable that makes Ajax possible!
  
  try {
     // Opera 8.0+, Firefox, Safari
     ajaxRequest = new XMLHttpRequest();
  } catch (e) {
     // Something went wrong
     alert("Your browser broke!");
     return false;
  }
  
  var queryString = "?fid=" + fid + "&t=" + type;

  ajaxRequest.open("GET", "/dashboard/flag_update" + queryString, true);
  ajaxRequest.send(null); 

  if(type != 'shared'){
    ajaxRefresh();
  }
}


  function getDname(){
    var input = document.getElementById("dirname");
    val = input.value;
    input.value = "";

    ajaxFolder(val);
    // window.alert(val);

}

  var progdiv = document.getElementById('the-progress-div');
  var pb = document.getElementById('pb');

  Dropzone.options.dzUpload = {
  previewTemplate: document.querySelector('#tpl').innerHTML,
  parallelUploads: 20,
  uploadMultiple: true,
  createImageThumbnails: true,
  maxFilesize: 200,
  dictDefaultMessage: "Drag and drop files or click here to upload",
  // maxFiles: 10,
  // previewsContainer: null,
  disablePreviews: true,

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
    setTimeout(function() {  
      // Using "_this" here, because "this" doesn't point to the dropzone anymore
      // $(’dz-preview’).remove();
      // ajaxRefresh();
      location.reload(true);
    }, 3000);
  }

};

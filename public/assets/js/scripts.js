
function addCode(name, ID) { 
  document.getElementById("title").innerHTML =  name;
  document.getElementById('copylink').onclick = function(){
    copyToClipboard(ID)
  }
  document.getElementById('delete').onclick = function(){
    ajaxDBUpdate(ID,'deleted')
  }
  document.getElementById('star').onclick = function(){
    ajaxDBUpdate(ID,'favorite')
  }
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
    document.getElementById('copy').innerHTML = "<b>Copied!</b>"
  }, 100); // seconds
  setTimeout(function(){
    document.getElementById('copy').innerHTML = "Copy Link"
  }, 2000); // seconds
}

function ajaxDBUpdate(fid,type){
  var xhr = new XMLHttpRequest();
  
  var queryString = "?fid=" + fid + "&t=" + type;

  xhr.open("GET", "/dashboard/flag_update" + queryString, true);
  xhr.onreadystatechange = function () {
    if(xhr.readyState == 4 && xhr.status == 200) {
      if(type != 'shared'){
        ajaxRefresh();
      }
      // location.reload(true);       
    }
   }
  xhr.send();
}

function getDname(){
  var input = document.getElementById("dirname");
  val = input.value;
  input.value = "";

  ajaxFolder(val);
  // window.alert(val);
}


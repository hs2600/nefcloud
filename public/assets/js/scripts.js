
function fileMenu(name, ID) { 
  document.getElementById("title").innerHTML =  name;
  document.getElementById('copylink').onclick = function(){
    copyToClipboard(ID)
  }
  document.getElementById('delete').onclick = function(){
    ajaxFlagUpdate(ID,'deleted')
  }
  document.getElementById('star').onclick = function(){
    ajaxFlagUpdate(ID,'favorite')
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
  ajaxFlagUpdate(val,'shared');
  setTimeout(function(){
    document.getElementById('copy').innerHTML = "<b>Copied!</b>"
  }, 100); // seconds
  setTimeout(function(){
    document.getElementById('copy').innerHTML = "Copy Link"
  }, 2000); // seconds
}

function getDname(){
  var input = document.getElementById("dirname");
  val = input.value;
  input.value = "";

  ajaxFolder(val);
  // window.alert(val);
}



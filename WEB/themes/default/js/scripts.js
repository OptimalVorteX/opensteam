function showhide(id){
  if (document.getElementById){
  obj = document.getElementById(id);
     if (obj.style.display == "none"){
     obj.style.display = "block";
    } else {
           obj.style.display = "none";
           }
    }
}

function ToClipboard (text) {
  window.prompt ("Press Ctrl+C to copy on clipboard", text);
}
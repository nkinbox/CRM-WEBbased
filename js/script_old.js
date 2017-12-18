function check() {
$.ajax({url: "checkmail.php", success: function(data){
if(data == "0") {
document.cookie = "mails=0";
}
if(document.cookie != "mails="+data) {
  alert("New Mail Received");
  $("a[href='mail.php']").html("Inbox <span class='badge'>" + data + "</span>");
  document.cookie = "mails="+data;
}

setTimeout(check, 6000);
}, cache: false});
}

$(document).ready(function(){
if(document.cookie == "")
document.cookie = "mails=0";
check();
});
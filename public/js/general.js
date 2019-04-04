var mobileMenuOpen = false;
$( "#phone-trigger-menu" ).click(function() {
  openMenu();
});

function openMenu(){
    if(mobileMenuOpen == false){
        mobileMenuOpen = true;
        $("#phone-menu").show();
    }
    else{
      mobileMenuOpen = false;
      $("#phone-menu").hide();
    }
}

var notifDelay = 4000;
var priorityParam = {high:8000,medium:6000,low:2000};

function createNotif(notif){
    //notif = JSON.parse(notif)
    var list = $("#notif-list");
    var message = notif.message;
    var time = notifDelay;

    if(notif.type !== undefined){
        var elem = $('<li>', {class:notif.type,style:"display:none",text:message});
    }
    else{
       var elem = $('<li>', {class:'info',style:"display:none",text:message}); 
    }
    
    

    /*priority*/
    if(notif.priority !== undefined){
        if(priorityParam[notif.priority]){
            time = priorityParam[notif.priority];
        }
    }
    
    list.append(elem);
    elem.animate({width: "toggle",height: "toggle" }, 500).delay(time).animate({top:"-=500"},500, function() {
        console.log("anim complete");
        elem.remove();
    });

}

$(document).ready(function(){
    var list = $("#notif-list");
    list.html("");
    var elem = $('<li>', { class: 'test' ,text:'test'});
    //list.append(elem);
});
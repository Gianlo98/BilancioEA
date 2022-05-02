$(document).ready(function(){
    if(window.location.hash === "") {
        window.location.hash = "dashboard";
    }else{
        switchModuleHash();
    }
});

$(window).on('hashchange', function() {
    switchModuleHash();
});

function switchModuleHash(){
    moduleName = leaveHash(window.location.hash);
    switchModule(moduleName);
}

function leaveHash(hash){
    return hash.substring(1,hash.lenght);
}

function switchModule(moduleName){
    $(".content-wrapper").load("modules/" + moduleName + ".php",function(responseText, statusText, xhr){
        if(xhr.status == 404){
          $(".content-wrapper").load("modules/" + moduleName + ".html",function(responseText, statusText, xhr){
              if(xhr.status == 404){
                  window.location.hash = "dashboard";
              }
          });
        }
    });
}

$(document).ready(function() {
    $(".color-display a").click(function(e) {
        e.preventDefault();
        $(".color-display").toggleClass("color-display-active");
    });
    $("#header .menu-btn").click(function(e) {
        e.preventDefault();
        $(".menu-btn").toggleClass("menu-btn-active");
        $(".mb-nav").toggleClass("mb-nav-active");
    });
    $("#header .panel-btn").click(function(e) {
        e.preventDefault();
        $(".panel-btn").toggleClass("panel-btn-active");
        $(".mb-panel").toggleClass("mb-panel-active");
    });

});
function show_menu_user() {
    $(".arrow-down").toggleClass("arrow-down-active");
    $(".nav_user_drop").toggleClass("nav_user_drop-active");
    return false;
}
function sets(a){a="http://www.addthis.com/bookmark.php?s="+a+"&pub=tvnet";a+="&url="+encodeURIComponent(location.href);a+="&title="+encodeURIComponent(document.title);a+="&winname="+window.name;window.open(a,"bookmark","scrollbars=yes,menubar=no,width=800,height=600,resizable=yes,toolbar=no,location=no,status=no,screenX=200,screenY=100,left=200,top=100")}function setcc(a){$.cookie("cat_id",a,{expires:7,path:"/"})}
function setbookmark(){var a=location.href,b=document.title,c=navigator.userAgent.toLowerCase();/msie/.test(c)?window.external.AddFavorite(a,b):/firefox/.test(c)?alert("Press <Ctrl + D> to bookmark in FireFox"):/opera/.test(c)?alert("Press <Ctrl + D> to bookmark in Opera"):/chrome/.test(c)&&alert("Press <Ctrl + D> to bookmark in Chrome")}



var page = $(".upAndDown");
var ul = $(".screen ul");
var num = 0;
var heightNum = parseInt(ul.height()/39);
if(heightNum>1){
    page.css("display","block").on("click",function(){
        num = (num+1)%heightNum;
        ul.css("top",-num*39+"px");
    });
}

+(function(factory){
	if(typeof PAGETYPE == "undefined"){
		PAGETYPE = document.documentElement.id;
	}
	(PAGETYPE == 'detail') && factory();
}(function(){
	$(function(){
		var preLoad = new PreLoad();
	})
}))



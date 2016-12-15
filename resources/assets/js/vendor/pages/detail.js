+(function(factory){
	if(typeof PAGETYPE == "undefined"){
		PAGETYPE = document.documentElement.id;
	}
	(PAGETYPE == 'detail') && factory();
}(function(){
	$(function(){
		var preLoad = new PreLoad({
			el: $("#main"),
			bookid: CHAPTER_INFO.book_id,
			current: CHAPTER_INFO.chapter_id,
			total: CHAPTER_INFO.chapter_num
		});
	})
}))



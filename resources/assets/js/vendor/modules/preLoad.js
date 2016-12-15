+(function(factory){
  window.PreLoad = factory();
}(function(){
  var PreLoad = function(opt){
    this.cache = [];
    this.options = $.extend({},arguments.callee.options,opt);
    if(!history.pushState) return false;
    this.init();
  }
  $.extend(PreLoad.prototype,{
    init: function(){
      var that = this;
      this.$root = $('html,body');
      this.$view = $(".view-page");
      this.$content = this.$view.find('>.detail-box>.content');
      this.options.page = location.href;
      $(window).on("load",function(){
        that.preload();
      });
      $(window).on("popstate",function(){
        var current = history.state;
        var page = history.state.page;
        that.loadPage(page);
      });
      $(document).on("click","a",function(e){
        if(this.href.match(/books\/(\d+)\/chapters\/(\d+)/)){
          e.preventDefault();
          var page = this.href;
          var pageObj = that.loadPage(page);
          history.pushState({page:page},'',this.href);
        }
      });
    },
    loadPage: function(page){
      var pageObj = _.find(this.cache,function(item){
        return item.page == page;
      });
      if(!pageObj){
        pageObj = this.addPage(page);
      }
      if(pageObj.ajax.state() == 'resolved'){
        this.update(pageObj);
      }
      return pageObj;
    },
    addPage: function(page){
      var that = this;
      var pageObj = _.find(this.cache,function(item){
        return item.page == page;
      });
      if(pageObj){
        return pageObj;
      }
      pageObj = {
        page: page
      }
      pageObj.ajax = $.get(page)
        .done(function(res){
          var $doc = $(res);
          pageObj.title = $doc.filter('title').html();
          pageObj.content = $doc.find('.view-page').html();
          if(history.state && history.state.page == pageObj.page){
            that.update(pageObj);
          }
        });
      this.cache.push(pageObj);
      if(this.cache.length >= this.options.maxCache){
        this.cache.shift();
      }
      return pageObj;
    },
    preload: function(){
      this.addPage(this.$view.find('.next-page').attr('href'));
    },
    update: function(pageObj){
      this.options.page = pageObj.page;
      document.title = pageObj.title;
      this.$root.scrollTop(this.$view.offset().top);
      try{
        this.$view.html(pageObj.content);
      }catch(e){
        console.log('has script');
      }finally{
        this.preload();
      }
    }
  });
  $.extend(PreLoad,{
    options:{
      maxCache: 20
    }
  });
  return PreLoad;

}))
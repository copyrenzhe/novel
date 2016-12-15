+(function(factory){
  window.PreLoad = factory();
}(function(){
  var PreLoad = function(opt){
    this.$el = $(opt.el);
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
      this.options.page = this.$content.data('page');
      $(window).on("load",function(){
        var detail = location.href.match(/books\/(\d+)\/chapters\/(\d+)/);
        that.addPage(parseInt(detail[2])+1);
      });
      $(window).on("popstate",function(){
        var current = history.state;
        var page = history.state.page;
        that.loadPage(page);
      });
      $(document).on("click","a",function(e){
        var detail = this.href.match(/books\/(\d+)\/chapters\/(\d+)/);
        if(detail){
          e.preventDefault();
          var page = parseInt(detail[2]);
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
        this.addPage(page+1);
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
      pageObj.ajax = $.get('/books/'+this.options.bookid+'/chapters/'+page)
        .done(function(res){
          var $doc = $(res);
          pageObj.title = $doc.filter('title').html();
          pageObj.content = $doc.find('.view-page').html();
          if(history.state.page == pageObj.page){
            that.update(pageObj);
          }
        });
      this.cache.push(pageObj);
      if(this.cache.length >= this.options.maxCache){
        this.cache.shift();
      }
      return pageObj;
    },
    update: function(pageObj){
      console.log(pageObj)
      this.options.page = pageObj.page;
      document.title = pageObj.title;
      this.$view.html(pageObj.content);
      //this.$root.scrollTop(this.$view.offset().top);
    }
  });
  $.extend(PreLoad,{
    options:{
      bookid: 1,
      current: 1,
      maxCache: 20
    }
  });
  return PreLoad;

}))
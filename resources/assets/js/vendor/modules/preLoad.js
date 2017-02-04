+(function(factory){
  window.PreLoad = factory();
}(function(){
  var PreLoad = function(opt){
    this.cache = [];
    this._cache = {};
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
      
      $(window).on("popstate",function(){
        var current = history.state;
        var page = location.href;
        that.showPage(page);
      });
      $(document).on("click","a",function(e){
        if(this.href.match(/books\/(\d+)\/chapters\/(\d+)/)){
          e.preventDefault();
          var page = this.href;
          var pageObj = that.showPage(page);
          history.pushState({page:page},'',this.href);
        }
      });
      this._init();
    },
    _init: function(){
      var that = this;
      var pageObj = {
        page: location.href,
        title: document.title,
        content: $(document.body).find('.view-page').html()
      }
      pageObj.ajax = $.Deferred();
      pageObj.ajax.resolve();

      this.cache.push(pageObj);
      this._cache[pageObj.page] = pageObj;
      $(window).on("load",function(){
        that.createPageObj(that.$view.find('.next-page').attr('href'));
      });
    },
    showPage: function(page){
      var pageObj = this._cache[page];
      if(!pageObj){
        this.createPageObj(page);
      }else if(pageObj.ajax.state() == 'resolved'){
        this.current = pageObj;
        document.title = pageObj.title;
        this.$root.scrollTop(this.$view.offset().top);
        try{
          this.$view.html(window.ad_filter(pageObj.content));
        }catch(e){
          console.log('has script');
        }finally{
          this.createPageObj(this.$view.find('.next-page').attr('href'));
        }
      }else{
        this.safeMode();
      }
    },
    createPageObj: function(page){
      var that = this;
      if(that._cache[page]) return;
      var pageObj = {
        page:page,
        tryCount:0
      }
      that.fetchPage(pageObj);

      that.cache.push(pageObj);
      that._cache[page] = pageObj;
      if(that.cache.length >= that.options.maxCache){
        var rpo = that.cache.shift();
        delete that._cache[rpo[page]];
      }
    },
    fetchPage: function(pageObj){
      var that = this;
      pageObj.tryCount++;
      var ajax = $.get(pageObj.page).done(function(res){
        var $doc = $(res);
        pageObj.title = $doc.filter('title').html();
        pageObj.content = $doc.find('.view-page').html();
        if(window.location.href == pageObj.page){
          that.showPage(pageObj.page);
        }
      }).fail(function(){
        if(pageObj.tryCount>5) return;
        console.log('网络错误,1秒后重试');
        setTimeout(function(){
          that.fetchPage(pageObj);
        },1000);
      })
      pageObj.ajax = ajax;
    },
    safeMode: function(){
      var that = this;
      clearTimeout(this._safeid);
      console.log('用户观看欲望十分强烈，必须要启用安全模式啦！');
      this._safeid = setTimeout(function(){
        if(window.location.href != that.current.page){
          location.reload();
        }
      },3000)
    }
  });
  $.extend(PreLoad,{
    options:{
      maxCache: 20
    }
  });
  return PreLoad;

}))
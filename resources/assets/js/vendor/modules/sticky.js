+(function(factory){
  window.Sticky = factory();
}(function(){
  var Sticky = function(opt){
    this.options = $.extend(true,{},arguments.callee.options,opt);
    if(this.options.stickyoffset != undefined){
      this.options.offset = this.options.stickyOffset;
    }
    this.$el = $(this.options.el);
    this.init();
  }

  $.extend(Sticky.prototype,{
    init: function(){
      this.createWrapper();
      var originalHandler = this.options.handler
      this.options.static = this.$el.css('position') == "static";
      this.options.height = this.$el.outerHeight(true);

      this.waypoint = new Waypoint($.extend({}, this.options, {
        element: this.wrapper,
        handler: $.proxy(function(direction) {
          var shouldBeStuck = this.options.direction.indexOf(direction) > -1
          var wrapperHeight = shouldBeStuck ? this.options.height : ''

          this.options.static && this.$wrapper.height(wrapperHeight)
          this.$el.toggleClass(this.options.stuckClass, shouldBeStuck)

          if (originalHandler) {
            originalHandler.call(this, direction)
          }
        }, this)
      }))
    },
    createWrapper : function() {
      this.$el.wrap('<div class="sticky-wrapper" />');
      this.$wrapper = this.$el.parent().addClass(this.options.wrapperclass);
      this.wrapper = this.$wrapper[0]
    },
    destroy : function() {
      if (this.$el.parent()[0] === this.wrapper) {
        this.waypoint.destroy()
        this.$el.removeClass(this.options.stuckClass).unwrap()
      }
    }
  });

  $.extend(Sticky,{
    options: {
      stuckClass: 'stuck',
      wrapperclass: '',
      direction: 'down right',
      handler: $.noop
    }
  });

  return Sticky;

}))
  
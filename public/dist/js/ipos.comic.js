// limit number
(function($) {
    $.fn.extend( {
        limiter: function(limit, elem) {
            $(this).on("keyup focus", function() {
                setCount(this, elem);
            });
            function setCount(src, elem) {
                var chars = src.value.length;
                if (chars > limit) {
                    src.value = src.value.substr(0, limit);
                    chars = limit;
                }
                elem.html( limit - chars );
            }
            setCount($(this)[0], elem);
        }
    });
})(jQuery);


$(document).ready(function(){
    var login_post_ck = {postname:'log_panel'}
    $.post(ajaxurl, login_post_ck, function(data){
        $('div#log_panel').html(data);
    });
    var login_post_ck = {postname:'login_ajax'}
    $.post(ajaxurl, login_post_ck, function(data){
        $('div.mb-panel').html(data);
    });
    //$("html").niceScroll();
    //$("#nav a").tipsy({gravity:"s"});
    $("div#nav ul li").hover(function(){
        $(this).find("div:first").css({visibility:"visible",display:"none"}).show(300)
    },function(){
        $(this).find("div:first").css({visibility:"hidden"})
    });
    $("#tabshow a").click(function(){
        $('#tabshow a.activer').removeClass('activer');
        $(this).addClass('activer');
        var	tabid	=	$(this).attr('rel');
        $('div.showtab').hide();
        $(tabid).fadeIn(300);
        return false;
    });

    $(".txt,#searchInput").focus(function() {
        if (this.value === this.defaultValue) {
            this.value = '';
        }
    }).blur(function() {
        if (this.value === '') {
            this.value = this.defaultValue;
        }
    });

    $("#chapter-search").keyup(function(event) {
        var	vname	=	$(this).val()
        loading_chapter(vname);
    });

    $("#searchInput,#searchInput_mobile").autocomplete("search.php",{width:313,max:20,highlight:!1,scroll:!1});
    //$("#searchInput_mobile").autocomplete("search.php",{width:313,max:20,highlight:!1,scroll:!1});
})
var	ajaxurl	=	'ajax.php';
function EnterKey(a){13==(window.event?window.event.keyCode:a.which)&&do_search()}
function do_search(a){if(searchid=$(".tgt-autocomplete-activeItem a").attr("href"))return window.location.href=searchid,!1;1==a&&(query=$("input[name='query']").val(),window.location.href="./search/"+query+".html");return!1}
function do_search_mobile(a){if(searchid=$(".tgt-autocomplete-activeItem a").attr("href"))return window.location.href=searchid,!1;1==a&&(query=$("input[name='query_mobile']").val(),window.location.href="./search/"+query+".html");return!1}
function loading_chapter(type) {
    var chapter_post = {
        postname:'chapter',
        ten:ten,
        cid:cid,
        type:type
    }
    $.post(ajaxurl, chapter_post, function(html){
        $("#_pchapter").html(html);
        $("#ztitle a").tipsy({gravity:"s"});
        $("#_pchapter").niceScroll();
    });
}
function change_captcha() {
    document.getElementById('img_captcha').src="./captcha/image.php?rnd=" + Math.random();
}

document.onkeydown=function(e) {
    e=e||window.event;
    if (e.keyCode === 116 ) {
        e.keyCode = 0;
        var	url_cache	=	$('input[name="url-cache"]').val();
        if(url_cache!=undefined) {
            $.post(ajaxurl,{postname: 'cache',url_cache:url_cache},
                function(data) {
                    window.location=url_cache;
                });
        }
        if(e.preventDefault)e.preventDefault();
        else e.returnValue = false;
        return false;
    }
}

function submit_login() {
    var username = $("input[name='tt-username']").val();
    var password = $("input[name='tt-password']").val();
    var remember = 0;
    if ($("input[name='tt-remember']").is(":checked"))
        remember	=	1;
    var login_post = {
        postname:'login',
        username:username,
        password:password,
        remember:remember
    }
    $.post(ajaxurl, login_post, function(html){
        if(html == 1) {
            alert("Đăng nhập thành công!");
            window.location	=	'/';
        }else {
            alert(html);
        }
    });
    return false;
}

function submit_like(cid) {
    var like_post = {
        postname:'like',
        cid:cid
    }
    $.post(ajaxurl, like_post, function(numz){
        if(numz==0)
            alert('Bạn chưa đăng nhập!');
        else if(numz==1)
            $("#Like a").html('Bạn thích truyện này!');
        else if(numz==2)
            $("#Like a").html('Bạn đã hết thích truyện này');
    });
}

function submit_bm(cid,eid) {
    var bm_post = {
        postname:'bm',
        cid:cid,
        eid:eid
    }
    $.post(ajaxurl, bm_post, function(numz){
        if(numz==0)
            alert('Bạn chưa đăng nhập!');
        else
            alert('Chương này đã được bookmark!');
    });
}
function submit_er(cid,eid) {
    var er_post = {
        postname:'error',
        cid:cid,
        eid:eid
    }
    $.post(ajaxurl, er_post, function(numz){
        if(numz==0)
            alert('Bạn chưa đăng nhập!');
        else
            alert('Cảm ơn bạn đã thông báo! BQT sẽ khắc phục sớm nhất có thể!');
    });
}

function list_comment(mid,type,page) {
    var data_post 	= {
        postname: 	'listcomment',
        mid:mid,
        type:type,
        page:page
    }
    $.post(ajaxurl, data_post, function(data){
        $('div.comment-list').hide().html(data).fadeIn(300);
    })
}

function submit_comment(mid,type) {
    var camnhantxt 	= $("textarea[name='message']").val();
    var data_post 	= {
        postname: 	'comment',
        mid:mid,
        type:type,
        camnhantxt:camnhantxt
    }
    if(!camnhantxt) {
        alert("Vui lòng nhập nội dung bình luận!");
    }else {
        if(camnhantxt.length<10 || camnhantxt.length>250) {
            alert("Nội dung bình luận phải chứa từ 10 đến 250 ký tự!");
        }else {
            $.post(ajaxurl, data_post, function(data){
                if(data == 1) {
                    alert("Bạn chưa đăng nhập!");
                }else {
                    if(data == 2) {
                        alert("Bình luận của bạn đã được gửi đi!");
                        $("textarea[name='message']").val('');
                        list_comment(mid,type,1)
                    }else {
                        alert(data);
                    }
                }
            })
        }
    }
}


function deletez(num,cid) {
    var del_post = {
        postname:'deletez',
        num:num,
        cid:cid
    }
    $.post(ajaxurl, del_post, function(html){
        $('#like-'+cid).remove();
    });
}

function showImages(maxnum) {
    var pagetype	=	$.inArray($.cookie("pagetype"),new Array(null,"1"))!=-1;
    if(pagetype) {
        $("select[name='pagetype'] option[value='1']").attr('selected', 'selected');
        $('div.chapter_slider p.show').removeClass('show');
        var initialPage = 	window.location.hash;
        var	numshow		=	0;
        var	num			=	initialPage.split('#');
        if(num[1]>0) {
            numshow		= 	parseInt(num[1])-1;
        }
        $("select[name='page'] option[value='"+(numshow+1)+"']").attr('selected', 'selected');
        $('p#img-'+numshow).addClass('show');
        $('div.chapter_slider img').click(function(){
            var	numimg	=	parseInt($(this).attr('rel'));
            changeimages(numimg,mouse_direction);
        });
        $(document).keyup(function(e) {
            var	numimg	=	parseInt($('div.chapter_slider p.show img').attr('rel'));
            if (e.which == 37) {
                changeimages(numimg,'prev');
            } else if (e.which == 39) {
                changeimages(numimg,'next');
            }
        });
        function changeimages(numimg,type) {
            if(type == 'prev') {
                var	showimg	=	numimg-1;
                if(showimg > -1) {
                    $('div.chapter_slider p.show').removeClass('show');
                    $('p#img-'+showimg).addClass('show');
                    $("select[name='page'] option[value='"+(showimg+1)+"']").attr('selected', 'selected');
                    location.hash = "#"+(showimg+1)
                }
            }else if(type == 'next') {
                var	showimg	=	numimg+1;
                if(showimg < maxnum) {
                    $('div.chapter_slider p.show').removeClass('show');
                    $('p#img-'+showimg).addClass('show');
                    $("select[name='page'] option[value='"+(showimg+1)+"']").attr('selected', 'selected');
                    location.hash = "#"+(showimg+1)
                }
            }
        }

        $("div.chapter_slider img").mousemove(function(e) {
            var obj = this;
            if ($(this).css('display') != 'none') {
                var mousepos = e.pageX;
                var width = $(this).width();
                if (mousepos < (width/2 + $(this).offset().left)) {
                    mouse_direction = 'prev';
                    $(this).css('cursor', 'url(\'temp.tt/img/arrow_left.png\'), auto');
                } else {
                    mouse_direction = 'next';
                    $(this).css('cursor', 'url(\'temp.tt/img/arrow_right.png\'), auto');
                }
            }
        });
        $('select[name="page"]').change(function(){
            var	showimg	=	parseInt($(this).attr('value'));
            $('div.chapter_slider p.show').removeClass('show');
            $('p#img-'+(showimg-1)).addClass('show');
            location.hash = "#"+showimg
        });
    }else {
        $("span#page").hide();
        $('div.chapter_slider p').removeClass('hide');
        $("div.chapter_slider img").mousemove(function() {
            $(this).css('cursor', 'url(\'temp.tt/img/arrow_down.png\'), auto');
        });
        $('div.chapter_slider img').click(function(){
            $("html, body").animate({ scrollTop: ($("html, body").scrollTop() + $(window).height()) }, 1000);
        });
    }
    $('select[name="pagetype"]').change(function(){
        $.cookie("pagetype",$(this).attr('value'));
        window.location.reload();
    });
    $('select[name="chapterz"]').change(function(){
        var urlchange	=	$(this).attr('value');
        window.location	=	urlchange;

    });
}

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
/**
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * (C) 2008 Syronex / J.M. Rosengard
 * http://www.syronex.com/software/jquery-color-picker
 *
 * - Check mark is either black or white, depending on the darkness
 *   of the color selected.
 * - Fixed a bug in the original plugin that led to problems when there is
 *   more than one colorPicker in a document.
 *
 * This is based on:
 *
 * jQuery colorSelect plugin 0.9
 * http://plugins.jquery.com/project/colorPickerAgain
 * Copyright (c) 2008 Otaku RzO (Renzo Galo Castro Jurado).
 * (Original author URL & domain name no longer available.)
 *
 */

(function($) {
    $.fn.colorPicker = function($$options) {
        // Defaults
        var $defaults = {
            color:new Array(
                "#FFFFFF", "#EEEEEE", "#FFFF88", "#FF7400", "#CDEB8B", "#6BBA70",
                "#006E2E", "#C3D9FF", "#4096EE", "#356AA0", "#FF0096", "#B02B2C",
                "#000000"
            ),
            defaultColor: 0,
            columns: 0,
            click: function($color){}
        };

        var $settings = $.extend({}, $defaults, $$options);

        // Iterate and reformat each matched element
        return this.each(function() {
            var $this = $(this);
            // build element specific options
            var o = $.meta ? $.extend({}, $settings, $this.data()) : $settings;
            var $$oldIndex = typeof(o.defaultColor)=='number' ? o.defaultColor : -1;

            var _html = "";
            for(i=0;i<o.color.length;i++){
                _html += '<div style="background-color:'+o.color[i]+';"></div>';
                if($$oldIndex==-1 && o.defaultColor==o.color[i]) $$oldIndex = i;
            }

            $this.html('<div class="jColorSelect">'+_html+'</div>');
            var $color = $this.children('.jColorSelect').children('div');
            // Set container width
            var w = ($color.width()+2+2) * (o.columns>0 ? o.columns : o.color.length );
            $this.children('.jColorSelect').css('width',w);

            // Subscribe to click event of each color box
            $color.each(function(i){
                $(this).click(function(){
                    if( $$oldIndex == i ) return;
                    if( $$oldIndex > -1 ){
                        cell = $color.eq($$oldIndex);
                        if(cell.hasClass('check')) cell.removeClass(
                            'check').removeClass('checkwht').removeClass('checkblk');
                    }
                    // Keep index
                    $$oldIndex = i;
                    $(this).addClass('check').addClass(isdark(o.color[i]) ? 'checkwht' : 'checkblk');
                    // Trigger user event
                    o.click(o.color[i]);
                });
            });

            // Simulate click for defaultColor
            _tmp = $$oldIndex;
            $$oldIndex = -1;
            $color.eq(_tmp).trigger('click');
        });
        return this;
    };


})(jQuery);

/**
 * Return true if color is dark, false otherwise.
 * (C) 2008 Syronex / J.M. Rosengard
 **/
function isdark(color){
    var colr = parseInt(color.substr(1), 16);
    return (colr >>> 16) // R
        + ((colr >>> 8) & 0x00ff) // G
        + (colr & 0x0000ff) // B
        < 500;
}

function checkcolor(color){
    var c=0;
    if(color=='#FFFFFF') c=0;
    else if(color=='#EEEEEE') c=1;
    else if(color=='#FFFF88') c=2;
    else if(color=='#FF7400') c=3;
    else if(color=='#CDEB8B') c=4;
    else if(color=='#6BBA70') c=5;
    else if(color=='#006E2E') c=6;
    else if(color=='#C3D9FF') c=7;
    else if(color=='#4096EE') c=8;
    else if(color=='#356AA0') c=9;
    else if(color=='#FF0096') c=10;
    else if(color=='#B02B2C') c=11;
    else if(color=='#000000') c=12;
    return c;
}
/*
 *┌─┐┬ ┬┌─┐┬ ┬┌┐┌┌─┐┌─┐┌┐┌┌─┐ ┌─┐┌─┐┌┬┐
 *└─┐├─┤├─┤││││││┌─┘├┤ ││││ ┬ │  │ ││││
 *└─┘┴ ┴┴ ┴└┴┘┘└┘└─┘└─┘┘└┘└─┘o└─┘└─┘┴ ┴
 * Author: Shawn
 * Author URI: https://shawnzeng.com
 */

$(document).ready(function($) {
    //------------------是否手机------------------
    const isMobile = /mobile/i.test(window.navigator.userAgent);

    //------------------手机端菜单点击显示与隐藏------------------
    var mobile_menu_click = 0;
    //菜单展开
    function menu_expand() {
        if (mobile_menu_click == 0) {
            $("#mobile-menu").animate({
                left: "0"
            }, 500);
            $("#header,#main,#foot").animate({
                left: "60%"
            }, 500);
          	$(".mobile-shade").css("display","block");
            mobile_menu_click++;
        }
    }
    //菜单收起
    function menu_close() {
        if (mobile_menu_click == 1) {
            $("#mobile-menu").animate({
                left: "-60%"
            }, 500);
            $("#header,#main,#foot").animate({
                left: "0"
            }, 500);
          	$(".mobile-shade").css("display","none");
            mobile_menu_click--;
        }
    }
    //监听事件
    $("#menu-bar").click(
        function() {
            if (mobile_menu_click == 0) {
                menu_expand()
            } else {
                menu_close()
            }
        }
    )
    $(".mobile-shade").click(menu_close)
  
    //------------------搜索按钮点击显示与隐藏------------------
    $("#menu-search").click(function() {
    	$("#searchform").fadeToggle(250);
    });
    $("body").click(function(){
      	$("#searchform").hide(250);
    });
    $("#menu-search, #searchform").click(function(){
      	event.stopPropagation();
    });
  
  	// ------------------模式切换------------------
  	var toolbar_click = 0;
    //菜单展开
    function toolbar_expand() {
        if (toolbar_click == 0) {
            $(".toolbar").animate({
                right: "0"
            }, 500);
            toolbar_click++;
        }
    }
    //菜单收起
    function toolbar_close() {
        if (toolbar_click == 1) {
            $(".toolbar").animate({
                right: "-100px"
            }, 500);
            toolbar_click--;
        }
    }
    //监听事件
    $(".toolbarl-expand").click(
        function() {
            if (toolbar_click == 0) {
                toolbar_expand()
            } else {
                toolbar_close()
            }
        }
    )

    // ------------------变幻背景------------------
    // if (!isMobile) {
    /*var c = document.getElementById('evanyou'),
        x = c.getContext('2d'),
        pr = window.devicePixelRatio || 1,
        w = window.innerWidth,
        h = window.innerHeight,
        f = 90,
        q,
        m = Math,
        r = 0,
        u = m.PI * 2,
        v = m.cos,
        z = m.random
    c.width = w * pr
    c.height = h * pr
    x.scale(pr, pr)
    x.globalAlpha = 0.6

    function evanyou() {
        x.clearRect(0, 0, w, h)
        q = [{
            x: 0,
            y: h * .7 + f
        }, {
            x: 0,
            y: h * .7 - f
        }]
        while (q[1].x < w + f) d(q[0], q[1])
    }

    function d(i, j) {
        x.beginPath()
        x.moveTo(i.x, i.y)
        x.lineTo(j.x, j.y)
        var k = j.x + (z() * 2 - 0.25) * f,
            n = y(j.y)
        x.lineTo(k, n)
        x.closePath()
        r -= u / -50
        x.fillStyle = '#' + (v(r) * 127 + 128 << 16 | v(r + u / 3) * 127 + 128 << 8 | v(r + u / 3 * 2) * 127 + 128).toString(16)
        x.fill()
        q[0] = q[1]
        q[1] = {
            x: k,
            y: n
        }
    }

    function y(p) {
        var t = p + (z() * 2 - 1.1) * f
        return (t > h || t < 0) ? y(p) : t
    }
    document.onclick = evanyou
    document.ontouchstart = evanyou
    evanyou()
    // }*/

    //------------------回到顶部------------------
    $(".go-top").hide()
    $(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 1) { //当window的scrolltop距离大于1时，go-top按钮淡出，反之淡入
                $(".go-top").fadeIn();
            } else {
                $(".go-top").fadeOut();
            }
        });
    });
    // 给go-top按钮一个点击事件
    $(".go-top").click(function() {
        $("html,body").animate({
            scrollTop: 0
        }, 300); //点击go to top按钮时，以800的速度回到顶部，这里的800可以根据你的需求修改
        return false;
    });

    //------------------夜间模式------------------
    btn_nightmode = $('.set-view-mode');
    if (sessionStorage.nightmode == "night") {
        $('body').addClass('night-mode');
        btn_nightmode.find('i').attr('class', 'fa fa-sun-o fa-2x fa-fw');
    }
    btn_nightmode.click(function() {
        var next_mode = $('body').hasClass('night-mode') ? 'day' : 'night';
        if (next_mode != 'day') {
            $('body').addClass('night-mode');
            btn_nightmode.find('i').attr('class', 'fa fa-sun-o fa-2x fa-fw');
            sessionStorage.nightmode = "night";
        } else {
            $('body').removeClass('night-mode');
            btn_nightmode.find('i').attr('class', 'fa fa-moon-o fa-2x fa-fw');
            sessionStorage.nightmode = "day";
        }
    });

    //------------------字体切换------------------
    btn_font = $('.set-font-mode');
    if (sessionStorage.font == "other-font") {
        $('body').addClass('other-font');
    }
    btn_font.click(function() {
        var next_mode_font = $('body').hasClass('other-font') ? 'normal-font' : 'other-font';
        if (next_mode_font != 'normal-font') {
            $('body').addClass('other-font');
            sessionStorage.font = "other-font";
        } else {
            $('body').removeClass('other-font');
            sessionStorage.font = "normal-font";
        }
    });
  
  	//------------------存档页面jQuery伸缩------------------
    (function(){
        $('#al_expand_collapse,#archives span.al_mon').css({cursor:"s-resize"});
        $('#archives span.al_mon').each(function(){
            var num=$(this).next().children('li').size();
            var text=$(this).text();
            $(this).html(text+' ('+num+'篇文章)');
        });
        var $al_post_list=$('#archives ul.al_post_list'),
            $al_post_list_f=$('#archives ul.al_post_list:first');
        $al_post_list.hide(1,function(){
            $al_post_list_f.show();
        });
        $('#archives span.al_mon').click(function(){
            $(this).next().slideToggle(400);
            return false;
        });
      	var al_expand_collapse_click=0;
        $('#al_expand_collapse').click(function(){
          	if (al_expand_collapse_click == 0){
              	$al_post_list.show();
              	al_expand_collapse_click++;
            }else if (al_expand_collapse_click == 1){
              	$al_post_list.hide();
              	al_expand_collapse_click--;
            }
        });
    })(); 
  
	// do you like me?
  	$.getJSON("/wp-content/themes/Memory/like.php?action=get", function (data) {
    	$('.like-vote span').html(data.like);
	});
    $('.like-vote').click(function () {
        if ($('.like-title').html() === 'Do you like me?') {
            $.getJSON("/wp-content/themes/Memory/like.php?action=add", function (data) {
                if (data.success) {
                    $('.like-vote span').html(data.like);
                    $('.like-title').html('我也喜欢你 (*≧▽≦)');
                }
                else {
                    $('.like-title').html('你的爱我已经感受到了~');
                }
            });
        }
    });
});

// title切换
var OriginTitile = document.title;
var titleTime;
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        document.title = '(つェ⊂)我藏好了哦~ ' + OriginTitile;
        clearTimeout(titleTime);
    }
    else {
        document.title = '(*´∇｀*) 被你发现啦~ ' + OriginTitile;
        titleTime = setTimeout(function() {
            document.title = OriginTitile;
        }, 2000);
    }
});

// 点赞
$.fn.postLike = function() {
    if ($(this).hasClass('done')) {
        return false;
    } else {
        $(this).addClass('done');
        var id = $(this).data("id"),
        action = $(this).data('action'),
        rateHolder = $(this).children('.count');
        var ajax_data = {
            action: "memory_like",
            um_id: id,
            um_action: action
        };
        $.post("/wp-admin/admin-ajax.php", ajax_data,
        function(data) {
            $(rateHolder).html(data);
        });
        return false;
    }
};
$(document).on("click", ".favorite",
function() {
    $(this).postLike();
});


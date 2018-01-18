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
  	//监听事件
    $("#menu-bar").click(
        function() {
            $("#header,#main,#foot,.header-picture-box").toggleClass("mobile-left");
          	$("#mobile-menu").toggleClass("mobile-normal");
          	$(".mobile-shade").toggleClass("mobile-show");
        }
    );
  	$(".mobile-shade").click(
        function() {
            $("#header,#main,#foot").removeClass("mobile-left");
          	$("#mobile-menu").removeClass("mobile-normal");
          	$(".mobile-shade").removeClass("mobile-show");
        }
    );
  
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
  	$(".share").click(function() {
    	$(".social-share").stop().slideToggle();
    });
  	$(".dashang").click(function() {
    	$(".erweima").stop().slideToggle();
    });

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
        btn_nightmode.find('i').attr('class', 'fa fa-sun-o fa-fw');
    }
    btn_nightmode.click(function() {
        var next_mode = $('body').hasClass('night-mode') ? 'day' : 'night';
        if (next_mode != 'day') {
            $('body').addClass('night-mode');
            btn_nightmode.find('i').attr('class', 'fa fa-sun-o fa-fw');
            sessionStorage.nightmode = "night";
        } else {
            $('body').removeClass('night-mode');
            btn_nightmode.find('i').attr('class', 'fa fa-moon-o fa-fw');
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


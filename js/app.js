var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');      
App = {
    mouseEvent: function() {
        $('#menu-login.have-login').click(function(e) {
            var theEvent = window.event || e;
            theEvent.stopPropagation();
            $('#personal-menu').fadeToggle(250);
        });
        $('body').on('click', function() {
            $('#personal-menu').fadeOut(250);
        });
        $(document).on('click', '#menu-bar.memory-menu', function() {
            $('#menu-bar').animate({ left: -42 }, 200, function() {
                $(this).removeClass('memory-menu').addClass('memory-close');
                $(this).animate({ left: 0 }, 200);
            });
            $('#menu-login').animate({ right: -42 }, 200);
            $('#mobile-menu').fadeIn(200).css('top', 0);
        });
        $(document).on('click', '#menu-bar.memory-close', function() {
            $('#menu-bar').animate({ left: -42 }, 200, function() {
                $(this).removeClass('memory-close').addClass('memory-menu');
                $(this).animate({ left: 0 }, 200);
                $('#menu-login').animate({ right: 0 }, 200);
            });
            $('#mobile-menu').fadeOut(200).css('top', -400);
        });
		
		// 点赞
        $(document).on('click','.post-like a',function() {
          if ($(this).hasClass('have-like')) {
            createMessage('你已经点过赞啦(･∀･)！');
            return false;
          } else {
            $(this).addClass('have-like');
            var id = $(this).data("id"),
                action = $(this).data('action'),
                rateHolder = $(this).children('.like-count');
            var ajax_data = {
                action: action,
                memory_id: id,
                memory_action: action
            };
            $.post(memoryConfig.ajaxUrl, ajax_data, function(data) {
				$(rateHolder).html(data);
				createMessage('୧(๑•̀⌄•́๑)૭感谢你的小心心！');
            });
            return false;
          }
        });	
		
      	// 复制
      	$(document).on('click', 'a.social-share-icon.memory-copy', function() {
      		var clipBoardContent="";
			clipBoardContent+=document.title;
			clipBoardContent+=" - ";
			clipBoardContent+=document.location.href;
			CopyToClipboard(clipBoardContent);
		});
		
      	// 打赏
      	$(document).on('click', '.post-pay', function() {
      		$('.pay-box, #layout-shadow').show();
		});
      	$(document).delegate('.pay-header .memory-close', 'click', function(e) {
          	e.stopPropagation();
          	$('#layout-shadow').hide();
      		$(this).parents('.pay-box').hide();
		});
      	$(document).on('click', '.pay-chose a:not(.chosen)', function() {
          	$('.pay-chose a, .pay-body>img').toggleClass('chosen');
        });
    },
	
  	// 点击加载更多（文章）
  	postsPaging:function() {
		$('#index-pagination a').on('click',function(e) {
			e.preventDefault();
			$(this).hide();
			var href = $(this).attr("href");
			loading_start($('#index-pagination'));
			if (href != undefined) {
				$.ajax({ 
					url: href,
					type: "get",
					error: function(request) {
						alert('加载错误!请联系网站管理员！');
					},
					success: function(data) {
						loading_finish($('#index-pagination'));
						var $result = $(data).find(".posts-list .memory-item");
						$('.posts-list').append($result.fadeIn(1000));
						var nexthref = $(data).find("#index-pagination a").attr("href");
						if (nexthref != undefined) {
						  $("#index-pagination a").show();
						  $("#index-pagination a").html('<div class="page-more">(｡・`ω´･)点我加载更多</div>');
						  $("#index-pagination a").attr("href", nexthref);
						} else {
						  $("#index-pagination a").remove();
						  $("#index-pagination").html('<div class="page-more">你已到达了世界的尽头(｡・`ω´･)！</div>');
						}
					}
				});
			}
	   });
	},

	// 图片懒加载
	imageLazyLoad: function() {
		$("div.lazy").lazyload({
			placeholder : memoryConfig.siteUrl + "/img/loading.gif",
			effect: "fadeIn",
		});
	},
	
  	owoEmoji:function() {
      	$('.OwO').each(function(i, block) {
            var s = new OwO({
                logo: '<i class="memory memory-emoji"></i>',
                container: document.getElementsByClassName('OwO')[0],
                target: document.getElementsByClassName('error')[0],
                position: 'down',
                width: '100%',
                maxHeight: '200px',
                api: memoryConfig.siteUrl + "/emoji/OwO.min.json"
			});
		});
	},
	
	commentsSubmit:function() {
        var edit_again = memoryConfig.commentEditAgain,
        edt1 = '提交成功，在刷新页面之前你可以<a rel="nofollow" class="comment-reply-link" href="#edit" onclick=\'return addComment.moveForm("',
        edt2 = ')\'>重新编辑</a>',
        cancel_edit = '放弃治疗',
        edit, re_edit, num = 1, comm_array=[], $body, wait = 10,
        $comments = $('.post-comment-num a'), // 评论数的 ID
        $cancel = $('#cancel-comment-reply-link'),
        cancel_text = $cancel.text(),
        $submit = $('#comment-form .comment-submit'),
        push_status = $('#comment-form .comment-submit.push-status');      
        $submit.attr('disabled', false);
        comm_array.push(''); //重新编辑不显示内容
        // submit
        $('#comment-form').submit(function() {
          	push_status.html('提交中...');
            $submit.attr('disabled', true).fadeTo('slow', 0.5);
            if ( edit ) $('#comment').after('<input type="text" name="edit_id" id="edit_id" value="' + edit + '" style="display:none;" />');
            // Ajax
            $.ajax({
                url: memoryConfig.ajaxUrl,
                data: $(this).serialize() + "&action=ajax_comment",
                type: $(this).attr('method'),
                error: function(XmlHttpRequest, textStatus, errorThrown) {
                    push_status.html('重新提交');
                  	createMessage(XmlHttpRequest.responseText, 3000);
                    setTimeout(function() {
                        $submit.attr('disabled', false).fadeTo('slow', 1);
                    }, 3000);
                },
                success: function(data) {
                    comm_array.push($('#comment').val());
                  	$('textarea').each(function() {this.value = ''});
                    var t = addComment, cancel = t.I('cancel-comment-reply-link'), temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId), post = t.I('comment_post_ID').value, parent = t.I('comment_parent').value;
                    // comments
                    if ( ! edit && $comments.length ) {
                        n = parseInt($comments.text().match(/\d+/));
                        $comments.text($comments.text().replace( n, n + 1 ));
                    }
                    // show comment
                    new_item = '"id="new-comment-' + num + '"></';
                    new_item = ( parent == '0' ) ? ('\n<div class="new-comment' + new_item + 'div>') : ('\n<ol class="children' + new_item + 'ol>');
                    cue = '\n <div class="ajax-edit"><span class="edit-info" id="success-' + num + '">';
                    if ( edit_again == 1 ) {
                        div_ = (document.body.innerHTML.indexOf('div-comment-') == -1) ? '' : ((document.body.innerHTML.indexOf('li-comment-') == -1) ? 'div-' : '');
                        cue = cue.concat(edt1, div_, 'comment-', parent, '", "', parent, '", "respond", "', post, '", ', num, edt2);
                    }
                    cue += '</span></div>\n';
                    if ( ( parent == '0' ) ) {
                        if ( $( '.no-comment' )[0] ) {
                            $( '.no-comment' )[0].remove();
                        }
                        $( 'ol.memory-comments-area' ).prepend(new_item);
                    } else {
                        $('#respond').before(new_item);
                    }
                    $('#new-comment-' + num).hide().append(data).fadeIn(400); //插入新提交评论
                    $('#new-comment-' + num + ' li .comment-comment').prepend(cue);
                    CountDown(); num++ ; edit = ''; $('*').remove('#edit_id');
                    cancel.style.display = 'none';//“取消回复”消失
                    cancel.onclick = null;
                    t.I('comment_parent').value = '0';
                    if ( temp && respond ) {
                        temp.parentNode.insertBefore(respond, temp);
                        temp.parentNode.removeChild(temp)
                    }
                    $('#comment-validate').each(function() {this.value = ''});
                }
            }); // end Ajax
          return false;
        }); // end submit
      
      
      	if(getVersion(memoryConfig.version=="5.1")>=510) {
		   	console.log("wp5.1+");
			// 修复wp5.1评论回复bug
			$(document).on('click', '.comment-reply-link', function(){
				var postId = document.getElementById('comment_post_ID').value;
				addComment.moveForm( "comment-"+$(this).attr('data-commentid'), $(this).attr('data-commentid'), "respond", postId );
				return false;  // 阻止 a tag 跳转，这句千万别漏了
			});
		}else {
			console.log("wp5.1-");
		}
      
        // comment-reply.dev.js
        addComment = {
            moveForm : function(commId, parentId, respondId, postId, num) {
                var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID');
                if ( edit ) PrevEdit();
                num ? (
                    t.I('comment').value = comm_array[num],
                    edit = t.I('new-comment-' + num).innerHTML.match(/(comment-)(\d+)/)[2],
                    $new_sucs = $('#success-' + num ), $new_sucs.hide(),
                    $new_comm = $('#new-comment-' + num ), $new_comm.hide(),
                    $cancel.text(cancel_edit)
                ) : $cancel.text(cancel_text);
                t.respondId = respondId;
                postId = postId || false;
                if ( !t.I('wp-temp-form-div') ) {
                    div = document.createElement('div');
                    div.id = 'wp-temp-form-div';
                    div.style.display = 'none';
                    respond.parentNode.insertBefore(div, respond);
                }
                !comm ? ( 
                    temp = t.I('wp-temp-form-div'),
                    t.I('comment_parent').value = '0',
                    temp.parentNode.insertBefore(respond, temp),
                    temp.parentNode.removeChild(temp)
                ) : comm.parentNode.insertBefore(respond, comm.nextSibling);
                if ( post && postId ) post.value = postId;
                parent.value = parentId;
                cancel.style.display = '';
                cancel.onclick = function() {
                    if ( edit ) PrevEdit();
                    var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

                    t.I('comment_parent').value = '0';
                    if ( temp && respond ) {
                        temp.parentNode.insertBefore(respond, temp);
                        temp.parentNode.removeChild(temp);
                        $('#comment').val('');
                    }
                    this.style.display = 'none';
                    this.onclick = null;
                    return false;
                };
                try { t.I('comment').focus(); }
                catch(e) {}
                return false;
            },
            I : function(e) {
                return document.getElementById(e);
            }
        }; // end addComment
        function PrevEdit() {
            $new_comm.show(); $new_sucs.show();
            $('textarea').each(function() {this.value = ''});
            edit = '';
            $('#comment-validate').each(function() {this.value = ''});
        } // End PrevEdit
        function CountDown() {
            if ( wait > 0 ) {
                push_status.html(wait+'s'); wait--; setTimeout(CountDown, 1000);
            }
            else {
                push_status.html('发表评论');
                $submit.attr('disabled', false).fadeTo('slow', 1);
                wait = 10;
            }
        } // End CountDown
    },
	
	// ajax头像更新
  	avatarAjax:function() {
        $("input#comment-email").blur(function() {
          var _email = $(this).val();
          if (_email != '') {
            $.ajax({
              type: 'GET',
              data: {
                action: 'ajax_avatar_get',  
                form: memoryConfig.ajaxUrl,
                email: _email
              },
              success: function(data) {
                $('.commentator > img').attr('src', data);
              }
            });
          }
          return false;
        });
  	},
	
    // 点击加载更多（评论）
	commentsPaging:function() {
		$('body').on('click', '.memory-comments-page a.page-numbers', function(e) {
		    e.preventDefault();
		    $.ajax({
		        type: 'GET',
		        url: $(this).attr('href'),
		        beforeSend: function(){
		            $('ol.memory-comments-area').html('');
		            loading_start($('ol.memory-comments-area'));
		        },
		        dataType: 'html',
		        success: function(out){
		            result = $(out).find('ol.memory-comments-area');
                  	nextlink = $(out).find('.memory-comments-page');
		            $('#comments').html(result);
					$('ol.memory-comments-area').append('<div id="pagination"></div>');
		            $('#pagination').html(nextlink);
                  	$body.animate({scrollTop: $('.comment-part').offset().top - 60}, 300);
		        }
		    }); // end ajax
		    return;
		});
	},
	
	pShare:function() {
      	$('.social-share').each(function() {
          	var image = (document.images[0] || 0).src || '';
            var site = getMetaContentByName('site') || getMetaContentByName('Site') || document.title;
            var title = getMetaContentByName('title') || getMetaContentByName('Title') || document.title;
            var description = getMetaContentByName('description') || getMetaContentByName('Description') || '';
            socialShare('.social-share', {
                url                 : location.href, // 网址，默认使用 window.location.href
                source              : site, // 来源（QQ空间会用到）, 默认读取head标签：<meta name="site" content="http://overtrue" />
                title               : title, // 标题，默认读取 document.title 或者 <meta name="title" content="share.js" />
                description         : description, // 描述, 默认读取head标签：<meta name="description" content="PHP弱类型的实现原理分析" />
                image               : image, // 图片, 默认取网页中第一个img标签
              	weiboKey            : '',
                sites               : ['weibo', 'qq', 'wechat', 'qzone'],
                wechatQrcodeHelper  : '<p>微信扫一扫分享</p>',
            });
		});
	},
	
  	scrollToTop:function() {
		var offset = 400,
		scroll_top_duration = 200,
		$back_to_top = $('#back-to-top');
		$(window).scroll(function() {
			( $(this).scrollTop() > offset ) ? $back_to_top.show() : $back_to_top.hide();
		});
		$back_to_top.on('click', function(e){
			e.preventDefault();
			$('body,html').animate({
			    scrollTop: 0 ,
			    }, scroll_top_duration
			);
		});
	},
	

  	startTime:function() {
		function show_date_time() {
          	window.setTimeout(function() {
        		show_date_time();
        	}, 1000);
	  		var blogStartTime=memoryConfig.siteStartTime;
            var date=new Date(blogStartTime.replace(/-/g, '/'));
            var today = new Date();
            var timeold = (today.getTime() - date.getTime());
            var msPerDay = 24 * 60 * 60 * 1000;
            var e_daysold = timeold / msPerDay;
            var daysold = Math.floor(e_daysold);
            var e_hrsold = (e_daysold - daysold) * 24;
            var hrsold = Math.floor(e_hrsold);
            var e_minsold = (e_hrsold - hrsold) * 60;
            var minsold = Math.floor((e_hrsold - hrsold) * 60);
            var seconds = Math.floor((e_minsold - minsold) * 60);
            $('#span_dt_dt').html(daysold + "天" + hrsold + "小时" + minsold + "分" + seconds + "秒");
        }
		show_date_time();
    },
}

App.mouseEvent();
App.imageLazyLoad();
App.owoEmoji();
App.commentsSubmit();
App.postsPaging();
App.commentsPaging();
App.pShare();
App.scrollToTop();
App.startTime();
App.avatarAjax();
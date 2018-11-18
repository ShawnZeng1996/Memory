<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$settings           = array(
  'menu_title'      => 'Memory',
  'menu_type'       => 'menu', // menu, submenu, options, theme, etc.
  'menu_slug'       => 'cs-framework',
  'ajax_save'       => false,
  'show_reset_all'  => false,
  'framework_title' => 'Wordpress Theme Memory <small>by <a href="https://shawnzeng.com">Shawn</a></small> V'.wp_get_theme()->get('Version'),
);

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options        = array();
// Just get the display name of users (e.g John Smith)

$user_names = array();
$all_users = get_users( array( 'fields' => array( 'user_login' ) ) );
foreach ( $all_users as $user ):
  $user =  esc_html( $user->user_login );
  $user_names[ $user ] = $user; 
endforeach;

$options[] = array(
  'name'   => 'memory_seperator_1',
  'title'  => '主题设置',
  'icon'   => 'fa fa-cog'
);


// ----------------------------------------
// a option section for options overview  -
// ----------------------------------------
$options[]      = array(
  'name'        => 'memory_base_config',
  'title'       => '基础设置',
  'icon'        => 'fa fa-institution',
  'fields'      => array(
    array(
      'type'    => 'notice',
      'class'   => 'info',
      'content' => '这部分内容为博主的相关信息。',
    ),
	array(
      'id'      => 'memory_bloger_user',
      'type'    => 'select',
      'title'   => '博主',
      'options' => $user_names,
	 ),
    array(
      'type'    => 'notice',
      'class'   => 'info',
      'content' => '这部分内容为博客的相关信息。',
    ),
    array( 
      'id'      => 'memory_start_time', 
      'type'    => 'text', 
      'title'   => '博客建立日期', 
      'attributes' => array( 'type' => 'date', ), 
      'default' =>  date('Y-m-d',time()),
    ),
    array(
      'id'      => 'memory_description',
      'type'    => 'text',
      'title'   => '博客的描述',
    ),
    array(
      'id'      => 'memory_keywords',
      'type'    => 'text',
      'title'   => '关键词',
      'desc'    => '博客的关键词，用英文逗号分割。',
    ),
    array(
      'id'      => 'memory_record',
      'type'    => 'text',
      'title'   => '备案号',
      'desc'    => '展现在主题页脚的备案号。',
      'default' => '未备案',
    ),
    array(
      'id'      => 'memory_copyright',
      'type'    => 'text',
      'title'   => 'Copyright',
      'desc'    => '展现在主题页脚的©符号后面的内容。',
      'default' =>  date('Y',time()),
    ),
    array(
      'type'    => 'notice',
      'class'   => 'info',
      'content' => '这部分内容为博客部分功能的设置。',
    ),    
    array(
      'id'        => 'memory_post_image',
      'type'      => 'image',
      'title'     => '文章默认配图',
      'default'   => get_template_directory_uri() .'/img/default_bg.jpg',
      'validate'  => 'required',
      'desc'      => '首页的文章配图，必填项。',
    ),
    array(
      'id'        => 'memory_alipay_image',
      'type'      => 'image',
      'title'     => '支付宝打赏二维码',
    ),
    array(
      'id'        => 'memory_wechat_image',
      'type'      => 'image',
      'title'     => '微信打赏二维码',
    ),

  ), // end: fields
);

$options[]   = array(
  'name'     => 'memory-social-info',
  'title'    => '社交信息',
  'icon'     => 'fa fa-address-book',
  'fields'   => array(
    array(
      'id'      => 'memory_qq',
      'type'    => 'text',
      'title'   => 'QQ号',
      'desc'    => '您的QQ号，默认会自动为您生成添加链接，如不填写则不显示该项。',
    ),
    array(
      'id'      => 'memory_email',
      'type'    => 'text',
      'title'   => '邮箱地址',
      'desc'    => '您的邮箱地址，如不填写则不显示该项。',
    ),
    array(
      'id'      => 'memory_qqqun',
      'type'    => 'text',
      'title'   => 'QQ群加群链接',
      'desc'    => 'QQ群加群链接，如不填写则不显示该项。',
    ),
    array(
      'id'      => 'memory_weibo',
      'type'    => 'text',
      'title'   => '微博链接',
      'desc'    => '微博链接，如不填写则不显示该项。',
    ),
    array(
      'id'      => 'memory_github',
      'type'    => 'text',
      'title'   => 'Github地址',
      'desc'    => 'Github地址，如不填写则不显示该项。',
    ),
    array(
      'id'      => 'memory_zhihu',
      'type'    => 'text',
      'title'   => '知乎地址，',
      'desc'    => '知乎地址，如不填写则不显示该项。',
    ),
    array(
      'id'      => 'memory_bilibili',
      'type'    => 'text',
      'title'   => '哔哩哔哩个人空间链接地址',
      'desc'    => '哔哩哔哩个人空间链接地址，如不填写则不显示该项。',
    ),
  )
);

$options[]      = array(
  'name'        => 'memory_style_config',
  'title'       => '布局样式',
  'icon'        => 'fa fa-dashboard',
  'fields'      => array(
    array(
      'id'      => 'memory_breadcrumbs',
      'type'    => 'switcher',
      'title'   => '面包屑导航',
      'desc'    => '开启该功能后会在页面（除首页外）头部出现一个模块用于显示你当前所在位置。有利于归档，请尽量开启。',
    ),
    array(
      'id'      => 'memory_opacity',
      'type'    => 'text',
      'title'   => '模块透明度',
      'default' => '1',
      'desc'    => '模块透明度，取值0~1，默认不透明，值为1，取0则完全透明。',
    ),
    array(
      'id'    => 'memory_background',
      'type'  => 'background',
      'title' => '背景图片',
      'desc'  => '可自行调整样式，如不选择图片则使用颜色填充，您可以在颜色选择器内选择背景色，默认#f5f5f5。',
      'help'  => '存在疑问？请查询css背景样式相关知识。',
      'default' => array(
          'image'      => '',
          'repeat'     => 'no-repeat',
          'position'   => 'center center',
          'attachment' => 'scroll',
    	  'size'       => 'cover',
          'color'      => '#f5f5f5',
      ),
    ),
    array(
      'id'      => 'memory_certify_color',
      'type'    => 'color_picker',
      'title'   => '认证图标颜色',
      'default' => '#ffba50',
      'desc'    => '认证图标颜色，推荐#49c7ff和#ffba50。',
    ),
    array(
      'id'      => 'memory_footer_color',
      'type'    => 'color_picker',
      'title'   => '页脚字体颜色',
      'default' => '#000',
      'desc'    => '页脚字体颜色，默认黑色。',
    ),
    array(
      'id'    => 'memory_card_background',
      'type'  => 'background',
      'title' => 'PC端名片背景图',
      'desc'  => '可自行调整样式，如不选择图片则默认用颜色填充。',
      'help'  => '存在疑问？请查询css背景样式相关知识。',
      'default' => array(
          'image'      => get_template_directory_uri() .'/img/default_bg.jpg',
          'repeat'     => 'no-repeat',
          'position'   => 'center center',
          'attachment' => 'scroll',
    	  'size'       => 'cover',
          'color'      => '#f5f5f5',
      ),
    ),
    array(
      'id'    => 'memory_user_css',
      'type'  => 'textarea',
      'title' => '自定义css',
    ),    
    array(
      'id'    => 'memory_user_js',
      'type'  => 'textarea',
      'title' => '自定义js',
      'desc'  => '注：本主题使用jQuery版本为3.2.1。',
    ),
  ), // end: fields
);

$options[]      = array(
  'name'        => 'memory_comment_config',
  'title'       => '评论设置',
  'icon'        => 'fa fa-comments',
  'fields'      => array(
    array(
      'id'      => 'memory_comment_edit',
      'type'    => 'switcher',
      'default' =>  true,
      'title'   => '评论再编辑功能',
      'label'   => '开启该功能后评论提交10秒内可以再次进行编辑',
    ),
    array(
      'id'        => 'memory_comment_avatar',
      'type'      => 'image',
      'title'     => '评论默认头像',
      'default'   => get_template_directory_uri() .'/img/comment-avatar.png',
      'desc'      => '评论默认头像，设置完后还需到仪表盘设置->讨论中选择默认头像方可生效。',
    ),
  )
);

$options[]   = array(
  'name'     => 'iconchosen',
  'title'    => '自选图标',
  'icon'     => 'fa fa-info-circle',
  'fields'   => array(

    array(
      'type'    => 'heading',
      'content' => '自选图标'
    ),
    array(
      'type'    => 'content',
      'content' => '<ul class="icon_lists clear"><li><i class="memory memory-baidu"></i> .memory-baidu</li><li><i class="memory memory-coffee"></i> .memory-coffee</li><li><i class="memory memory-Facebook"></i> .memory-Facebook</li><li><i class="memory memory-404"></i> .memory-404</li><li><i class="memory memory-ipad"></i> .memory-ipad</li><li><i class="memory memory-sun"></i> .memory-sun</li><li><i class="memory memory-android"></i> .memory-android</li><li><i class="memory memory-fire"></i> .memory-fire</li><li><i class="memory memory-download"></i> .memory-download</li><li><i class="memory memory-search"></i> .memory-search</li><li><i class="memory memory-man"></i> .memory-man</li><li><i class="memory memory-woman"></i> .memory-woman</li><li><i class="memory memory-linkedin"></i> .memory-linkedin</li><li><i class="memory memory-rss"></i> .memory-rss</li><li><i class="memory memory-tencentweibo"></i> .memory-tencentweibo</li><li><i class="memory memory-google"></i> .memory-google</li><li><i class="memory memory-touxian"></i> .memory-touxian</li><li><i class="memory memory-qqzone"></i> .memory-qqzone</li><li><i class="memory memory-novel"></i> .memory-novel</li><li><i class="memory memory-bck"></i> .memory-bck</li><li><i class="memory memory-douban"></i> .memory-douban</li><li><i class="memory memory-iphone"></i> .memory-iphone</li><li><i class="memory memory-throwout"></i> .memory-throwout</li><li><i class="memory memory-tag"></i> .memory-tag</li><li><i class="memory memory-twitter"></i> .memory-twitter</li><li><i class="memory memory-github"></i> .memory-github</li><li><i class="memory memory-ie"></i> .memory-ie</li><li><i class="memory memory-expand"></i> .memory-expand</li><li><i class="memory memory-link"></i> .memory-link</li><li><i class="memory memory-more-o"></i> .memory-more-o</li><li><i class="memory memory-location"></i> .memory-location</li><li><i class="memory memory-tags"></i> .memory-tags</li><li><i class="memory memory-heart"></i> .memory-heart</li><li><i class="memory memory-math"></i> .memory-math</li><li><i class="memory memory-rocket"></i> .memory-rocket</li><li><i class="memory memory-nickname"></i> .memory-nickname</li><li><i class="memory memory-about"></i> .memory-about</li><li><i class="memory memory-comment"></i> .memory-comment</li><li><i class="memory memory-edit"></i> .memory-edit</li><li><i class="memory memory-menu"></i> .memory-menu</li><li><i class="memory memory-logout"></i> .memory-logout</li><li><i class="memory memory-trash"></i> .memory-trash</li><li><i class="memory memory-settings"></i> .memory-settings</li><li><i class="memory memory-diandian"></i> .memory-diandian</li><li><i class="memory memory-view"></i> .memory-view</li><li><i class="memory memory-fish"></i> .memory-fish</li><li><i class="memory memory-reply"></i> .memory-reply</li><li><i class="memory memory-mac"></i> .memory-mac</li><li><i class="memory memory-weihu"></i> .memory-weihu</li><li><i class="memory memory-zhifubao"></i> .memory-zhifubao</li><li><i class="memory memory-firefox"></i> .memory-firefox</li><li><i class="memory memory-blacklist"></i> .memory-blacklist</li><li><i class="memory memory-keyword"></i> .memory-keyword</li><li><i class="memory memory-wechat"></i> .memory-wechat</li><li><i class="memory memory-weibo"></i> .memory-weibo</li><li><i class="memory memory-steam"></i> .memory-steam</li><li><i class="memory memory-qq"></i> .memory-qq</li><li><i class="memory memory-kindle"></i> .memory-kindle</li><li><i class="memory memory-heart-o"></i> .memory-heart-o</li><li><i class="memory memory-time"></i> .memory-time</li><li><i class="memory memory-comments"></i> .memory-comments</li><li><i class="memory memory-dot"></i> .memory-dot</li><li><i class="memory memory-maxthon"></i> .memory-maxthon</li><li><i class="memory memory-copyright"></i> .memory-copyright</li><li><i class="memory memory-calendar"></i> .memory-calendar</li><li><i class="memory memory-uc"></i> .memory-uc</li><li><i class="memory memory-hitokoto"></i> .memory-hitokoto</li><li><i class="memory memory-moon"></i> .memory-moon</li><li><i class="memory memory-chrome"></i> .memory-chrome</li><li><i class="memory memory-login"></i> .memory-login</li><li><i class="memory memory-email"></i> .memory-email</li><li><i class="memory memory-dashboard"></i> .memory-dashboard</li><li><i class="memory memory-email-o"></i> .memory-email-o</li><li><i class="memory memory-at"></i> .memory-at</li><li><i class="memory memory-birthday"></i> .memory-birthday</li><li><i class="memory memory-safari"></i> .memory-safari</li><li><i class="memory memory-wordpress"></i> .memory-wordpress</li><li><i class="memory memory-360"></i> .memory-360</li><li><i class="memory memory-QQbrowser"></i> .memory-QQbrowser</li><li><i class="memory memory-sougou"></i> .memory-sougou</li><li><i class="memory memory-qqqun"></i> .memory-qqqun</li><li><i class="memory memory-copy"></i> .memory-copy</li><li><i class="memory memory-certify"></i> .memory-certify</li><li><i class="memory memory-default"></i> .memory-default</li><li><i class="memory memory-classify_icon"></i> .memory-classify_icon</li><li><i class="memory memory-postlist"></i> .memory-postlist</li><li><i class="memory memory-close"></i> .memory-close</li><li><i class="memory memory-bilibili"></i> .memory-bilibili</li><li><i class="memory memory-windows"></i> .memory-windows</li><li><i class="memory memory-opera"></i> .memory-opera</li><li><i class="memory memory-linux"></i> .memory-linux</li><li><i class="memory memory-code"></i> .memory-code</li><li><i class="memory memory-home"></i> .memory-home</li><li><i class="memory memory-category"></i> .memory-category</li><li><i class="memory memory-timeline"></i> .memory-timeline</li><li><i class="memory memory-article"></i> .memory-article</li><li><i class="memory memory-leaf"></i> .memory-leaf</li><li><i class="memory memory-font"></i> .memory-font</li><li><i class="memory memory-dashang"></i> .memory-dashang</li><li><i class="memory memory-zhihu"></i> .memory-zhihu</li><li><i class="memory memory-photo"></i> .memory-photo</li><li><i class="memory memory-top"></i> .memory-top</li><li><i class="memory memory-statistics"></i> .memory-statistics</li><li><i class="memory memory-browser"></i> .memory-browser</li><li><i class="memory memory-share"></i> .memory-share</li><li><i class="memory memory-shangyinhao"></i> .memory-shangyinhao</li><li><i class="memory memory-xiayinhao"></i> .memory-xiayinhao</li><li><i class="memory memory-visitors-o"></i> .memory-visitors-o</li><li><i class="memory memory-yibiaopan"></i> .memory-yibiaopan</li><li><i class="memory memory-visitors"></i> .memory-visitors</li><li><i class="memory memory-emoji"></i> .memory-emoji</li><li><i class="memory memory-site"></i> .memory-site</li></ul>
',
    ),

  )
);

// ------------------------------
// backup                       -
// ------------------------------
$options[]   = array(
  'name'     => 'backup_section',
  'title'    => '备份还原',
  'icon'     => 'fa fa-shield',
  'fields'   => array(
    array(
      'type'    => 'notice',
      'class'   => 'warning',
      'content' => '您可以在此备份/还原您在本主题的配置信息。',
    ),
    array(
      'type'    => 'backup',
    ),
  )
);

// ------------------------------
// a seperator                  -
// ------------------------------
$options[] = array(
  'name'   => 'memory_seperator_2',
  'title'  => '其他信息',
  'icon'   => 'fa fa-bookmark'
);

// ------------------------------
// license                      -
// ------------------------------
$options[]   = array(
  'name'     => 'memory_author',
  'title'    => '关于作者',
  'icon'     => 'fa fa-info-circle',
  'fields'   => array(

    array(
      'type'    => 'heading',
      'content' => 'Shawn'
    ),
    array(
      'type'    => 'content',
      'content' => '关于作者的介绍，目前还没想好要写些啥……',
    ),

  )
);

$options[]   = array(
  'name'     => 'memory_help',
  'title'    => '使用教程',
  'icon'     => 'fa fa-book',
  'fields'   => array(

    array(
      'type'    => 'heading',
      'content' => 'memory主题使用教程'
    ),
    array(
      'type'    => 'content',
      'content' => '<p>请前往<a href="https://shawnzeng.com">作者博客</a>查看，另外请加入memory主题售后服务群，方便沟通反馈，谢谢！另外，请务必看完教程后再向我咨询，如果教程上已介绍了的问题，我有权拒绝回答。。。</p>',
    ),

  )
);

CSFramework::instance( $settings, $options );

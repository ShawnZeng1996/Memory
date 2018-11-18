<?php
class Memory_widget_recent_visitors extends WP_Widget {

    public $default_instance = array();
    function __construct(){
        parent::__construct(
            'recent_visitors',
            'Memory最近访客',
            array( 'description' => __( '根据设置可以显示不同数量的访客', 'Memory' ) )
        );
        $this->default_instance = array(
            'title' => __('最近访客', 'Memory'),
          	'icon'  => 'memory-visitors',
            'readerNum'=> 5
        );
    }

    function form($instance){
        $instance = wp_parse_args( $instance, $this->default_instance );
        $title = htmlspecialchars($instance['title']);
        $icon = htmlspecialchars($instance['icon']);
        $readerNum = htmlspecialchars($instance['readerNum']);
        echo '<p><label for="'.$this->get_field_name('title').'">标题:<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></label></p>';
        echo '<p><label for="'.esc_attr( $this->get_field_id( 'icon' ) ).'">图标：</label><input type="text" class="widefat" id="'. esc_attr( $this->get_field_id( 'icon' ) ).'" name="'. esc_attr( $this->get_field_name( 'icon' ) ).'" value="'. $instance['icon'].'" /></p>';
      	echo '<p><label for="'.$this->get_field_name('readerNum').'">显示访客数量:<input class="widefat" id="'.$this->get_field_id('readerNum').'" name="'.$this->get_field_name('readerNum').'" type="text" value="'.$readerNum.'" /></label></p>';
    }

    function update($new_instance,$old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
      	$instance['icon'] = strip_tags(stripslashes($new_instance['icon']));
        $instance['readerNum'] = strip_tags(stripslashes($new_instance['readerNum']));
        return $instance;
    }

    function widget($args, $instance){
        extract($args);
      	if( empty( $instance ) ) $instance = $this->default_instance;
        $title = apply_filters('widget_title', empty($instance['title']) ? __('活跃访客','yang') : $instance['title']);//小工具前台标题
		if(!isset($instance['icon']))
          	$icon = 'memory-visitors';
      	else if($instance['icon']=='')
          	$icon = 'memory-visitors';
      	else
          	$icon = $instance['icon'];
        $readerNum = $instance['readerNum'];
        echo $before_widget;  //id开始框
        if( $title ) echo $args['before_title'] . '<i class="memory ' . $icon . '"></i> ' . $title . $args['after_title'];
        if (function_exists('Memory_most_active_friends')) { echo "<ul class='widget-visitors'>" . Memory_most_active_friends($readerNum) . "</ul>";} ;
        echo $after_widget;  //框架结束
    }
}
register_widget('Memory_widget_recent_visitors');
<?php
class Memory_widget_recent_photos extends WP_Widget {

    public $default_instance = array();
    function __construct(){
        parent::__construct(
            'recent_photos',
            'Memory近期图像',
            array( 'description' => __( '根据设置可以显示不同数量的图片', 'Memory' ) )
        );
        $this->default_instance = array(
            'title' => __('近期图像', 'Memory'),
          	'icon'  => 'memory-bck',
            'phoNum'=> 5
        );
    }

    function form($instance){
        $instance = wp_parse_args( $instance, $this->default_instance );
        $title = htmlspecialchars($instance['title']);
        $icon = htmlspecialchars($instance['icon']);
        $phoNum = htmlspecialchars($instance['phoNum']);
        echo '<p><label for="'.$this->get_field_name('title').'">标题:<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></label></p>';
        echo '<p><label for="'.esc_attr( $this->get_field_id( 'icon' ) ).'">图标：</label><input type="text" class="widefat" id="'. esc_attr( $this->get_field_id( 'icon' ) ).'" name="'. esc_attr( $this->get_field_name( 'icon' ) ).'" value="'. $instance['icon'].'" /></p>';
      	echo '<p><label for="'.$this->get_field_name('phoNum').'">显示图片数量:<input class="widefat" id="'.$this->get_field_id('phoNum').'" name="'.$this->get_field_name('phoNum').'" type="text" value="'.$phoNum.'" /></label></p>';
    }

    function update($new_instance,$old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
      	$instance['icon'] = strip_tags(stripslashes($new_instance['icon']));
        $instance['phoNum'] = strip_tags(stripslashes($new_instance['phoNum']));
        return $instance;
    }

    function widget($args, $instance){
        extract($args);
      	if( empty( $instance ) ) $instance = $this->default_instance;
        $title = apply_filters('widget_title', empty($instance['title']) ? __('近期图像','Memory') : $instance['title']);//小工具前台标题
		if(!isset($instance['icon']))
          	$icon = 'memory-photo';
      	else if($instance['icon']=='')
          	$icon = 'memory-photo';
      	else
          	$icon = $instance['icon'];
        $phoNum = $instance['phoNum'];
        echo $before_widget;  //id开始框
        if( $title ) echo $args['before_title'] . '<i class="memory ' . $icon . '"></i> ' . $title . $args['after_title'];
        if (function_exists('Memory_recent_photos')) { echo "<ul class='widget-photos'>" . Memory_recent_photos($phoNum) . "</ul>";} ;
        echo $after_widget;  //框架结束
    }
}
register_widget('Memory_widget_recent_photos');
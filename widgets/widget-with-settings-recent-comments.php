<?php  
/**
 * ┌─┐┬ ┬┌─┐┬ ┬┌┐┌┌─┐┌─┐┌┐┌┌─┐ ┌─┐┌─┐┌┬┐
 * └─┐├─┤├─┤││││││┌─┘├┤ ││││ ┬ │  │ ││││
 * └─┘┴ ┴┴ ┴└┴┘┘└┘└─┘└─┘┘└┘└─┘o└─┘└─┘┴ ┴
 *
 * @package WordPress
 * @Theme Memory
 *
 * @author admin@shawnzeng.com
 * @link https://shawnzeng.com
 * 小工具：评论
 */
class Memory_widget_recent_comments extends WP_Widget{

	//默认设置
	public $default_instance = array();

	//初始化
	function __construct(){
		parent::__construct(
			'recent_comments',
			'Memory最近评论' . __( '', 'Memory' ),
			array( 'description' => __( '根据设置可以显示不同的评论', 'Memory' ) )
		);
		$this->default_instance = array(
			'title'                 => __( '评论', 'Memory' ),
          	'icon'  				=> 'memory-comments',
			'number'                => 5,
			'exclude_users'         => array(),
			'exclude_administrator' => false,
			'descending'            => true,
			'date_limit'            => 'unlimited'
		);
	}

	//小工具内容
	function widget( $args, $instance ){
		if( empty( $instance ) ) $instance = $this->default_instance;
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
      	if(!isset($instance['icon'])) 
          	$icon = 'memory-comments';
      	else if($instance['icon']=='') 
          	$icon = 'memory-comments';
      	else
          	$icon = $instance['icon'];
		echo $args['before_widget'];
			if( !empty( $title ) ) echo $args['before_title'] . '<i class="memory ' . $icon . '"></i> ' . $title . $args['after_title'];
			$exclude_users = $instance['exclude_users'];
			if( $instance['exclude_administrator'] ){
				$administrator_ids = array_map( 'absint', get_users( 'fields=ids&role=administrator' ) );
				$exclude_users = array_unique( array_merge( $instance['exclude_users'], $administrator_ids ) );
			}
			$date_query = $instance['date_limit'] == 'unlimited' ? null : array( 'after' => $instance['date_limit'] );
      		if(isset($instance['number']) && isset($instance['descending']))
			$comments = get_comments( array(
				'number'         => $instance['number'],
				'author__not_in' => $exclude_users,
				'order'          => $instance['descending'] ? 'DESC' : 'ASC',
				'date_query'     => $date_query,
				'status'         => 'approve',
				'type'           => 'comment'
			) );
			if( empty( $comments ) ):
?>
				<div class="empty-recent-comments">
					<p><?php _e( '什么评论都没有，赶紧去发表你的意见吧！' ); ?></p>
				</div>
<?php
			else:
				echo '<ul class="recentcomments">';
					foreach( $comments as $comment ):
						$a_title = sprintf( __( '「%s」', 'Memory' ), get_the_title( $comment->comment_post_ID ) );
?>
						<li>
							<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>" title="<?php echo get_comment_author( $comment ); ?>">
								<?php echo get_avatar( $comment, 36 ); ?>
								<div class="right-box">
									<span class="author">「<?php echo get_comment_author( $comment ); ?>」</span>于<span>「<?php echo human_time_diff(get_comment_date('U',$comment->comment_ID), current_time('timestamp')) . '前'; ?>」</span>在<span class="title"><?php echo esc_attr( $a_title ); ?></span>中说: <br/><div class="comment-content"><span class="recent-comment"><?php echo get_comment_text( $comment ); ?></span></div>
								</div>
							</a>
						</li>
<?php
					endforeach;
				echo '</ul>';
			endif;
		echo $args['after_widget'];
	}

	//保存设置选项
	function update( $new_instance, $old_instance ){
		$instance = array();
		
		//标题
		$instance['title'] = strip_tags( $new_instance['title'] );

		//评论数量
		$instance['number'] = absint( $new_instance['number'] );
		if( $instance['number'] === 0 ) $instance['number'] = 1;

		//排除用户
		$instance['exclude_users'] = array_map( 'absint', (array) $new_instance['exclude_users'] );
		if( !empty( $instance['exclude_users'] ) ){
			$instance['exclude_users'] = get_users( array(
				'include' => $instance['exclude_users'],
				'fields' => 'ids'
			) );
			$instance['exclude_users'] = array_map( 'absint', $instance['exclude_users'] );
		}

		//排除所有管理员
		$instance['exclude_administrator'] = !empty( $new_instance['exclude_administrator'] );

		//倒序排列
		$instance['descending'] = !empty( $new_instance['descending'] );

		//日期限制
		$all_date_limit = array(
			'unlimited',
			'1 day ago',
			'3 day ago',
			'1 week ago',
			'1 month ago',
			'3 month ago',
			'6 month ago',
			'1 year ago',
			'2 year ago',
			'3 year ago'
		);
		$instance['date_limit'] = in_array( $new_instance['date_limit'], $all_date_limit ) ? $new_instance['date_limit'] : 'unlimited';

		return $instance;
	}

	//设置表单
	function form( $instance ){
		$instance = wp_parse_args( $instance, $this->default_instance );

		$users = get_users( array(
			'orderby' => 'registered',
			'fields'  => array( 'ID', 'display_name' )
		) );

		$date_limit = array(
			'unlimited'   => __( '无限制', 'Memory' ),
			'1 day ago'   => __( '一天之内', 'Memory' ),
			'3 day ago'   => __( '三天之内', 'Memory' ),
			'1 week ago'  => __( '一周之内', 'Memory' ),
			'1 month ago' => __( '一个月之内', 'Memory' ),
			'3 month ago' => __( '三个月之内', 'Memory' ),
			'6 month ago' => __( '半年之内', 'Memory' ),
			'1 year ago'  => __( '一年之内', 'Memory' ),
			'2 year ago'  => __( '两年之内', 'Memory' ),
			'3 year ago'  => __( '三年之内', 'Memory' )
		);
?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( '标题：', 'Memory' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php _e( '图标：', 'Memory' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" value="<?php echo $instance['icon']; ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( '评论数量：', 'Memory' ); ?></label>
			<input type="number" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'exclude_users' ) ); ?>"><?php _e( '排除用户：', 'Memory' ); ?></label>
			<select class="widefat" multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'exclude_users' ) ); ?>[]" name="<?php echo esc_attr( $this->get_field_name( 'exclude_users' ) ); ?>[]">
				<?php foreach( $users as $user ): ?>
					<option value="<?php echo esc_attr( $user->ID ); ?>"<?php if( in_array( $user->ID, $instance['exclude_users'] ) ) echo ' selected="selected"'; ?>><?php echo $user->display_name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'exclude_administrator' ) ); ?>"><?php _e( '排除所有管理员：', 'Memory' ); ?></label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'exclude_administrator' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'exclude_administrator' ) ); ?>" value="1"<?php checked( $instance['exclude_administrator'] ); ?> />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'descending' ) ); ?>"><?php _e( '倒序排列：', 'Memory' ); ?></label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'descending' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'descending' ) ); ?>" value="1"<?php checked( $instance['descending'] ); ?> />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'date_limit' ) ); ?>"><?php _e( '日期限制：', 'Memory' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'date_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date_limit' ) ); ?>">
				<?php foreach( $date_limit as $date_code => $date_title ): ?>
					<option value="<?php echo $date_code; ?>"<?php selected( $instance['date_limit'], $date_code ); ?>><?php echo $date_title; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
<?php
	}

}
register_widget( 'Memory_widget_recent_comments' );

//End of page.
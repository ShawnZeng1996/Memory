<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// METABOX OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options      = array();

// -----------------------------------------
// Post Metabox Options                    -
// -----------------------------------------
$options[]    = array(
  'id'        => '_giligili_post_options',
  'title'     => 'Memory设置',
  'post_type' => array('post','page'),
  'context'   => 'normal',
  'priority'  => 'default',
  'sections'  => array(
    array(
      'name'   => 'seo_improve',
      'title'  => 'SEO设置',
      'fields' => array(
        array(
          'type'    => 'notice',
          'class'   => 'info',
          'content' => '此部分为SEO优化功能，如果不填则使用默认值。',
    	),
        array(
          'id'    => 'post_description',
          'type'  => 'textarea',
          'title' => '描述',
        ),
        array(
          'id'    => 'post_keywords',
          'type'  => 'text',
          'title' => '关键词',
        ),
      ),
    ),

  ),
);

CSFramework_Metabox::instance( $options );

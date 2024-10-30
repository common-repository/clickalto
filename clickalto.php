<?php

/*
Plugin Name: Clickalto
Plugin URI: http://www.clickalto.com
Description: Widget description
Version: 1.2
Author: Kernix
Author URI: http://www.kernix.com
License: GPLv2 or later
Text Domain: clickalto
Domain Path: /languages/
*/

function clickalto_log($var) {
  if (is_int($var) && $var === 0) { 
    $var = 'INT(0)';
  }
  elseif (is_null($var)) { 
    $var = 'NULL';
  }
  elseif (is_bool($var)) {
    $var = 'BOOL('.intval($var).')';
  }
  if (is_array($var)) { 
    $var = "\n".var_export($var, true); 
  }
  if ($prefix !== null) {
    $var = $prefix.' '.$var;
  }
//  error_log($var);
}

function clickalto_get_sidebar($sidebars) {
  if (!is_array($sidebars)) return false;
  $sidebars = (array) $sidebars;
  foreach ($sidebars as $name => $sidebar) {
    if (!is_array($sidebar)) continue ;
    $tmp = 'names='.join('', $sidebar);
    if (strpos($tmp, 'clickalto') != false) return $name;
  }
  return null;
}

function clickalto_find_sidebar($sidebars) {
  $skip = array('wp_inactive_widgets', 'undefined', 'array_version', '');
  foreach ($sidebars as $name => $sidebar) {
    $name = (string) $name;
    if (in_array($name, $skip)) continue ;
    return $name;
  }
  return 'sidebar-1';
}

add_action('widgets_init', 'init_clickalto');
add_action('admin_menu', 'register_clickalto_menu');

function init_clickalto() {
  load_plugin_textdomain('clickalto', false, 
                         dirname(plugin_basename( __FILE__ )).'/languages/');
  $sidebars = get_option('sidebars_widgets');
  clickalto_log($sidebars);
  if ($sidebar = clickalto_get_sidebar($sidebars)) {
//    clickalto_log('in sidebar : '.$sidebar);
  }
  else {
    $sidebar = clickalto_find_sidebar($sidebars);
//    clickalto_log('not in sidebar : '.$sidebar.' *** AJOUT ***');
    $sidebars[$sidebar][] = 'clickalto-1';
    update_option('sidebars_widgets', $sidebars);
    update_option('widget_clickalto', array(1 => array(), 2 => array(), 13 => array()));
//    update_option('clickalto', array('_multiwidget' => 0));
  }
  register_widget('clickalto');
}

$opts = get_option('clickalto');
if ($opts['id'] < 1) {
  $opts = get_option('widget_clickalto');
  foreach ($opts as $idx => $h) {
    if ($h['id'] > 0) {
      update_option('clickalto', array('id' => $h['id']));
      break ; 
    }
  }
}

function register_clickalto_menu() {
  add_menu_page('Clickalto', 'Clickalto', 'add_users', 'clickalto/config.php', '',  
                plugins_url('clickalto/icon.png'), 101);
}

class Clickalto extends WP_Widget {
  
  public function __construct() {
    $widget_opts = array('classname' => 'clickalto',
                         'description' => __('Widget description', 'clickalto'));
    parent::__construct('clickalto', 'Clickalto', $widget_opts);
  }

  public function widget($args, $instance) {
    // outputs the content of the widget

    $settings = get_option("clickalto"); 
    $widget_id = (int) $settings['id'];

//    extract($args);
//    $widget_id = (int) $instance['id'];

    clickalto_log('WID='.$widget_id);

    echo $before_widget;
    if ($widget_id < 1) {
      echo __('Required id', 'clickalto');
    }
    else {
      if ($html = @file_get_contents('http://www.clickalto.com/widget.embed?id='.$widget_id)) {
        echo $html;
      }
      else {
        echo __('Error load', 'clickalto');
      }
    }
    echo $after_widget;
  }

  public function update($new_instance, $old_instance) {

    $settings = get_option("clickalto");
    clickalto_log($settings);
    clickalto_log($new_instance);
    clickalto_log($old_instance);
    return ;
    // processes widget options to be saved
    
//    $settings['_multiwidget'] = 0;
//    update_option("clickalto", $settings);

    clickalto_log($new_instance);
    clickalto_log($old_instance);

    $instance = $old_instance;
    $instance['id'] = (int) $new_instance['id'];

    return $instance;
  }

  public function form($instance) {
    // outputs the options form on admin
    $label = __('Widget code', 'clickalto');
 //.__('Only one widget', 'clickalto')
    echo '<p>'
//         .  '<br /><br />'
         .  '<a href="admin.php?page=clickalto/config.php">'.__('Configure it', 'clickalto').'</a>'
         .'</p>';
  }

}

?>
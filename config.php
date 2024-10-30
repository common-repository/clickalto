<?php

if (isset($_POST)) {
  try {
    if (isset($_POST['widget-clickalto'])) {
      $id = $_POST['widget-clickalto'];
      if (is_numeric($id)) {
        update_option('clickalto', array('id' => $id));
      }
    }
  }
  catch (Exception $e) {}
}

global $wpdb, $wp_registered_widgets;

$clickalto_id = 0;

$sidebars_widgets = wp_get_sidebars_widgets();
//$is_present = false;

$sidebar = clickalto_get_sidebar($sidebars_widgets);
//clickalto_log('SIDEBAR='.$sidebar);

if ($sidebar) {
  foreach ($sidebars_widgets[$sidebar] as $key => $widget_name) {
//    clickalto_log($widget_name);
    if (strpos($widget_name, 'clickalto') === false) continue ;
    $is_present = true;
//    $clickalto_id = $widget_name;
    break ;
  }
}

/*$clickalto = $wp_registered_widgets[$clickalto_id];
$index = $clickalto['callback'][0]->number;
$instances = get_option('widget_clickalto');


$id = (count($instances) > 1) ? $instances[$index]['id'] : ''; */

$settings = get_option("clickalto");
$widget_id = (int) $settings['id'];
$id = $widget_id > 0 ? $widget_id : '';

?>

<style>
form { padding:10px 10px 0 10px; margin:20px 0 0 0; border:1px solid #eee; }
.warning { color:#cc0000; border:1px solid #cc0000; margin:10px 0; padding:5px; }
</style>

<div class="wrap">
  <div class="icon32"><img src="<?php echo plugins_url('clickalto/logo.png'); ?>" width="32" /></div>
  <h2>Clickalto</h2>
  <?php if (!$is_present): ?>
  <div class="warning">
    <?php _e('Widget is not in the main sidebar', 'clickalto'); ?><br />
    <?php _e('Widget must be added', 'clickalto'); ?>
    <a href="widgets.php" class="button button-primary widget-control-save"><?php _e('Add it', 'clickalto'); ?></a>
  </div>
  <?php endif; ?>
  <?php if (empty($id) || $id <= 0): ?>
  <div class="warning">
    <?php _e('Widget is not configured', 'clickalto'); ?>
  </div>
  <?php endif; ?>
  <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <p>
      <label for="clickalto_id"><?php _e('Widget code', 'clickalto'); ?> :</label>
      <input type="text" id="clickalto_id" name="widget-clickalto" value="<?php echo $id; ?>" <?php echo ((!$is_present) ? 'disabled=disabled' : ''); ?> />
      <p class="description"><?php _e('Widget code legend', 'clickalto'); ?></p>
      <input type="hidden" name="widget-clickalto-index" value="<?php echo $index; ?>" />
    </p>
    <p class="submit">
      <input type="submit" value="<?php _e('Save', 'clickalto'); ?>" class="button button-primary widget-control-save" <?php echo ((!$is_present) ? 'disabled=disabled' : ''); ?>>
    </p>
  </form>
</div>
<?php

try {

  if (isset($_POST['widget-clickalto']) && isset($_POST['widget-clickalto-index'])) {
    $index = $_POST['widget-clickalto-index'];
    $id = $_POST['widget-clickalto'];
    if (is_numeric($id)) {
      update_option('widget_clickalto',
                    array($index => array('id' => $id),
                          '_multiwidget' => 1));
    }
  }

}
catch (Exception $e) {}

?>
<?php

\spl_autoload_register(function ($class) {
  static $map = array (
  'Sukellos\\WP_Sukellos_Enable_Classic_Editor_Loader' => 'sukellos-enable-classic-editor.php',
  'Sukellos\\WP_Sukellos_Enable_Classic_Editor' => 'class-wp-sukellos-enable-classic-editor.php',
  'Sukellos\\Admin\\WP_Sukellos_Enable_Classic_Editor_Admin' => 'admin/class-wp-sukellos-enable-classic-editor-admin.php',
  'Sukellos\\Gutenberg_Manager' => 'includes/managers/class-gutenberg-manager.php',
);

  if (isset($map[$class])) {
    require_once __DIR__ . '/' . $map[$class];
  }
}, true, false);
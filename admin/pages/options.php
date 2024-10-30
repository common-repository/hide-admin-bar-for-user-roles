<?php
if (!current_user_can('manage_options')) {
    wp_die('You do not have permissions to access this page.');
}
$habfur_settings = get_option('habfur_settings');
?>
<div class="wrap about-wrap habfur-wrap">
  <h1><?php echo __(HABFUR_PLUGIN_NAME, 'hide-admin-bar-for-user-roles'); ?></h1>
  <div class="about-text"><?php echo __('Manage admin bar settings here.', 'hide-admin-bar-for-user-roles'); ?></div>

  <h2 class="nav-tab-wrapper">
    <a class="nav-tab" data-tab="habfur-settings" href="#habfur-settings" id="habfur-settings-tab">
        <?php echo __('Settings', 'hide-admin-bar-for-user-roles'); ?>
    </a>
    <a class="nav-tab" data-tab="habfur-help" href="#habfur-help" id="habfur-help-tab">
        <?php echo __('Help', 'hide-admin-bar-for-user-roles'); ?>
    </a>
  </h2>

  <div id="habfur-settings" class="habfur-tabs">
    <?php include_once('tabs/settings.php'); ?>
  </div>

  <div id="habfur-help" class="habfur-tabs">
    <?php include_once('tabs/help.php'); ?>
  </div>
</div>

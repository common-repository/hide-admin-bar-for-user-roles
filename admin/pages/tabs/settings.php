<?php
if (!current_user_can('manage_options')) {
  wp_die('You do not have permissions to access this page.');
}
global $wp_roles;
$habfur_all_roles = $wp_roles->roles;
$habfur_user = wp_get_current_user();
$habfur_settings = get_option( 'habfur_settings' );
?>
<form id="habfur-settings-form" method="post">
  <input type="hidden" name="action" value="habfur_save_settings">
  <input type="hidden" name="security" value="<?php echo wp_create_nonce("habfur-save-settings"); ?>">
  <div>
      <div class="habfur-col-7">
        <div class="habfur-row">

          <div class="habfur-col-12">
            <label class="habfur-setting-label" for="habfur_hide_all_role">
              <?php echo __('Hide for All User Roles', 'hide-admin-bar-for-user-roles'); ?>
            </label>
            <span class="habfur-setting-item">
              <label class="habfur-switch">
                <input type="checkbox" name="habfur_settings[all]" value="1" id="habfur_hide_all_role" <?php if (isset($habfur_settings['all']) && $habfur_settings['all'] == 1) echo 'checked="checked"'; ?>>
                <span class="habfur-slider habfur-round"></span>
              </label>
              <br /><br />
              <small>
                <?php echo __('Select and save to hide for all the roles or you can choose to hide for individual roles below.', 'hide-admin-bar-for-user-roles'); ?>
              </small>
            </span>
          </div>

          <div class="habfur-col-12">
            <label class="habfur-setting-label">
              <?php echo __('Hide for Selected User Roles', 'hide-admin-bar-for-user-roles'); ?>
            </label>
            <span class="habfur-setting-item" id="habfur-hide-individual-role">

              <?php foreach ($habfur_all_roles as $role_key => $role) { ?>

                <label class="habfur-switch">
                  <input type="checkbox" name="habfur_settings[<?php echo $role_key; ?>]" class="habfur_hide_individual_role" value="1" <?php if (isset($habfur_settings[$role_key]) && $habfur_settings[$role_key] == 1) echo 'checked="checked"'; ?>>
                  <span class="habfur-slider habfur-round"></span>
                </label>
                <span class="habfur-role-name">
                  <b class="habfur-m-l-7"><?php echo $role['name']; ?></b>
                  <?php if(in_array($role_key, (array) $habfur_user->roles)) echo __("<small>(Your Role)</small>", 'hide-admin-bar-for-user-roles'); ?>
                </span>
                <br /><br />
                  
              <?php } ?>

              <small>
                <?php echo __('Note: A user can have multiple roles.', 'hide-admin-bar-for-user-roles'); ?>
              </small>
            </span>
          </div>

        </div>
      </div>

  </div>
</form>

<div class="habfur-save-settings-container">
  <input type="submit" value="<?php echo __('Save Settings', 'hide-admin-bar-for-user-roles'); ?>" class="button button-large button-primary habfur-button" id="habfur-save-settings" name="save_settings">
  <div id="habfur-error-message"></div>
</div>
<h1>Register</h1>



<form method="post"  action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

  <input type="text" name="name" value="">
  <input type="text" name="email" value="">
  <input type="password" name="password" value="">
  <input type="password" name="password_confirmed" value="">
  <?php wp_nonce_field('jersey_new_user', 'jersey_new_user_form'); ?>
  <input type="hidden" name="action" value="jersey_new_user">
  <input type="submit" value="registeruser">
</form>

<?php echo do_shortcode('[alka_facebook]') ?>

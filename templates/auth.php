<h1>Auth</h1>


<form method="post"  action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

  <input type="text" name="email" value="">
  <input type="password" name="password" value="">
  <?php wp_nonce_field('jersey_login', 'jersey_login_form'); ?>
  <input type="hidden" name="action" value="jersey_login">
  <input type="submit" value="login">
</form>

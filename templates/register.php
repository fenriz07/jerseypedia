<h1>Register</h1>



<form method="post"  action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

  <input type="text" name="name" value="">
  <input type="text" name="email" value="">
  <input type="password" name="" value="">
  <input type="password_confirmed" name="" value="">

  <input type="hidden" name="action" value="jersey_new_user">
  <input type="submit" value="registeruser">
</form>

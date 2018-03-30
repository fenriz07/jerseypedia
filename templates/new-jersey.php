<h1>New Jersey</h1>
<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
<?php

?>
<form method="post"  action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
<?php
$form = new CreateJersey();
$form->render();
?>
</form>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $( 'select[name="league"]' )
      .change(function() {
        var str = "";
        $( 'select[name="league"] option:selected' ).each(function() {
          str += $( this ).val() + " ";

          jQuery.getJSON("/wp-admin/admin-ajax.php", {
            action: 'get_team', idleague: $(this).val()
            }, function(response) {

              console.log(response);
            }
          );

        });
        console.log(str);
      })
      .trigger( "change" );
  });
</script>
<?php

$form->getLeague();

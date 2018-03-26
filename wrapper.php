<?php
$template_file = jersey_template_path();
// if (! is_search() && (is_page_template('page-templates/homepage.php') || is_page_template('page-templates/landing-page.php') || is_page_template('page-templates/blank-page.php') || is_singular('lpr_quiz') || is_singular('lp_quiz') || is_page_template('page-templates/landing-no-footer.php'))) {
//     load_template($template_file);
//
//     return;
// }
get_header();
?>



  		<?php

        JPFlashMessage::init();

        echo  '<div class="wrapper">';
          load_template($template_file);
        echo '</div>';
      ?>

<?php
get_footer();

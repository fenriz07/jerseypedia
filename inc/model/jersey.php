<?php

/**
 *
 */
class JerseyModel
{
    private static $_instance = null;
    private static $post_type = 'jersey';
    private static $args;
    public static $result_post;

    private function __construct()
    {
    }

    public static function select()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function rand($post_per_page=4)
    {
        self::$args = [
          'orderby'        => 'rand',
          'posts_per_page' => $post_per_page,
        ];
        return $this;
    }

    public function recentContributions()
    {
        self::$result = 2;
        return $this;
    }

    private function query()
    {
        self::$args['post_type'] = self::$post_type;
        $posts = [];
        $query = new WP_Query(self::$args);

        // The Loop
        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
            array_push($posts, [
                  'id'    => get_the_ID(),
                  'uri'   => get_permalink(),
                  'title' => get_the_title(),
                  'meta'  => [],
                ]);
            endwhile;
        } else {
            return false;
        }

        // Reset Post Data
        wp_reset_postdata();

        return $posts;
    }

    public function get()
    {
        $result = $this->query();
        if ($result == false) {
            //No hay resultados
            return 101;
        }
        return $result;
    }
}

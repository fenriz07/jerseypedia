<?php

/**
 *
 */
class JerseyModel
{
    private static $_instance = null;
    private static $post_type = 'jersey';
    private static $args;
    public static $result;

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

    public static function set()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function view()
    {
        global $post;

        //Set the name of the Posts Custom Field.
        $count_key = 'post_views_count';

        //Returns values of the custom field with the specified key from the specified post.
        $count = get_post_meta($post->ID, $count_key, true);

        //If the the Post Custom Field value is empty.
        if ($count == '') {
            $count = 0; // set the counter to zero.

            //Delete all custom fields with the specified key from the specified post.
            delete_post_meta($post->ID, $count_key);

            //Add a custom (meta) field (Name/value)to the specified post.
            add_post_meta($post->ID, $count_key, '0');
            return $count;

        //If the the Post Custom Field value is NOT empty.
        } else {
            $count++; //increment the counter by 1.
            //Update the value of an existing meta key (custom field) for the specified post.
            update_post_meta($post->ID, $count_key, $count);

            //If statement, is just to have the singular form 'View' for the value '1'
            if ($count == '1') {
                return $count;
            }
            //In all other cases return (count) Views
            else {
                return $count;
            }
        }
    }

    public function rand($post_per_page=4)
    {
        self::$args = [
          'orderby'        => 'rand',
          'posts_per_page' => $post_per_page,
          'post_status' => 'publish',
        ];
        return $this;
    }

    public function recentContributions($post_per_page=2)
    {
        self::$args = [
              'posts_per_page' => $post_per_page,
              'offset' => 0,
              'category' => 0,
              'orderby' => 'post_date',
              'order' => 'DESC',
              'include' => '',
              'exclude' => '',
              'meta_key' => '',
              'meta_value' =>'',
              'post_status' => 'publish',
              'suppress_filters' => true
      ];
        return $this;
    }

    public function base()
    {
        self::$args['post_type'] = self::$post_type;
        $posts = [];
        $query = new WP_Query(self::$args);

        // The Loop
        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
            array_push($posts, [
                  'id'      => get_the_ID(),
                  'uri'     => get_permalink(),
                  'title'   => get_the_title(),
                ]);
            endwhile;
        } else {
            self::$result = false;
            return false;
        }

        // Reset Post Data
        wp_reset_postdata();

        self::$result = $posts;
        return $this;
    }

    public function addMeta($options = ['kit','type_kit','league','make','sponsors','colours','fabric'])
    {
        $posts = self::$result;

        foreach ($posts as $key => $post) {
            $meta = [];

            foreach ($options as $number=> $value) {
                $meta += [PREFIX_META_BOX_JP . $value => rwmb_meta(PREFIX_META_BOX_JP . $value, '', $post['id'])];
            }

            $posts[$key]['meta'] = $meta;
        }

        self::$result = $posts;

        return $this;
    }

    public function get()
    {
        return self::$result;
    }
}

<?php

/**
 *
 */
class JerseyTaxonomyModel
{
    private static $_instance = null;
    private static $post_type = 'jersey';
    public static $result;

    private function __construct()
    {
        # code...
    }

    public static function select()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function leagues()
    {
        $args = [
                'taxonomy'   => 'team',
                'hide_empty' => false,
                'parent'     => 0,
              ];

        $leagues = get_terms($args);

        self::$result = $leagues;
        return $this;
    }

    public function teamByLeague($idLeague)
    {
        $args = [
                'taxonomy'   => 'team',
                'hide_empty' => false,
                'child_of'   => $idLeague
              ];

        $teams = get_terms($args);

        self::$result = $teams;

        return $this;
    }

    public function filterIdName()
    {
        $result = [];

        $filter_terms = function ($term) {
            return [$term->term_id => $term->name];
        };

        $filter = array_map($filter_terms, self::$result);

        foreach ($filter as $key => $value) {
            foreach ($value as $key => $value) {
                $result[$key] = $value;
            }
        }

        self::$result = $result;

        return $this;
    }

    public function get()
    {
        return self::$result;
    }
}

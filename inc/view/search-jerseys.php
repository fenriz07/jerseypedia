<?php

/**
 *
 */
class JerseySearch
{
    public static function init()
    {
        $self = new self();

        self::processingPost();
    }

    private static function isAdvanceSearch()
    {
        $args = [];
        $advanced_search = filter_input(INPUT_POST, 'advanced_search', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        if (is_null($advanced_search) || $advanced_search != true) {
            return self::isTitle();
        }

        //Advanced Search

        $league = filter_input(INPUT_POST, 'league', FILTER_VALIDATE_INT);
        $team   = filter_input(INPUT_POST, 'team', FILTER_VALIDATE_INT);
        $season = filter_input(INPUT_POST, 'season', FILTER_VALIDATE_INT);

        if (isset($_POST['kit']) && $season == 0) {
            $kit = (int)$_POST['kit'];
            $args['meta_key']   =  PREFIX_META_BOX_JP . 'kit';
            $args['meta_value'] =  $kit;
        }

        if (!isset($_POST['kit']) && $season != 0) {
            $args['meta_key']   =  PREFIX_META_BOX_JP . 'since';
            $args['meta_value'] =  $season;
            $args['meta_compare'] = '>=';
        }

        if (isset($_POST['kit']) && $season != 0) {
            $kit = (int)$_POST['kit'];
            $args['meta_key']   =  PREFIX_META_BOX_JP . 'kit';
            $args['meta_value'] =  $kit;

            $args['meta_query']  = [
              'relation' => 'AND',
              [
                'key'     => PREFIX_META_BOX_JP . 'kit',
                      'value'   => $kit,
                      'compare' => '=',
              ],
              [
                'key'     => PREFIX_META_BOX_JP . 'since',
                'value'   => $season,
                'compare' => '>=',
              ]
            ];
        }

        //
        // if ($league != 0 || $team != 0) {
        //     $team_query = ($team != 0) ? $team : $league;
        //
        //     $args['tax_query'] = [[
        //     'taxonomy' => 'team',
        //     'field'    => 'term_id',
        //     'terms'    =>  $team_query,
        //     ]];
        // }


        var_dump($args);
        return $args;
    }

    private static function isTitle()
    {
        $args = [];
        $title_jersey = filter_input(INPUT_POST, 'title-jersey', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);

        //Hay 2 escenarios uno es solo enviando el title y otro es con la opcion de busquedad avanzada.
        if (!is_null($title_jersey)) {
            check_admin_referer('jersey-search', 'advanced-search');

            if (empty(trim($title_jersey))) {
                return $args;
            }
            $args['s'] = $title_jersey;
        }
        return $args ;
    }

    public static function processingPost()
    {
        echo '<pre>';
        var_dump(JerseyModel::select()->advancedSearch(self::isAdvanceSearch())->base()->addMeta()->get());
        echo '</pre>';
    }
}

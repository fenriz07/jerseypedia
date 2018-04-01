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
        $advanced_search = filter_input(INPUT_POST, 'advanced_search', FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
        if (is_null($advanced_search)) {
            return self::isTitle();
        }
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
        var_dump(JerseyModel::select()->advancedSearch(self::isAdvanceSearch())->base()->get());
        echo '</pre>';
    }
}

<?php

include JERSEY_DIR . '/inc/model/jersey-taxonomy.php';

/**
 *
 */
class CreateJersey
{
    public function __construct()
    {
        add_action('admin_post_create_jersey', [$this, 'processingPost']);
        add_action('wp_ajax_get_team', [$this, 'getTeam']);
    }

    private function action()
    {
        echo '<input type="hidden" name="action" value="create_jersey">';
        echo '<input type="submit" value="create jersey">';
    }

    private function select($field, $return = null)
    {
        $select_base = '<select name="'.$field['name'].'">%s</select>';
        $option_base = '<option value="%s">%s</option>';
        $options = '';
        $input = '';

        foreach ($field['options'] as $key => $option) {
            $options .= sprintf($option_base, $key, $option);
        }

        $input = sprintf($select_base, $options);
        if ($return != null) {
            return $input;
        }
        echo $input;
    }

    private function text($field)
    {
        $text_base = '<input type="text" name="%s">';
        echo sprintf($text_base, $field['name']);
    }

    private function printInput($namefield)
    {
        $field = JerseyModel::select()->metabox()->field($namefield);
        call_user_func([$this,$field['type']], $field);
    }

    public function render()
    {
        $this->printInput('kit');
        $this->printInput('type_kit');
        $this->printInput('league');
        $this->printInput('make');
        $this->printInput('sponsors');
        $this->printInput('colours');
        $this->printInput('fabric');
        wp_nonce_field('create_jersey', 'create_jersey_form');
        $this->action();
    }

    public function processingPost()
    {
        $failures = [];
        $int  = [
                      [
                        'name'     => 'kit',
                        'emessage' => __('Select a valid kit', JERSEY_DOMAIN_TEXT),
                      ],
                      [
                        'name'     => 'type_kit',
                        'emessage' => __('Select a valid type kit', JERSEY_DOMAIN_TEXT),
                      ],
                      // [
                      //   'name'     => 'team',
                      //   'emessage' => __('Select a valid team', JERSEY_DOMAIN_TEXT),
                      // ],
                    ];
        $text = [
                    [
                      'name'     => 'league',
                      'emessage' => __('Select a valid league', JERSEY_DOMAIN_TEXT),
                    ],
                    [
                      'name'     => 'make',
                      'emessage' => __('Select a valid make', JERSEY_DOMAIN_TEXT),
                    ],
                    [
                      'name'     => 'sponsors',
                      'emessage' => __('Select a valid sponsors', JERSEY_DOMAIN_TEXT),
                    ],
                    [
                      'name'     => 'colours',
                      'emessage' => __('Select a valid colours', JERSEY_DOMAIN_TEXT),
                    ],
                    [
                      'name'     => 'fabric',
                      'emessage' => __('Select a valid fabric', JERSEY_DOMAIN_TEXT),
                    ],
                ];

        foreach ($int as $key => $input) {
            if ($input['name'] != 'team') {
                $select =  filter_input(INPUT_POST, $input['name'], FILTER_VALIDATE_INT, ["options"=>["min_range"=>1, "max_range"=>4]]);
            } else {
                $select =  filter_input(INPUT_POST, $input['name'], FILTER_VALIDATE_INT);
            }

            if ($select  == false) {
                $failures[] = $input['emessage'];
            }
        }

        foreach ($text as $key => $input) {
            $select =  filter_input(INPUT_POST, $input['name'], FILTER_SANITIZE_STRING, ['options'=>['flags' => FILTER_NULL_ON_FAILURE]]);
            if (is_null($select)) {
                $failures[] = $input['emessage'];
            }
        }

        if (count($failures)>0) {
            JPFlashMessage::FlashMessage($failures);
        }

        $this->createPost($_POST);
    }

    private function createPost($data)
    {
        $jersey = [
          'post_title'   => 'Probando',
          'post_content' => '<h1>Hello</h1>',
          'post_type'    => 'jersey',
          'post_status'  => 'pending',
        ];

        wp_insert_post($jersey);

        //TODO: NOTIFICAR CON UN EMAIL AL ADMIN.
    }

    public function getTeam()
    {
        $idLeague = isset($_GET['idleague']) ? $_GET['idleague'] : 1;

        $teams = JerseyTaxonomyModel::select()->teamByLeague($idLeague)->filterIdName()->get();

        echo json_encode(['teams' => $teams]);
        wp_die();
    }

    public function getLeague()
    {
        $field = [
                    'name'    => 'league',
                    'options' => [0=>__("Select League", JERSEY_DOMAIN_TEXT)],
                 ];
        $field['options'] += JerseyTaxonomyModel::select()->leagues()->filterIdName()->get();
        $this->select($field);
    }
}

new CreateJersey();

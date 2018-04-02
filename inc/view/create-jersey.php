<?php

include JERSEY_DIR . '/inc/model/jersey-taxonomy.php';

/**
 *
 */
class CreateJersey
{
    public function __construct()
    {
        $this->inputs = [
          'kit',
          'type_kit',
          'league',
          'make',
          'sponsors',
          'fabric',
          'colours',
          'since',
          'until'
        ];

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

    private function number($field)
    {
        $text_base = '<input type="number" name="%s" placeholder="%s">';
        echo sprintf($text_base, $field['name'], $field['placeholder']);
    }

    private function printInput($namefield)
    {
        $field = JerseyModel::select()->metabox()->field($namefield);
        call_user_func([$this,$field['type']], $field);
    }

    public function render()
    {
        foreach ($this->inputs as $key => $input) {
            $this->printInput($input);
        }
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
                        'with_option' => true,
                      ],
                      [
                        'name'     => 'type_kit',
                        'emessage' => __('Select a valid type kit', JERSEY_DOMAIN_TEXT),
                        'with_option' => true,
                      ],
                      // [
                      //   'name'     => 'team',
                      //   'emessage' => __('Select a valid team', JERSEY_DOMAIN_TEXT),
                      // ],
                      [
                        'name'        => 'since',
                        'emessage'    => __('Select a valid since year', JERSEY_DOMAIN_TEXT),
                        'with_option' => false,
                      ],
                      [
                        'name'     => 'until',
                        'emessage' => __('Select a valid until year', JERSEY_DOMAIN_TEXT),
                        'with_option' => false,
                      ],
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
            if ($input['with_option']) {
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

        $this->createPost($_POST, $_FILES);
    }

    private function createPost($data, $files)
    {
        /*
          Supongamos que por data me traigo el RealMadrid cuyo id = 5
        */

        $id_team = 5;

        $team = get_term($id_team, 'team');

        $title_jersey = "{$team->name} | {$data['since']} - {$data['until']}";

        echo $team->name;

        $jersey = [
          'post_title'    => $title_jersey,
          'post_name'     => $team->name,
          'post_content'  => '<h1>Hello</h1>',
          'post_type'     => 'jersey',
          'post_status'   => 'pending',
        ];

        //var_dump($this->inputs);

        $jersey_id = wp_insert_post($jersey);

        if (is_wp_error($jersey_id)) {
            JPFlashMessage::FlashMessage($jersey_id->get_error_message());
        }

        foreach ($this->inputs as $key => $input) {
            $key_mb = PREFIX_META_BOX_JP . $input;
            add_post_meta($jersey_id, $key_mb, $data[$input]);
        }

        $add_term = wp_set_post_terms($jersey_id, $id_team, 'team');

        if (is_wp_error($add_term)) {
            JPFlashMessage::FlashMessage($add_term->get_error_message());
        }

        // TODO: Subir imagenes

        $jerseygallery = new jerseyGallery();
        $jerseygallery->gallerySingleJersey($files, $jersey_id, 2);


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

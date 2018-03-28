<?php

/**
 *
 */
class JerseyRating
{
    public function __construct()
    {
        $this->key   = PREFIX_META_BOX_JP . 'rating';
    }

    public function getRating($jersey_id)
    {
        $rating = get_post_meta($jersey_id, $this->key, true);


        if ($rating == null) {
            $rating  = [
                          'stats'  => [
                                        'number_votes' => 0,
                                        'total_points' => 0,
                                        'dec_avg'      => 0,
                                        'whole_avg'    => 0,
                                      ],
                          'voters' => [],
                        ];
            $rating = serialize($rating);

            delete_post_meta($jersey_id, $this->key);

            add_post_meta($jersey_id, $this->key, $rating);
        }

        return unserialize($rating);
    }

    private function getIpUser()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    private function hasVoted($voters)
    {
        return (in_array($this->getIpUser(), $voters)) ? true : false;
    }

    public function setRating($jersey_id, $vote)
    {
        $rating = $this->getRating($jersey_id);
        $voters = $rating['voters'];

        if ($this->hasVoted($voters)) {
            JPFlashMessage::FlashMessage(__('The user has already voted', JERSEY_DOMAIN_TEXT));
        }

        $rating['stats']['number_votes'] += 1;
        $rating['stats']['total_points'] += $vote;
        $rating['stats']['dec_avg']   = round($rating['stats']['total_points'] /$rating['stats']['number_votes'], 1);
        $rating['stats']['whole_avg'] = round($rating['stats']['dec_avg']);

        array_push($rating['voters'], $this->getIpUser());

        $rating = serialize($rating);
        update_post_meta($jersey_id, $this->key, $rating);

        JPFlashMessage::FlashMessage(__('Vote add', JERSEY_DOMAIN_TEXT));
    }

    /*
      Listen to the {post} from jerseysingle
    */
    public function hearPost()
    {
        add_action('admin_post_jersey_rating_form', array( $this, 'processingPost' ));
    }

    public function processingPost()
    {


        //This function is executed only if the user is authenticated

        if (!isset($_POST['jersey_id'])) {
            JPFlashMessage::FlashMessage(__('Jersey not exist', JERSEY_DOMAIN_TEXT));
        }

        if (!isset($_POST['rating'])) {
            JPFlashMessage::FlashMessage(__('No Vote', JERSEY_DOMAIN_TEXT));
        }

        $jersey_id = $_POST['jersey_id'];
        $vote      = $_POST['rating'];


        $validate_jersey_id = filter_var($jersey_id, FILTER_VALIDATE_INT);
        $validate_rating    = filter_var($vote, FILTER_VALIDATE_INT, array("options"=>array("min_range"=>1, "max_range"=>5)));

        if ($validate_jersey_id == false) {
            JPFlashMessage::FlashMessage(__('Jersey not exist', JERSEY_DOMAIN_TEXT));
        }

        if ($validate_rating == false) {
            JPFlashMessage::FlashMessage(__('Vote Invalid', JERSEY_DOMAIN_TEXT));
        }

        /* TODO: Validar con nonce....
        */

        $this->setRating($jersey_id, $vote);
    }
}

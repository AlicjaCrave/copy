<?php

namespace oMotive\PostType;

 // custom post type 
class RewardPostType extends PostType
{

    const POST_TYPE_KEY =  'reward';
    const POST_TYPE_NAME = 'Récompense';
    const POST_TYPE_SLUG = 'reward';
    const POST_TYPE_REST_BASE = 'rewards';
    const POST_TYPE_ICON = 'dashicons-awards';
    

    const CAPABILITIES = [
        'edit_posts' => 'edit_rewards',
        'publish_posts' => 'publish_rewards',
        'edit_post' => 'edit_reward',
        'read_post' => 'read_reward',
        'delete_post' => 'delete_reward',
        'edit_others_posts' => 'edit_others_rewards',
        'delete_others_posts' =>  'delete_others_rewards',
    ];

    

        const ADMIN_CAPS = [
            'edit_rewards' => true,
            'publish_rewards' => true,
            'edit_reward' => true,
            'read_reward' => true,
            'delete_reward' => true,
            'edit_others_rewards' => true,
            'delete_others_rewards' => true
        ];
}
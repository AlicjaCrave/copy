<?php

namespace oMotive\PostType;

// custom post type 
class ResolutionPostType extends PostType
{

    const POST_TYPE_KEY =  'resolution';
    const POST_TYPE_NAME = 'Résolutions';
    const POST_TYPE_SLUG = 'resolution';
    const POST_TYPE_REST_BASE = 'resolutions';
    const POST_TYPE_ICON = 'dashicons-calendar-alt';
    

    const CAPABILITIES = [
        'edit_posts' => 'edit_resolutions',
        'publish_posts' => 'publish_resolutions',
        'edit_post' => 'edit_resolution',
        'read_post' => 'read_resolution',
        'delete_post' => 'delete_resolution',
        'edit_others_posts' => 'edit_others_resolutions',
        'delete_others_posts' =>  'delete_others_resolutions',
    ];

    

        const ADMIN_CAPS = [
            'edit_resolutions' => true,
            'publish_resolutions' => true,
            'edit_resolution' => true,
            'read_resolution' => true,
            'delete_resolution' => true,
            'edit_others_resolutions' => true,
            'delete_others_resolutions' => true
        ];
}
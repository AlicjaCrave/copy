<?php

namespace oMotive\PostType;

// custom post type 
class CustomResolutionPostType extends PostType
{

    const POST_TYPE_KEY =  'customResolution';
    const POST_TYPE_NAME = 'Custom Résolutions';
    const POST_TYPE_SLUG = 'customResolution';
    const POST_TYPE_REST_BASE = 'CustomResolutions';
    const POST_TYPE_ICON = 'dashicons-hammer';
    

    const CAPABILITIES = [
        'edit_posts' => 'edit_custom_resolutions',
        'publish_posts' => 'publish_custom_resolutions',
        'edit_post' => 'edit_custom_resolution',
        'read_post' => 'read_custom_resolution',
        'delete_post' => 'delete_custom_resolution',
        'edit_others_posts' => 'edit_others_custom_resolutions',
        'delete_others_posts' =>  'delete_others_custom_resolutions',
    ];

    

        const ADMIN_CAPS = [
            'edit_custom_resolutions' => true,
            'publish_custom_resolutions' => true,
            'edit_custom_resolution' => true,
            'read_custom_resolution' => true,
            'delete_custom_resolution' => true,
            'edit_others_custom_resolutions' => true,
            'delete_others_custom_resolutions' => true
        ];
}
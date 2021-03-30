<?php

namespace oMotive\PostType;
 
// custom post type 
class TestimonyPostType extends PostType
{

    const POST_TYPE_KEY =  'testimony';
    const POST_TYPE_NAME = 'Témoignages';
    const POST_TYPE_SLUG = 'testimony';
    const POST_TYPE_REST_BASE = 'testimonials';
    const POST_TYPE_ICON = 'dashicons-buddicons-pm';
    

    const CAPABILITIES = [
        'edit_posts' => 'edit_testimonials',
        'publish_posts' => 'publish_testimonials',
        'edit_post' => 'edit_testimony',
        'read_post' => 'read_testimony',
        'delete_post' => 'delete_testimony',
        'edit_others_posts' => 'edit_others_testimonials',
        'delete_others_posts' =>  'delete_others_testimonials',
    // Do we want to have special capabilities for the admin? 
    // Yes, because we have Custom Post Type which is not avalaible in the 'native' Wrodpress.
    ];

        const ADMIN_CAPS = [
            'edit_testimonials' => true,
            'publish_testimonials' => true,
            'edit_testimony' => true,
            'read_testimony' => true,
            'delete_testimony' => true,
            'edit_others_testimonials' => true,
            'delete_others_testimonials' => true
        ];
}
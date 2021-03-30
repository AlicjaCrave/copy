<?php 

namespace oMotive\PostType;

class PostType {
    static public function register()
    {
        register_post_type(
            static::POST_TYPE_KEY, // CPT ID 
            [
                'label' => static::POST_TYPE_NAME,
                'public' => true,
                'has_archive' => true,
                "rewrite" => [
                    'slug' => static::POST_TYPE_SLUG
                ],
                'supports' => [
                    'title',
                    'author',
                    'editor',
                    'excerpt',
                    'thumbnail',  
                ],
                'capabilities' => static::CAPABILITIES,
                'map_meta_cap' => true,
                'show_in_rest' => true,
                'rest_base' => static::POST_TYPE_REST_BASE,
                'menu_icon' => static::POST_TYPE_ICON,
                'menu_position' => 4,
                'taxonomies' => ['category'],
            ]
        );
    }
    // add admin specific caps for the custom post type
    static public function addAdminCaps()
    {
        $adminRole = get_role('administrator');
        foreach (static::ADMIN_CAPS as $cap => $grant) {
            $adminRole->add_cap($cap, $grant);
        }
    }

    // remove admin specific caps for the custom post type 
    static public function removeAdminCaps()
    {
        $adminRole = get_role('administrator');
        foreach (static::ADMIN_CAPS as $cap => $grant) {
            $adminRole->remove_cap($cap);
        }
    }
}
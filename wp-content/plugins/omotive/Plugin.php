<?php

namespace oMotive;

use oMotive\PostType\TestimonyPostType;
use oMotive\PostType\ResolutionPostType;
use oMotive\PostType\CustomResolutionPostType;
use oMotive\PostType\RewardPostType;
use oMotive\Role\UserRole;
use oMotive\Taxonomy\GradeTaxonomy;
use oMotive\Classes\Database;
use oMotive\Classes\RouterReward;
use oMotive\Classes\RouterResolution;
use oMotive\Classes\RouterCustomResolution;
use oMotive\Classes\RouterUser;
use oMotive\Classes\RouterTestimony;

use WP_REST_Request;

class Plugin
{
    static public function run()
    {
        // Whitelisting Endpoints JWT-Auth
        add_filter('jwt_auth_whitelist', function ($endpoints) {

            return array(
                '/wp-json/omotive/v1/users',
                '/wp-json/omotive/v1/users/*',
                '/wp-json/omotive/v1/testimonials',
                '/wp-json/omotive/v1/testimonials/*',
                '/omotive/O-Motive/wp-json/omotive/v1/users',
                '/omotive/O-Motive/wp-json/omotive/v1/users/*',
                '/omotive/O-Motive/wp-json/omotive/v1/testimonials',
                '/omotive/O-Motive/wp-json/omotive/v1/testimonials/*',
                '/O-Motive/wp-json/omotive/v1/users',
                '/O-Motive/wp-json/omotive/v1/users/*',
                '/O-Motive/wp-json/omotive/v1/testimonials',
                '/O-Motive/wp-json/omotive/v1/testimonials/*',
            );
        });


        // Registration of the CPT (custom post type) at the 'init' hook
        add_action('init', [self::class, 'registerPostTypes']);

        // Registration of the custom taxonomies.
        add_action('init', [self::class, 'registerTaxonomies']);

        // Function rest_api_init allows us to construct custom routes.
        add_action('rest_api_init', [self::class, 'registerRoute']);



        register_activation_hook(OMOTIVE_PLUGIN_FILE, [self::class, 'onPluginActivation']);
        register_deactivation_hook(OMOTIVE_PLUGIN_FILE, [self::class, 'onPluginDeactivation']);

        // We react to the registration. 
        // 'wp_rest_user_user_register' allows to give the newly registered user 
        // a custom role. Here ('utilisateur').
        add_action('wp_rest_user_user_register', [self::class, 'userRegistered']);

        
    }

    // Defines a default role, avatar, city and image backround when the user registers.
    static public function userRegistered($user)
    {
        $user->set_role(get_option('default_role'));        
        $avatar = "http://ec2-3-92-198-2.compute-1.amazonaws.com/O-Motive/wp-content/uploads/2021/02/penguin-sur-le-fond-transperent.png";
        add_user_meta($user->ID, 'avatar', wp_slash($avatar));
        $city = "ville";
        add_user_meta($user->ID, 'city', wp_slash($city));
        $imagebackground = "http://ec2-3-92-198-2.compute-1.amazonaws.com/O-Motive/wp-content/uploads/2021/02/jeremy-cai-ucYWe5mzTMU-unsplash-scaled.jpg";
        add_user_meta($user->ID, 'imagebackground', wp_slash($imagebackground));
    }

    // Registration of CPT
    static public function registerPostTypes()
    {
        TestimonyPostType::register();
        ResolutionPostType::register();
        CustomResolutionPostType::register();
        RewardPostType::register();
    }

    // Registration of custom taxonomy
    static public function registerTaxonomies()
    {
        GradeTaxonomy::register();
    }

    // Call for the functions that will be activated with the plugin omotive.
    static public function onPluginActivation()
    {
        TestimonyPostType::addAdminCaps();
        ResolutionPostType::addAdminCaps();
        CustomResolutionPostType::addAdminCaps();
        RewardPostType::addAdminCaps();
        UserRole::add();
        GradeTaxonomy::addAdminCaps();
        Database::generateTables();

        // update_option function allows to change the default role 
        // of the user that just registered.
        // Here, it's 'const' utilisateur, defined in Role -> UserRole
        update_option('default_role', UserRole::ROLE_KEY);
    }

    // Deactivates the functions when the plugin is deactivated.  
    static public function onPluginDeactivation()
    {
        TestimonyPostType::removeAdminCaps();
        ResolutionPostType::removeAdminCaps();
        CustomResolutionPostType::removeAdminCaps();
        RewardPostType::removeAdminCaps();
        UserRole::remove();
        GradeTaxonomy::removeAdminCaps();

        // When plugin omotive deactivated, the default role 
        // becomes again 'user'.
        update_option('default_role', 'subscriber');
    }

    // Calling our functions.
    static public function registerRoute()
    {
        RouterUser::getUsers();
        RouterUser::getUser();
        RouterUser::editUser();
        RouterUser::deleteUser();
        RouterReward::addRewarduser();
        RouterReward::getUserRewards();
        RouterResolution::addResolutionUser();
        RouterResolution::getUserResolutions();
        RouterResolution::getUserArchivedResolutions();
        RouterResolution::delResolutionUser();
        RouterResolution::editResolutionUser();
        RouterTestimony::addTestimony();
        RouterTestimony::getTestimonials();
        RouterCustomResolution::addCustomResolutionUser();
        RouterCustomResolution::addCustomResolutionUser();
        RouterCustomResolution::getCustomUserResolutions();
        RouterCustomResolution::getUserCustomArchivedResolutions();
        RouterCustomResolution::delCustomResolutionUser();
        RouterCustomResolution::editCustomResolutionUser();
    }
}

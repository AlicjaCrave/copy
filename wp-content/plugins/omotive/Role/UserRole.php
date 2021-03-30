<?php

namespace oMotive\Role;

// our custom role 'utilisateur'
class UserRole {
    
    const ROLE_KEY = 'utilisateur';
    const ROLE_NAME = 'Utilisateur';

    static public function add()
    {
        add_role(
            self::ROLE_KEY, // name of the role
            self::ROLE_NAME, // display name of the role
            // capabilities
            [
                //CPT testimonials
                'read' => true,
                'edit_posts' => true, 
                'edit_testimonials' => true, 
                'publish_testimonials' => false,
                'edit_testimony'  => true,
                'read_testimony'  => true,
                'delete_testimony'  => true,
                'edit_others_testimonials' => false, 
                'delete_others_testimonials' => false,
                //taxo grade
                'manage_grade_testimonials' => true,
                'edit_grade_testimonials' => true,
                'delete_grade_testimonials' => true,
                'assign_grade_testimonials' => true,
                
                //CPT resolutions
                'edit_resolutions'=> true,
                'publish_resolutions'=> false,
                'edit_resolution'=> false,
                'read_resolution'=> true,
                'delete_resolution'=> false,
                'edit_others_resolutions'=> false,
                'delete_others_resolutions'=> false,

                //CPT rewards
                'edit_rewards' => true,
                'publish_rewards' => false,
                'edit_reward' => false,
                'read_reward' => true,
                'delete_reward' => false,
                'edit_others_rewards' => false,
                'delete_others_rewards' => false
            ]
        );
    }                        
         // remove the 'utilisateur' role
        static public function remove()
        {
            remove_role(self::ROLE_KEY);
        }
    }
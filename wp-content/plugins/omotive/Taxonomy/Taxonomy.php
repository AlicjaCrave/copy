<?php

namespace oMotive\Taxonomy;

class Taxonomy {
    // registers the custom taxonomy
    static public function register()
    {
        register_taxonomy(
            static::TAXONOMY_KEY, // we choose the name of our taxonomy
            static::RELATED_CPT_LIST, // we choose the list of the CPT that our taxonomy will be attached to.
            [
                'hierarchical' => true, // make it hierarchical (like categories) => we leave it 'true' for the reasons of the user experience in the backoffice.
                'labels' => ['name' =>  static::TAXONOMY_NAME],
                'show_ui' => true,
                'show_in_rest' => true,
                'capabilities' => static::CAPABILITIES   
            ]     
        );
    }
     // add admin specific caps for the taxonomy
     // allows to add custom capacities to our custom taxonomy.
    static public function addAdminCaps()
    {
        // retrive the administrator role
        $adminRole = get_role('administrator');
        // for every capacity in our current admin CPT, we add a capacity
        foreach (static::ADMIN_CAPS as $cap => $grant) {
            $adminRole->add_cap($cap, $grant);
        }
    }
    // remove admin specific caps for the taxonomy
    static public function removeAdminCaps()
    {
        // retrive the administrator role
        $adminRole = get_role('administrator');
        // for every capacity in our current admin CPT, we remove a capacity
        foreach (static::ADMIN_CAPS as $cap => $grant) {
            $adminRole->remove_cap($cap);
        }
    }
}
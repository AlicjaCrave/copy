<?php

namespace oMotive\Taxonomy;

use oMotive\PostType\TestimonyPostType;

// our custom taxonomy for the grade functionality 
// that gives rights to the users to write the testimonies. (CPT)
class GradeTaxonomy extends Taxonomy {

    const TAXONOMY_KEY = 'grade';
    const TAXONOMY_NAME = 'Note';
    const RELATED_CPT_LIST = [TestimonyPostType::POST_TYPE_KEY];
    
    const CAPABILITIES =  [
        'manage_terms' => 'manage_grade_testimonials',
        'edit_terms' => 'edit_grade_testimonials',
        'delete_terms' => 'delete_grade_testimonials',
        'assign_terms' => 'assign_grade_testimonials'
    ];

    const ADMIN_CAPS = [
        'manage_grade_testimonials' => true,
        'edit_grade_testimonials' => true,
        'delete_grade_testimonials' => true,
        'assign_grade_testimonials' => true,
    ];
}



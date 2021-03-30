<?php

namespace oMotive\Classes;

use oMotive\Classes\Database;


use WP_REST_Request;
use WP_Error;
use WP_REST_Response;

// the list of our custom routes.
class RouterTestimony
{
    const ROUTE_NAME = 'omotive/v1';  // const is the name of the route.

    // adds a resolution to a user
    static public function addTestimony()
    {
        register_rest_route(self::ROUTE_NAME, '/testimonials', [
            'methods' => 'POST',
            'callback' => function (WP_REST_Request $request) {
                $current_user = wp_get_current_user();
                $userid = $current_user->data->ID;
                $resolutionid = $request->get_param("resolutionid");
                $title = $request->get_param("title");
                $content = $request->get_param("content");
                $grade = $request->get_param("grade");

                if ($grade == 1) {
                    $gradeId = 2;
                }
                if ($grade == 2) {
                    $gradeId = 3;
                }
                if ($grade == 3) {
                    $gradeId = 4;
                }
                if ($grade == 4) {
                    $gradeId = 5;
                }
                if ($grade == 5) {
                    $gradeId = 6;
                }

                $status = 2;

                // Get the category ID
                $category = get_the_category($resolutionid);
                $categoryId = $category[0]->cat_ID;

                if ($userid === null || $resolutionid === null) {
                    return new WP_Error('400', 'error');
                } else {
                    // we want to retrive the id of the testimony that will be created.
                    // insert the post into the database
                    $postId = wp_insert_post([
                        'post_title'    => $title,
                        'post_content'  => $content,
                        'post_status'   => 'pending',
                        'post_author'   => $userid,
                        'post_type' => 'testimony',
                        'tax_input' => ['grade'     => $gradeId, 'category' => $categoryId]
                    ]);

                    Database::archiveResolutionsByUser($userid, $resolutionid, $status);
                    $response = new WP_REST_Response('sucess');
                    return $response;
                }
            }
        ]);
    }
    // retrives the number of testimonials specified in the parameter of the route
    static public function getTestimonials()
    {
        register_rest_route(self::ROUTE_NAME, '/testimonials/(?P<numb>\d+)', [
            'methods' => 'GET',
            'callback' => function (WP_REST_Request $request) {
                $numberPostsPerPage = $request->get_param("numb");
                $testimonials = get_posts(['posts_per_page' => $numberPostsPerPage, 'post_type' => 'testimony']);

                $testimonyData = [];

                foreach ($testimonials as $testimony) {

                    $content = $testimony->post_content;
                    $authorId = $testimony->post_author;

                    $testimonyId = $testimony->ID;

                    $category = get_the_category($testimonyId);
                    $categoryName = $category[0]->name;
                    $categoryId = $category[0]->cat_ID;


                    $grade = get_the_terms($testimonyId, "grade");
                    $gradeLevel = $grade[0]->name;


                    $userData = get_userdata($authorId);
                    $displayName = $userData->data->display_name;

                    $userMetaData = get_user_meta($authorId);
                    $avatar = $userMetaData["avatar"][0];

                    $data = ['content' => $content, 'author_name' => $displayName, 'grade' => $gradeLevel, 'testimony_id' => $testimonyId, 'category_name' => $categoryName, 'category_id' =>  $categoryId, 'avatar_author' => $avatar];
                    array_push($testimonyData, $data);
                }

                return ($testimonyData);
            }

        ]);
    }
}

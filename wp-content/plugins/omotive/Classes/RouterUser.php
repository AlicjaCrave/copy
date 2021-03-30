<?php

namespace oMotive\Classes;

use oMotive\Classes\Database;


use WP_REST_Request;
use WP_Error;
use WP_REST_Response;

// the list of our custom routes.
class RouterUser
{
  const ROUTE_NAME = 'omotive/v1';  // const is the name of the route.

  // retrives the list of the all the users. 
  static public function getUsers()
  {
    register_rest_route(self::ROUTE_NAME, '/users', [
      'methods' => 'GET',
      'callback' => function (WP_REST_Request $request) {
        $users = get_users(['role__in' => 'utilisateur']);

        if ($users === null) {
          return new WP_Error();
        }

        $datausers = []; // we start with an empty array
        foreach ($users as $user) // for every user in the array we retrive the asked informations.
        {
          $userid = $user->data->ID;
          $first_name = get_user_meta($userid, 'first_name');
          $last_name = get_user_meta($userid, 'last_name');
          $nickname = get_user_meta($userid, 'nickname');
          $description = get_user_meta($userid, 'description');
          $city = get_user_meta($userid, 'city');
          $avatar = get_user_meta($userid, 'avatar');
          $imagebackground = get_user_meta($userid, 'imagebackground');

          // we have created the $usermetadata array to retrive the informations together in the request response.
          $usermetadata = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'nickname' => $nickname,
            'description' => $description,
            'city' => $city,
            'avatar' => $avatar,
            'imagebackground' => $imagebackground,
          ];

          $userdata = [$user->data, $usermetadata];

          array_push($datausers, $userdata);
        }
        $response = new WP_REST_Response($datausers);
        return $response;
      }
    ]);
  }

  // get one user. 
  static public function getUser()
  {
    register_rest_route(self::ROUTE_NAME, '/users/(?P<id>\d+)', [
      'methods' => 'GET',
      'callback' => function (WP_REST_Request $request) {
        $userid = $request->get_param("id");
        $user = get_users(['search' => $userid]);

        //user meta data
        $first_name = get_user_meta($userid, 'first_name');
        $last_name = get_user_meta($userid, 'last_name');
        $nickname = get_user_meta($userid, 'nickname');
        $description = get_user_meta($userid, 'description');
        $city = get_user_meta($userid, 'city');
        $avatar = get_user_meta($userid, 'avatar');
        $imagebackground = get_user_meta($userid, 'imagebackground');

        $usermetadata = [
          'first_name' => $first_name,
          'last_name' => $last_name,
          'nickname' => $nickname,
          'description' => $description,
          'city' => $city,
          'avatar' => $avatar,
          'imagebackground' => $imagebackground,
        ];
        //User data + meta data
        $userdata = [$user[0]->data, $usermetadata];

        if ($user[0]->roles == ["utilisateur"]) {
          $response = new WP_REST_Response($userdata);
          return $response;
        } else {
          return new WP_Error('400', 'error');
        }
      }
    ]);
  }
  // allows user to edit his/her personal information.
  static public function editUser()
  {
    register_rest_route(self::ROUTE_NAME, '/users', [
      'methods' => 'PATCH',
      'callback' => function (WP_REST_Request $request) {
        $current_user = wp_get_current_user();
        $userid = $current_user->data->ID;

        // User data
        $user_pass = $request->get_param("user_pass");
        $user_nicename = $request->get_param("user_nicename");
        $user_email = $request->get_param("user_email");
        $user_url = $request->get_param("user_url");
        $display_name = $request->get_param("display_name");
        // User data Meta
        $first_name = $request->get_param("first_name");
        $last_name = $request->get_param("last_name");
        $nickname = $request->get_param("nickname");
        $city = $request->get_param("city");
        $description = $request->get_param("description");
        $avatar = $request->get_param("avatar");
        $imagebackground = $request->get_param("imagebackground");

        // Update user data
        if ($user_pass) {
          wp_update_user(['ID' => $userid, 'user_pass' => $user_pass]);
        }
        if ($user_nicename) {
          wp_update_user(['ID' => $userid, 'user_nicename' => $user_nicename]);
        }
        if ($user_email) {
          wp_update_user(['ID' => $userid, 'user_email' => $user_email]);
        }
        if ($user_url) {
          wp_update_user(['ID' => $userid, 'user_url' => $user_url]);
        }
        if ($display_name) {
          wp_update_user(['ID' => $userid, 'display_name' => $display_name]);
        }

        // Update User meta
        if ($first_name) {
          update_user_meta($userid, 'first_name', wp_slash($first_name));
        }
        if ($last_name) {
          update_user_meta($userid, 'last_name', wp_slash($last_name));
        }
        if ($nickname) {
          update_user_meta($userid, 'nickname', wp_slash($nickname));
        }

        $caracters = strlen($description);

        // we limit the number of the caracters to 250

        // we add condition 'if' to be able to use the method PATCH correctly
        // (to be able to edit just the choosen fields, without having to send the whole request each time).
        if ($caracters > 250) {
          return new WP_Error('400', 'Texte de description trop long');
        } else {
          update_user_meta($userid, 'description', wp_slash($description));
        }
        // we create custom metadata to attach to an user ('city', 'avatar','imagebackground')
        if ($city) {
          if (!get_user_meta($userid, 'city')) {
            add_user_meta($userid, 'city', wp_slash($city));
          } else {
            update_user_meta($userid, 'city', wp_slash($city));
          }
        }

        if ($avatar) {
          if (!get_user_meta($userid, 'avatar')) {
            add_user_meta($userid, 'avatar', wp_slash($avatar));
          } else {
            update_user_meta($userid, 'avatar', wp_slash($avatar));
          }
        }

        if ($imagebackground) {
          if (!get_user_meta($userid, 'imagebackground')) {
            add_user_meta($userid, 'imagebackground', wp_slash($imagebackground));
          } else {
            update_user_meta($userid, 'imagebackground', wp_slash($imagebackground));
          }
        }

        $response = new WP_REST_Response('User edited');
        return $response;
      }
    ]);
  }
  // allows a user to delete his/hers account.
  static public function deleteUser()
  {
    register_rest_route(self::ROUTE_NAME, '/users', [
      'methods' => 'DELETE',
      'callback' => function (WP_REST_Request $request) {
        $current_user = wp_get_current_user();
        wp_delete_user($current_user->ID);
        $response = new WP_REST_Response('utilisateur supprimé');
        return $response;
      }
    ]);
  }

}





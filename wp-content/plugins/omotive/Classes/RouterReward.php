<?php

namespace oMotive\Classes;

use oMotive\Classes\Database;


use WP_REST_Request;
use WP_Error;
use WP_REST_Response;

class RouterReward {

const ROUTE_NAME = 'omotive/v1';  // const is the name of the route.

 // adds a reward to a user.
 static public function addRewardUser()
 {
   register_rest_route(self::ROUTE_NAME, '/addrewarduser', [
     'methods' => 'POST',
     'callback' => function (WP_REST_Request $request) {
       $current_user = wp_get_current_user();
       $userid = $current_user->data->ID;
       $rewardid = $request->get_param("rewardid");

       if ($userid === null || $rewardid === null) {
         return new WP_Error('400', 'error');
       } else {
         Database::addRewardByUser($userid, $rewardid);
         $response = new WP_REST_Response('reward added');
         return $response;
       }
     }
   ]);
 }
 // retrives all the user's rewards.
 static public function getUserRewards()
 {
   register_rest_route(self::ROUTE_NAME, '/userrewards/(?P<id>\d+)', [
     'methods' => 'GET',
     'callback' => function (WP_REST_Request $request) {
       $userId = (int)$request->get_param('id');
       $user = get_user_by('ID', $userId);

       if ($user == false) {
         return new WP_REST_Response();
       }

       $response =  Database::getRewardsByUserId($userId);
       if ($response == null) {
         return new WP_REST_Response();
       }
       return $response;
     }
   ]);
 }


}
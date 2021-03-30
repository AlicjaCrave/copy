<?php

namespace oMotive\Classes;

use oMotive\Classes\Database;


use WP_REST_Request;
use WP_Error;
use WP_REST_Response;

class RouterResolution {

  const ROUTE_NAME = 'omotive/v1';  // const is the name of the route
  
  // adds a resolution to a user
  static public function addResolutionUser()
  {
    register_rest_route(self::ROUTE_NAME, '/addresolutionuser', [
      'methods' => 'POST',
      'callback' => function (WP_REST_Request $request) {
        $current_user = wp_get_current_user();
        $userid = $current_user->data->ID;
        $resolutionid = $request->get_param("resolutionid");

        if ($userid === null || $resolutionid === null) {
          return new WP_Error('400', 'error');
        } else {
          Database::addResolutionsByUser($userid, $resolutionid);
          $response = new WP_REST_Response('sucess');
          return $response;
        }
      }
    ]);
  }
  // retrives user's resolutions
  static public function getUserResolutions()
  {
    register_rest_route(self::ROUTE_NAME, '/userresolutions/(?P<id>\d+)', [
      'methods' => 'GET',
      'callback' => function (WP_REST_Request $request) {
        $userId = (int)$request->get_param('id');
        $user = get_user_by('ID', $userId);

        if ($user == false) {
          return new WP_REST_Response();
        }

        $response = [];

        $resolutions =  Database::getResolutionsByUserId($userId);

        // we search  for the 'resolutions' and for each resolution we retrive the information 
        // that we need: title, urlthumbnail, status, duration and resolution id.
        foreach ($resolutions as $resolution) {
          $post = get_post($resolution->resolution_id);
          $title = get_the_title($post);
          $urlthumbnail = get_the_post_thumbnail_url($post);
          $status = $resolution->status;
          $duration = $resolution->duration;
          $resolutionid = $resolution->resolution_id;
          $dateStart = $resolution->date_start;
          $dateFinish = $resolution->date_finish;

          if ($status == 0 || $status == 1 ){
            $resolutiondata = ['resolution_id' => $resolutionid, 'post_title' => $title, 'url_thumbnail' => $urlthumbnail, 'status' => $status, 'date_start' => $dateStart, 'date_finish' => $dateFinish,'duration' => $duration];
          array_push($response, $resolutiondata);
          }
          
        }

        // on récupère id des resolutions puis on boucle dessus pour récupérer les posts avec les fonctions comme get_the_post_thumbnail_url


        if ($resolutions == null) {
          return new WP_REST_Response();
        }

        return $response;
      }
    ]);
  }
  // allows user to delete his/her resolution
  static public function delResolutionUser()
  {
    register_rest_route(self::ROUTE_NAME, '/delresolutionuser', [
      'methods' => 'DELETE',
      'callback' => function (WP_REST_Request $request) {
        $current_user = wp_get_current_user();
        $userid = $current_user->data->ID;
        $resolutionid = $request->get_param("resolutionid");

        if ($userid === null || $resolutionid === null) {
          return new WP_Error('400', 'error');
        } else {
          Database::delResolutionsByUser($userid, $resolutionid);
          $response = new WP_REST_Response('sucess');
          return $response;
        }
      }
    ]);
  }
  // allows user to edit his/hers resoltions
  static public function editResolutionUser()
  {
    register_rest_route(self::ROUTE_NAME, '/editresolutionuser', [
      'methods' => 'PUT',
      'callback' => function (WP_REST_Request $request) {
        $current_user = wp_get_current_user();
        $userid = $current_user->data->ID;
        $resolutionid = $request->get_param("resolutionid");
        $status = $request->get_param("status");
        $duration = $request->get_param("duration");
        
        $firstrewardId = 262;

        if($status !=0){
          $dateFinish = date_create();
        }
        if ($status ==1) {
          // the first reward is added to the user profile when the status of the resolution 
          // changes from 0 to 1 (completed). 
          // for the future develepoement: retrive the $rewardId to make it dynamic.
          Database::addRewardByUser($userid, $firstrewardId);
        }
        
        if ($userid === null || $resolutionid === null) {
          return new WP_Error('400', 'error');
        } else {
          Database::editResolutionsByUser($userid, $resolutionid, $status, $duration, $dateFinish);
          $response = new WP_REST_Response('sucess');
          return $response;
        }
      }
    ]);
  }
 // retrives user's resolutions
 static public function getUserArchivedResolutions()
 {
   register_rest_route(self::ROUTE_NAME, '/userarchivedresolutions/(?P<id>\d+)', [
     'methods' => 'GET',
     'callback' => function (WP_REST_Request $request) {
       $userId = (int)$request->get_param('id');
       $user = get_user_by('ID', $userId);

       if ($user == false) {
         return new WP_REST_Response();
       }

       $response = [];

       $resolutions =  Database::getResolutionsByUserId($userId);

       // we search  for the 'resolutions' and for each resolution we retrive the information 
       // that we need: title, urlthumbnail, status, duration and resolution id.
       foreach ($resolutions as $resolution) {
         $post = get_post($resolution->resolution_id);
         $title = get_the_title($post);
         $urlthumbnail = get_the_post_thumbnail_url($post);
         $status = $resolution->status;
         $duration = $resolution->duration;
         $resolutionid = $resolution->resolution_id;
         $dateStart = $resolution->date_start;
         $dateFinish = $resolution->date_finish;

         if($status >1){
          $resolutiondata = ['resolution_id' => $resolutionid, 'post_title' => $title, 'url_thumbnail' => $urlthumbnail, 'status' => $status, 'date_start' => $dateStart, 'date_finish' => $dateFinish,'duration' => $duration];
          array_push($response, $resolutiondata);
         }
                
       }

       // on récupère id des resolutions puis on boucle dessus pour récupérer les posts avec les fonctions comme get_the_post_thumbnail_url


       if ($resolutions == null) {
         return new WP_REST_Response();
       }

       return $response;
     }
   ]);
 }
}
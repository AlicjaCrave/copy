<?php

namespace oMotive\Classes;
use oMotive\Classes\Day;

class Database
{
    public static function generateTables()
    {
        // we retrive the connector between data base and Wordpress (which is provided by WP)
        global $wpdb;

        // we retrive the configuration of the WP, the character set which we will use in our MySQL table.
        $charsetCollate = $wpdb->get_charset_collate();

        // we retrive the name of the table which was defined in omotive.php
        $tableNameResolution = OMOTIVE_RESOLUTION_USER_TABLE_NAME;
        $tableNameReward = OMOTIVE_REWARD_USER_TABLE_NAME;

        // we generate our table to manage the relations between user and resolution
        $sqlResolution = "
            CREATE TABLE IF NOT EXISTS `{$tableNameResolution}` (
                `user_id` BIGINT(20) UNSIGNED NOT NULL,
                `resolution_id` BIGINT(20) UNSIGNED NOT NULL,
                `status` BOOL NOT NULL,
                `duration` VARCHAR(4000) NOT NULL,
                `date_start` DATETIME NULL,
                `date_finish` DATETIME NULL,
                PRIMARY KEY(`user_id`, `resolution_id`)
            ) {$charsetCollate};
        ";

        $sqlReward = "
            CREATE TABLE IF NOT EXISTS `{$tableNameReward}` (
                `user_id` BIGINT(20) UNSIGNED NOT NULL,
                `reward_id` BIGINT(20) UNSIGNED NOT NULL,
                PRIMARY KEY(`user_id`, `reward_id`)
            ) {$charsetCollate};
        ";

        // we execute the request
        $wpdb->query($sqlResolution);
        $wpdb->query($sqlReward);
    }

    // add a new relationship between user and reward
    static public function addRewardByUser($userPostId, $rewardId)
    {  
        global $wpdb;

        $tableName = OMOTIVE_REWARD_USER_TABLE_NAME;

        $sql = "
            INSERT INTO `{$tableName}` 
            (`user_id`, `reward_id`) 
            VALUES 
            (%d, %d)
        ";

        $preparedQuery = $wpdb->prepare(
            $sql,
            [
                $userPostId,
                $rewardId,
            ]
        );
 
        // executes the prepared request.
        $success = $wpdb->query($preparedQuery);       
        return !!$success;
    }

    // retrives the user's rewards
    static public function getRewardsByUserId($userId)
    {
        global $wpdb;

        $tableName = OMOTIVE_REWARD_USER_TABLE_NAME;

        $sql = "
            SELECT * FROM `{$tableName}` WHERE `user_id`={$userId}
        ";

        $rewardUserRelationshipList = $wpdb->get_results($sql);
        $response = [];
        foreach ($rewardUserRelationshipList as $reward) {
            $rewardId = $reward->reward_id;
            $rewardImage = get_the_post_thumbnail_url($rewardId);
            $results = ["userId" => $userId, "rewardId" => $rewardId, "image" => $rewardImage];

            array_push($response, $results);
        }

        return $response;        
    }

    // add a new relationship between an user and a resolution
    static public function addResolutionsByUser($userPostId, $resolutionId)
    {
        global $wpdb;

        $tableName = OMOTIVE_RESOLUTION_USER_TABLE_NAME;
        // default values
        $status = 0;
        $dateStart = date_create();
        $dateFormatSQL = date_format($dateStart, 'Y-m-d');

        // we call the class to define a new object. 
        // each object represents a day in the calendar with the values of day and state
        $day1 = new Day(); $day1->day = '1'; $day1->status = '0';
        $day2 = new Day (); $day2->day = '2';  $day2->status = '0';
        $day3 = new Day(); $day3->day = '3'; $day3->status = '0';
        $day4 = new Day (); $day4->day = '4';  $day4->status = '0';
        $day5 = new Day(); $day5->day = '5'; $day5->status = '0';
        $day6 = new Day (); $day6->day = '6';  $day6->status = '0';
        $day7 = new Day(); $day7->day = '7'; $day7->status = '0';
        $day8 = new Day (); $day8->day = '8';  $day8->status = '0';
        $day9 = new Day(); $day9->day = '9'; $day9->status = '0';
        $day10 = new Day (); $day10->day = '10';  $day10->status = '0';
        $day11 = new Day(); $day11->day = '11'; $day11->status = '0';
        $day12 = new Day (); $day12->day = '12';  $day12->status = '0';
        $day13 = new Day(); $day13->day = '13'; $day13->status = '0';
        $day14 = new Day (); $day14->day = '14';  $day14->status = '0';
        $day15 = new Day(); $day15->day = '15'; $day15->status = '0';
        $day16 = new Day (); $day16->day = '16';  $day16->status = '0';
        $day17 = new Day(); $day17->day = '17'; $day17->status = '0';
        $day18 = new Day (); $day18->day = '18';  $day18->status = '0';
        $day19 = new Day (); $day19->day = '19';  $day19->status = '0';
        $day20 = new Day(); $day20->day = '20'; $day20->status = '0';
        $day21 = new Day (); $day21->day = '21';  $day21->status = '0';
        $day22 = new Day(); $day22->day = '22'; $day22->status = '0';
        $day23 = new Day (); $day23->day = '23';  $day23->status = '0';
        $day24 = new Day(); $day24->day = '24'; $day24->status = '0';
        $day25 = new Day (); $day25->day = '25';  $day25->status = '0';
        $day26 = new Day(); $day26->day = '26'; $day26->status = '0';
        $day27 = new Day (); $day27->day = '27';  $day27->status = '0';
        $day28 = new Day (); $day28->day = '28';  $day28->status = '0';
        $day29 = new Day(); $day29->day = '29'; $day29->status = '0';
        $day30 = new Day (); $day30->day = '30';  $day30->status = '0';
        
        $calendar = 
        [
            $day1, $day2, $day3, $day4, $day5, $day6, $day7, $day8, $day9, $day10, 
           $day11, $day12, $day13, $day14, $day15, $day16, $day17, $day18, $day19, $day20,
           $day21, $day22, $day23, $day24, $day25, $day26, $day27, $day28, $day29, $day30
        ];
        
        $duration = serialize($calendar);

        $sql = "
            INSERT INTO `{$tableName}` 
            (`user_id`, `resolution_id`, `status`, `duration`, `date_start` ) 
            VALUES 
            (%d, %d, %d, %s, %s)
        ";

        $preparedQuery = $wpdb->prepare(
            $sql,
            [
                $userPostId,
                $resolutionId,
                $status,
                $duration,
                $dateFormatSQL
            ]
        );

       $success = $wpdb->query($preparedQuery);
       return !!$success;
    }

    // retrieve resolutions attached to a specific user
    static public function getResolutionsByUserId($userId)
    {
        global $wpdb;

        $tableName = OMOTIVE_RESOLUTION_USER_TABLE_NAME;

        $uploadDirInfos = wp_upload_dir();
        $uploadDir = $uploadDirInfos['baseurl'];

        // 1er option
        $sql = "
        SELECT resolution_id, status, duration, date_start, date_finish
        FROM wp_user_resolution
        WHERE user_id = {$userId}
        ";

        // 2e option
        /* $sql = "
        SELECT p.post_title, 
               p.post_content, 
               CONCAT( '".$uploadDir."','/', pm2.meta_value) as thumbnail_url, 
               ur.duration,
               ur.status
        FROM `wp_posts` as p       
        lEFT JOIN `wp_user_resolution` as ur ON p.ID = ur.resolution_id
        LEFT JOIN `wp_postmeta` as pm ON p.ID = pm.post_id
        AND pm.meta_key = '_thumbnail_id'
        LEFT JOIN `wp_postmeta` as pm2 on pm2.post_id = pm.meta_value
        AND pm2.meta_key = '_wp_attached_file'
        WHERE ur.user_id = {$userId}
        "; */
 
        $resolutionUserRelationshipList = $wpdb->get_results($sql);
      
        foreach ($resolutionUserRelationshipList as $value) {
            $serializedduration = $value->duration;
            $duration = unserialize($serializedduration);
            $value->duration = $duration;
        }
                
      return $resolutionUserRelationshipList ;
        
    }

    // delete relationship between resolution and an user
    static public function delResolutionsByUser($userPostId, $resolutionId)
    {
        global $wpdb;

        $tableName = OMOTIVE_RESOLUTION_USER_TABLE_NAME;
           
        $sql = "
            DELETE FROM  `{$tableName}` 
            WHERE
            `user_id` =  %d
            AND
            `resolution_id` = %d
            ";

        $preparedQuery = $wpdb->prepare(
            $sql,
            [
                $userPostId,
                $resolutionId
            ]
        );

       $success = $wpdb->query($preparedQuery);
       return !!$success;
    }

     // edit a  relationship between a resolution and a user
    static public function editResolutionsByUser($userPostId, $resolutionId, $status, $duration, $dateFinish)
    {
        global $wpdb;

        $tableName = OMOTIVE_RESOLUTION_USER_TABLE_NAME;

        $calendar = serialize($duration);
        
        $dateFormatSQL = date_format($dateFinish, 'Y-m-d');

        $sql = "
            UPDATE `{$tableName}`
            SET
            `duration` = %s,
            `status` = %d,
            `date_finish` = %s
            WHERE
            `user_id` =  %d
            AND
            `resolution_id` = %d
        ";

        $preparedQuery = $wpdb->prepare(
            $sql,
            [
                $calendar,
                $status,
                $dateFormatSQL,
                $userPostId,
                $resolutionId,
          
            ]
        );
       
       $success = $wpdb->query($preparedQuery);
       return !!$success;
    }

    // archives the resolutions of which status is 2 (or more).
    static public function archiveResolutionsByUser($userPostId, $resolutionId, $status)
    {
        global $wpdb;

        $tableName = OMOTIVE_RESOLUTION_USER_TABLE_NAME;

        $sql = "
            UPDATE `{$tableName}`
            SET
            `status` = %d
            WHERE
            `user_id` =  %d
            AND
            `resolution_id` = %d
        ";
       
        $preparedQuery = $wpdb->prepare(
            $sql,
            [
                $status,
                $userPostId,
                $resolutionId,
            ]
        );
     
       $success = $wpdb->query($preparedQuery);
       
       return !!$success;
    }

    

    
    // add a new relationship between an user and a testimony
    static public function addTestimonyByUser($userPostId, $title, $content, $grade, $category)
    {
        global $wpdb;

        $tableName = 'wp_posts';
        
        $postType = "testimony";
        $postStatus = "pending";
        
        $sql = "
            INSERT INTO `{$tableName}` 
            (`post_author`, `post_title`, `post_content`, `post_type`, `post_status`) 
            VALUES 
            (%d, %s, %s, %s, %s)
        ";

        $preparedQuery = $wpdb->prepare(
            $sql,
            [
                $userPostId,
                $title,
                $content,
                $postType,
                $postStatus
            ]
        );

       $success = $wpdb->query($preparedQuery);

      
       return !!$success;
    }


}


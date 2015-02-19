<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('display_errors', 'on');
error_reporting(E_ALL);

$dbhandle = pgconnect('localhost', 'postgres', 'thakkar');
//var_dump($dbhandle);

if (isset($_REQUEST['ajax'])) {
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_player_count') {
        $sold = get_count_of_player($dbhandle);
        $array = array();
        $array['tobesold'] = $array['sold'] = $array['unsold'] = 0;
        foreach ($sold as $s) {
            if ($s['sold'] == 0) {
                $array['tobesold'] = $s['count_total'];
            } elseif ($s['sold'] == 1) {
                $array['sold'] = $s['count_total'];
            } elseif ($s['sold'] == 2) {
                $array['unsold'] = $s['count_total'];
            }
        }

        echo json_encode($array);
    } else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_previous_player') {

        $user_id = trim($_REQUEST['user_id']);
        $player = get_previous_player($dbhandle, $user_id);

        echo json_encode($player);

        update_user($dbhandle, 0, $user_id, 0, $player[0]['previous_user_id'], 0);
    } else if (isset($_REQUEST['action'])) {

        $dbhandle = pgconnect('localhost', 'postgres', 'thakkar');
        $player = get_player($dbhandle);
        $user_id = trim($_REQUEST['user_id']);
        $prev_user_id = trim($_REQUEST['prev_user_id']);
        set_player($dbhandle, $user_id, $prev_user_id, 2);
        echo json_encode($player);
    } else {
        $dbhandle = pgconnect('localhost', 'postgres', 'thakkar');
        $bid_value = trim($_REQUEST['bid_value']);
        $user_id = trim($_REQUEST['user_id']);
        $prev_user_id = trim($_REQUEST['previous_user_id']);
        $captain_id = trim($_REQUEST['captain_id']);
        update_user($dbhandle, $bid_value, $user_id, $captain_id, $prev_user_id, 1);
        $player = get_player($dbhandle);
        echo json_encode($player);
    }

    return true;
} else if (isset($_REQUEST['teamlist'])) {

    $team_players = get_team_listing($dbhandle);
//    var_dump($team_players);
    $teams = array();
    foreach ($team_players as $tp) {
        if (!is_array($teams[$tp['captain_id']])) {
            $teams[$tp['captain_id']] = array();
        }
        $temparr = array('name' => $tp['name'], 'bid_value' => $tp['bid_value']);
        array_push($teams[$tp['captain_id']], $temparr);
    }
    $captain = get_captain($dbhandle);
    foreach ($captain as $cap) {
        $captains[$cap['id']] = $cap['name'];
    }
    include_once 'team_listing.tpl.php';
} else {
    if ($dbhandle) {
        $captain = get_captain($dbhandle);
        $player = get_player($dbhandle);
        $sold = get_count_of_player($dbhandle);

        $tobesold = $solded = $unsold = 0;
        foreach ($sold as $s) {
            if ($s['sold'] == 0) {
                $tobesold = $s['count_total'];
            } elseif ($s['sold'] == 1) {
                $solded = $s['count_total'];
            } elseif ($s['sold'] == 2) {
                $unsold = $s['count_total'];
            }
        }
    }
    include_once 'auction.tpl.php';
}

function pgconnect($host, $username, $password) {
    $tries = 0;
    if (!($handle = pg_connect("host={$host} user={$username} password={$password} dbname=oferta")) && $tries++ < 3) {
        // $this->errorString[] = __FILE__ . "::" . __FUNCTION__ . "::" . __LINE__ . "::$dbHost, $dbUser, $dbPass::" . mysqli_error($this->link);
    }
    if ($tries > 2) {
        return false;
    }
    return $handle;
}

function get_previous_player($handle, $user_id) {
    $query = 'select * from users where id = ' . $user_id;
    return mysql_fire_query($handle, $query);
}

function update_user($handle, $bid_value, $user_id, $captain_id, $prev_user_id, $sold = 1) {
    $query = 'update users set sold=' . $sold . ', previous_user_id = ' . $prev_user_id . ', bid_value=' . $bid_value . ', captain_id = ' . $captain_id . ' where id = ' . $user_id;
    return mysql_fire_query($handle, $query);
}

function get_count_of_player($handle) {
    $query = 'select sold, count(*) as count_total from users where is_captain != 1 group by sold';
    return mysql_fire_query($handle, $query);
}

function get_team_listing($handle) {
    $query = 'select is_captain, name,bid_value, captain_id from users where captain_id != 0';
    return mysql_fire_query($handle, $query);
}

function get_captain($handle) {
    $query = 'select * from users where is_captain = 1';
    return mysql_fire_query($handle, $query);
}

function set_player($handle, $user_id, $prev_user_id, $sold) {
    $sql = 'update users set sold=' . $sold . ', previous_user_id = ' . $prev_user_id . ' where id = ' . $user_id;
    return mysql_fire_query($handle, $sql);
}

function get_player($handle) {
    $sql = 'SELECT * FROM users where is_captain != 1 AND bid_value = 0 ORDER BY RANDOM() LIMIT 1';
    return mysql_fire_query($handle, $sql);
}

function mysql_fire_query($handle, $sql) {
//    echo $sql;
    //reset : update users set captain_id = 0, bid_value = 0, sold =0 , previous_user_id = 0 
    $return = array();

    $query = pg_query($handle, $sql);

    if ($query) {

//        do {
            $resultSet = array();
            while ($row = pg_fetch_assoc($query)) {

                $resultSet[] = $row;
            }

            $return[] = $resultSet;
            @pg_free_result($result);

            /*
              if (mysqli_more_results($this->link)) {
              printf("-----------------\n");
              }
             *
             */
//        } while (@mysqli_next_result($handle));
    }

    return $return[0];
}

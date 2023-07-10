<?php
/**
 * Template Name: Location List
 */
  //session_start();
  global $wpdb;
  require_once('config.php');
  $red_login_url = site_url().'/wp-admin/admin.php?page=home';
  if($_SESSION['token']=='')
  {
    ob_start();
    header('Location: '.$red_login_url);
    ob_end_flush();
  }
  $accesstoken = 'Bearer '.$auth_token; 

  if(time() - $_SESSION['timestamp'] > 80400000) 
  {
    $accesstoken = 'Bearer '.$auth_token; 
    $url = $refresh_token_api;
    $headers = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json', 
            'Authorization' => $accesstoken
        );
    $arg = array(
    'headers' => $headers,
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true
    );
    $res = wp_remote_get($refresh_token_api,$arg);
    $output = wp_remote_retrieve_body($res);
    $output = json_decode( $output, true );
    $_SESSION['token'] =  $output['jwt']; //die;
    $_SESSION['timestamp'] = time();
  }


  $get_wallet_url = 'https://cl-api.delhivery.com/user/';

  $headers = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json', 
            'Authorization' => $accesstoken
        );
  //print_r($headers);
  $arg = array(
    'headers' => $headers,
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true
  );
  $w_res = wp_remote_get(esc_url($get_wallet_url),$arg);
  $w_output = wp_remote_retrieve_body($w_res);
  $w_output = json_decode( $w_output, true );
  $wallet_id = $w_output['wallet_id'];
  $get_balance_url = 'https://api-bird.delhivery.com/proxy/wallet/'.$wallet_id;
  $c_res = wp_remote_get(esc_url($get_balance_url),$arg);
  $c_output = wp_remote_retrieve_body($c_res);
  $c_output = json_decode( $c_output, true );
  $current_balance = $c_output['current_balance'];
?>
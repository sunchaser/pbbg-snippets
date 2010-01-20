<?php
/****************************************************************************
File:   vote.php
Desc:   
Author: Sunchaser [mik342003@yahoo.it]
****************************************************************************/

function sendvote( $listname )
{

$listname = mysql_real_escape_string( $_REQUEST['listname'] );

// load data of rating site

$data = mysql_query( 
	"select id, voting_link 
  from cfg_ranking_sites
  where name = '{$listname}' " );

list( $list_id, $voting_link) = mysql_fetch_row( $data );

// generate a rnd string
$k =  substr( md5 ( time() . rand(1, 10000000) ), 1, 25);

// get the user id from session 
$user = User::get_char_info();
$u = $user['user_id'];

// place the generated random key and trace the vote
$urlcalled = str_replace( "KEY", $k, $voting_link );

mysql_query( "insert into 
	voting_log
  ( user_id, vkey, list_id, ip, status, urlcalled ) 
  values
  ( '{$u}', '{$k}', $list_id, '".$_SERVER['REMOTE_ADDR']."', 'new', '{$urlcalled}' ) ");

// send the vote
header ( "location: $urlcalled" );
exit;
}
?>
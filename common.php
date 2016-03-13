<?php
session_start();

//Connect to the mysql server
//Add your own details here
        $connection = mysqli_connect('server', 'username', 'password');
        if (!$connection) {
            die('Could not connect: ' . mysql_error());
        }
     
//Function is used to strip quotes and any unwanted characters that can be harmful to SQL commands
//A very quick SQL injection prevention method - a small security increase
function make_safe( $value, $force_quotes = false )
{
	if( is_numeric( $value ) && !$force_quotes )
		return $value;

	if( get_magic_quotes_gpc() )
		$value = stripslashes( $value );

    return "'".@mysql_real_escape_string( $value )."'";
}
?>
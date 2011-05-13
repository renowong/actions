<?php
class User{
	var $userid;
	var $login;
	var $password;
	var $email;
	var $level;
	var $first;
	var $last;
	var $active;

	function userid( ) {
		echo $this->userid;
	}
   
	function login( ) {
		echo $this->login;
	}
   
	function password( ) {
		echo $this->password;
	}
   
	function email( ) {
		echo $this->email;
	}
   
	function level( ) {
		echo $this->level;
	}

	function first( ) {
		echo $this->first;
	}
	
	function last( ) {
		echo $this->last;
	}
	
	function active( ) {
		echo $this->active;
	}

 }
?>



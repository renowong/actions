<?php
class Task{
	var $date;
	var $progress;
	var $deadline;
	var $project;
	var $title;
	var $description;
	var $incharge;
	var $comments;
	var $active;
	var $userid;
	var $enddate;
	var $copies;

	function date( ) {
		echo $this->date;
	}
   
	function progress( ) {
		echo $this->progress;
	}
   
	function deadline( ) {
		echo $this->deadline;
	}
   
	function project( ) {
		echo $this->project;
	}
   
	function title( ) {
		echo $this->title;
	}

	function description( ) {
		echo $this->description;
	}
	
	function incharge( ) {
		echo $this->incharge;
	}

	function comments( ) {
		echo $this->comments;
	}

	function active( ) {
		echo $this->active;
	}

	function userid( ) {
		echo $this->userid;
	}

	function enddate( ) {
		echo $this->enddate;
	}
	
	function copies( ) {
		echo $this->copies;
	}
 }
?>



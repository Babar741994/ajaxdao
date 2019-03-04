<?php
include("DAO.php");
if(isset($_POST["group_no"])){
	
	$clients=filter_var($_POST["clients"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	$year=filter_var($_POST["year"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	$year=filter_var($_POST["month"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	$group_number = filter_var($_POST["group_no"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	$dao = new DAO();

	$dao->set_clientlist($clients);
	$dao->set_year($year);
	$dao->set_month($month);
	echo $dao->getData($group_number,0);



}

?>
<?php
include("config.php");
class DAO{
	
		var $clientlist;
		function set_clientlist($new_clientlist) {
		 	 $this->clientlist = $new_clientlist;


		}	
 
		function get_clientlist() {		
		 	 return $this->clientlist;		
		 }		
 
 var $year;
		function set_year($new_year) {
		 	 $this->year = $new_year;

		}	
 
		function get_year() {		
		 	 return $this->year;		
		 }		
 
 var $month;
		function set_month($new_month) {
		 	 $this->month = $new_month;
		 //	 $getmonth=$new_month;
		}	
 
		function get_month() {		
		 	 return $this->month;		
		 }		
 


	public function dbConnect(){
		
		$dbhost = DB_SERVER; // set the hostname
		$dbname = DB_DATABASE ; // set the database name
		$dbuser = DB_USERNAME ; // set the mysql username
		$dbpass = DB_PASSWORD;  // set the mysql password

		try {
			$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
			$dbConnection->exec("set names utf8");
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $dbConnection;

		}
		catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	
		
	}
	
	public function getData($groupNo,$getclient){

		//$getclient=  $this->month;	
		// $getclient= "helo";	
		// $getclient=$this->month;

		try{
			// echo "<script type='text/javascript'>alert('".$getmonth."');</script>";
		//	echo "bb".$getmonth;

		}catch (Exception $e){

			echo "hello";
			echo 'Caught: ' . $e->getMessage();
		}
		// echo "<script type='text/javascript'>alert('".$getclient."');</script>";


		//sanitize post value
		$group_number = $groupNo;
		$items_per_group = 5 ; 

		//throw HTTP error if group number is not valid
		if(!is_numeric($group_number)){
			header('HTTP/1.1 500 Invalid number!');
			exit();
		}

		//get current starting point of records
		$position = ($group_number * $items_per_group);

		$teststring="Gul Ahmed";
		$counttotallines = substr_count($teststring, ',') + 1;
		
		try {
			$dbConnection = $this->dbConnect();
			$stmt = $dbConnection->prepare("SELECT customers.*,

				SUM(CASE WHEN flag = 'Mobile Call' 
				OR 			  flag = 'Temp Mobile Call' THEN 1 ELSE 0 END) 									   										AS 'mobilecalls',
				SUM(CASE WHEN flag = 'Transit'		    THEN 1 ELSE 0 END) 															   				AS 'transitcalls',
				SUM(CASE WHEN flag = 'Onnet'            THEN 1 ELSE 0 END) 																	   		AS 'onnetcalls',
				SUM(CASE WHEN flag = 'NWD POP'  		THEN 1 ELSE 0 END) 																	   		AS 'nwdpopcalls',
				SUM(CASE WHEN flag = 'NWD' 				THEN 1 ELSE 0 END) 																		    AS 'nwdcalls',
				SUM(CASE WHEN flag = 'local' 
				OR flag = 'local (shortcode)'THEN 1 ELSE 0 END) 										   					   				AS 'landlinecalls',
				SUM(CASE WHEN flag = 'isd' 			    THEN 1 ELSE 0 END) 																		    AS 'internationalcalls',
				SUM(CASE WHEN flag = 'Forwarded'        THEN 1 ELSE 0 END) 																   			AS 'forwardedcalls',
				SUM(CASE WHEN flag = 'Mobile Call' 
				OR flag = 'Temp Mobile Call' THEN CEIL(duration/60) ELSE 0 END) 					   										AS 'mobileminutes',
				SUM(CASE WHEN flag = 'Onnet' 			THEN CEIL(duration/60) ELSE 0 END)														   	AS 'onnetminutes',
				SUM(CASE WHEN flag = 'NWD POP' 			THEN CEIL(duration/60) ELSE 0 END)													        AS 'nwdpopminutes',
				SUM(CASE WHEN flag = 'NWD' 				THEN CEIL(duration/60) ELSE 0 END)														    AS 'nwdminutes',
				SUM(CASE WHEN flag = 'Transit'  		THEN CEIL(duration/60) ELSE 0 END) 													   		AS 'transitminutes',
				SUM(CASE WHEN flag = 'local' 
				OR flag = 'local (shortcode)'THEN CEIL(duration/60) ELSE 0 END) 						   									AS 'landlineminutes',
				SUM(CASE WHEN flag = 'isd' 				THEN CEIL(duration/60) ELSE 0 END) 														    AS 'internationalminutes',
				SUM(CASE WHEN flag = 'Forwarded' 		THEN CEIL(duration/60) ELSE 0 END) 												   			AS 'forwardedminutes',
				SUM(CASE WHEN flag = 'Mobile Call'
				OR flag = 'Temp Mobile Call' THEN CEIL(duration/customers.mobilepulse) ELSE 0 END)  										AS 'pulsemobile',
				SUM(CASE WHEN flag = 'Onnet'		    THEN CEIL(duration/customers.onnetpulse) ELSE 0 END)									    AS 'pulseonnet',
				SUM(CASE WHEN flag = 'NWD POP' 			THEN  CEIL(duration/customers.nwdpoppulse) ELSE 0 END)								   		AS 'pulsenwdpop',
				SUM(CASE WHEN flag = 'NWD' 				THEN CEIL(duration/customers.nwdpulse) ELSE 0 END)										    AS 'pulsenwd',
				SUM(CASE WHEN flag = 'Transit'  		THEN CEIL(duration/customers.transitpulse) ELSE 0 END) 								   		AS 'pulsetransit',
				SUM(CASE WHEN flag = 'local'
				OR flag = 'local (shortcode)'THEN CEIL(duration/customers.localpulse) ELSE 0 END)		  							    AS 'pulselocal',
				SUM(CASE WHEN flag = 'isd' 				THEN CEIL(duration/60) ELSE 0 END) 														    AS 'pulseinternational',
				SUM(CASE WHEN flag = 'Forwarded' 		THEN CEIL(duration/customers.callfwdpulse) ELSE 0 END) 							   		    AS 'pulseforwarded',
				SUM(CASE WHEN flag = 'isd' 				THEN CEIL(duration/60)*(SELECT rate FROM isd_tarrif WHERE groups = tag LIMIT 1) ELSE 0 END) AS 'internationalcharges'

				FROM cdr_answer
				INNER JOIN customers ON cdr_answer.client =customers.billingcontext AND customers.billingcontext IN ('Gul Ahmed' ) WHERE date_time LIKE '2018-12-%' GROUP BY customers.billingcontext LIMIT :position , :items_per_group");
			$stmt->bindParam(':position', $position , PDO::PARAM_INT);
			$stmt->bindParam(':items_per_group', $items_per_group, PDO::PARAM_INT);
			$stmt->execute();

			$Count = $stmt->rowCount(); 
			//echo " Total Records Count : $Count .<br>" ;

			$result ="" ;
			if ($Count  > 0){
				while($data=$stmt->fetch(PDO::FETCH_ASSOC)) {

					$result = $result .
					"<div class='user-post-right-display-main-container-upto-last'  >

					<div  style='margin-left:10px;' >
					<span style='margin-left:12px;' >
					<h1>
					<a href='".$data['billingcontext']."' style='text-decoration:none;'>".$data['billingcontext']." <a/>
					</h1>
					</span>
					<span>".$data['billingcontext']."</span>
					</div>
					</div> " ;

					

				}
				return $result ;
			}
			else{
				echo "error in query";
			}

		}
		catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	}//end of Get Data
	
	public function getTotalNumberOfRecordsCount(){
		$items_per_group = 5 ;
		
		try {
			// $dbConnection = $this->dbConnect();
			// $stmt = $dbConnection->prepare("");
			// $stmt->execute();

			// $Count = $stmt->rowCount(); 
			//echo " Total Records Count : $Count .<br>" ;
			$Count=$counttotallines;

			if ($Count  > 0){
				$data=$stmt->fetch(PDO::FETCH_ASSOC) ;
				$total_groups = ceil($counttotallines/$items_per_group);
				return $total_groups;

				
			}

		}
		catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	}
	
	
}


?>
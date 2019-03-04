<?php
include("DAO.php");



$dao = new DAO();

$clients = $_GET['clients'];
$year = $_GET['year'];
$month = $_GET['month'];
$clients = rtrim($clients, ",");
$splittedClients = explode(",", $clients);
$client_lst = "'" . implode("','", $splittedClients) ."'";


?>

<html>
<head>
<script src="jquery-3.2.1.min.js"></script>
<style>
.user-post-right-display-main-container-upto-last
{
float:left;
line-height:20px;
min-height:50px;
word-wrap:break-word;
overflow:hidden;
display:block;
font-size:15px;
width:600px;
margin-top:15px;
background-color:white;
border:1px solid #d6d7da;
border-radius:4px;
-moz-border-radius:4px;
-webkit-border-radius:4px;

  box-shadow: 0 2px 10px #cbcbcb;
 -moz-box-shadow: 0 2px 10px #cbcbcb;
 -webkit-box-shadow: 0 2px 10px #cbcbcb;

}
.user-post-display-body-main-container:after {
 content: ".";
 display: block;
 height: 0;
 clear: both;
 visibility: hidden;
}
.animation-load{
float:left;
min-height:30px;
text-align: center;
width:600px;
font-size:18px;
margin-bottom:15px;
margin-top:15px;
background-color:white;
border:1px solid #d6d7da;
border-radius:4px;
padding-top:5px;
-moz-border-radius:4px;
-webkit-border-radius:4px;
  box-shadow: 0 2px 10px #cbcbcb;
 -moz-box-shadow: 0 2px 10px #cbcbcb;
 -webkit-box-shadow: 0 2px 10px #cbcbcb;
 display:none;
 font-weight: bold;
cursor: pointer;	
}


</style>


<script type="text/javascript">



$(document).ready(function() {
	var track_load = 0; //total loaded record group(s)
	var loading  = false; //to prevents multipal ajax loads
	var total_groups = <?php echo $dao->getTotalNumberOfRecordsCount(); ?> //total record group(s)
	
  
    // Load data on first load...
	$.post('controller.php',{'group_no': track_load,'postclients': <?php echo '"'.$client_lst.'"' ?>,'postyear': <?php echo $year ?>,'month':<?php  echo $month ?>}, function(data){
		track_load++;
		$('.user-post-display-body-main-container').append(data);
		$('.animation-load').show();

	});
  	// Scroll to load data
	$(window).scroll('scroll', function() {
    if($(window).scrollTop() >= $('#display_posts').offset().top + $('#display_posts').outerHeight() - window.innerHeight) {
        loadData();
    }
});

	
	
function loadData()
{


	if(track_load <= total_groups && loading==false) //there's more data to load
			{
				loading = true; //prevent further ajax loading
			
				$('.animation-load').html('<img src="loading-animation.gif" width="70px;" height="50px">');
				//load data from the server using a HTTP POST request
				$.post('controller.php',{'group_no': track_load,'postclients': <?php echo '"'.$client_lst.'"' ?>,'postyear': <?php echo $year ?>,'month':<?php  echo $month ?>}, function(data){
									
					$(".user-post-display-body-main-container").append(data); //append received data into the element

					//hide loading image
					$('.animation-load').html('Load More...'); //hide loading image once data is received
					
					track_load++; //loaded group increment
					loading = false; 
				
				}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
					
					alert(thrownError); //alert with HTTP error
					$('.animation-load').hide(); //hide loading image
					loading = false;
				
				});
				
				
			}
			
			if(track_load >= total_groups-1)
			   {
				//reached end of the page yet? disable load button
				
				$('.animation-load').html('No more post');
			   }
			
			
}	
	
	$(".animation-load").click(function (e) { //user clicks on button
	 loadData();
		
});



});
</script>
</head>
<body style="margin:0px auto;width:700px;">

<div id="display_posts" class="user-post-display-body-main-container" style="margin:0px auto;width:700px;">

<?php   echo $dao->getData(0,""); ?>
</div>
<div class="animation-load" > Load More... </div>
</body>
</html>
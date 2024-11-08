<?php 
$sales_rep_login = "no"; $repchk=0; 
$repchk_str = "";
?>
<!DOCTYPE HTML>
<html>
    <head>
	<title>Boomerang by UsedCardboardBoxes</title>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel='stylesheet' type='text/css' href='assets/css/new_header-dashboard.css'>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var timerStart = Date.now();
 
// Collapse navbar when clicking outside
$(document).click(function(event) {
            var clickover = $(event.target);
            var _opened = $("#navbarNavTop").hasClass("show");
            if (_opened === true && !clickover.closest('.navbar-collapse').length) {
                $("button.navbar-toggler").click();
            }
        });
    </script>
	
	<!-- TOOLTIP STYLE START -->
	<link rel="stylesheet" type="text/css" href="css/tooltip_style.css" /> 
	<!-- TOOLTIP STYLE END -->
	<? /*if (!isset($_REQUEST["hd_chgpwd"])) { ?>
	<script language="JavaScript" SRC="inc/NewCalendarPopup.js"></script>
	<script language="JavaScript" SRC="inc/general.js"></script>
	<script language="JavaScript">document.write(getCalendarStyles());</script>
	<script language="JavaScript" >
		var cal1xx = new CalendarPopup("listdiv");
		cal1xx.showNavigationDropdowns();
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
	</script>
	<? } */?>

    </head>
    <body>
    <nav class="nav-top-1 bg-light px-3">
    <div class="d-flex">
    <div class="d-flex align-items-center">
    <!-- Navbar toggler -->
    <button class="mr-4 navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNavTop" aria-controls="navbarNavTop" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
    </button>
    
  
    <a class="" href="home.php"><img src="images/ucb-logo.jpg" /></a>

    </div>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse collapse-custom" id="navbarNavTop">
    <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="home.php" onclick="show_loading()">Home</a>
        </li>
        <!--<li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=tutorials<?php echo $repchk_str ?>" onclick="show_loading()">Tutorials</a>
        </li> -->
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=why_boomerang<?php echo $repchk_str ?>" onclick="show_loading()">Why Boomerang?</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=feedback<?php echo $repchk_str ?>" onclick="show_loading()">Feedback</a>
        </li>
      </ul>
    </div>
    </div>

    <div class="d-flex align-items-center">
		
	<?php if ($sales_rep_login == "no") { ?>  
      <div class="d-flex align-items-center">
          <?php 
              if(isset($_COOKIE['loginid']) && $_COOKIE['loginid']!= "" ){ ?>
                    <?php 
                    db();
                    $select_user = db_query("SELECT user_name, user_last_name FROM boomerang_usermaster WHERE loginid = '".$_COOKIE['loginid']."'");
                    if(tep_db_num_rows($select_user)>0){
                      $user = array_shift($select_user);
                      echo '<a href="user_profile.php" class="company_text_header">'.$user['user_name'] . ' ' . $user['user_last_name'] .'</a>';
                    }
                  }
                  else{
                    echo '<a class="btn btn-topbar" href="index.php">Login</a>';
                  }
                ?>
        </div>
		
        <?php } ?>
        </div>
  </nav>
  <div class="nav-top-2">
    <p class="pl-5 mb-0">
    Shop B2B:
    <!-- <a class="gaylord_link"  href="client_dashboard_new.php?show=inventory<?php //echo $repchk_str ?>" onclick="show_loading()">Browse Gaylords</a> -->
    <a class="gaylord_link"  href="client_dashboard_new1.php?show=inventory&category=gy<?php echo $repchk_str ?>">Browse Gaylords</a>
    <a class="gaylord_link"  href="client_dashboard_new1.php?show=inventory&category=sb<?php echo $repchk_str ?>">Browse Shipping Boxes</a>
    <a class="gaylord_link"  href="client_dashboard_new1.php?show=inventory&category=pl<?php echo $repchk_str ?>">Browse Pallets </a>
    <a class="gaylord_link"  href="client_dashboard_new1.php?show=inventory&category=ss<?php echo $repchk_str ?>">Browse Supersack</a>
  </p>
  </div>

  <div class="nav-top-3 text-center">
    <p class="px-5 mb-0">
    Welcome to the <span class="bold-text">early access, invite only prototype</span> of <span class="bold-text">Boomerang by UsedCardboardBoxes.</span>
    <a class="early_access_link"  href="client_dashboard_new.php?show=prototype" onclick="show_loading()">Learn More</a>
  </p>
  </div>


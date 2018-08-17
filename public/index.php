<?php require_once('../private/initialize.php'); ?>

<?php

if(isset($_GET['ip']) && isset($_GET['local']) ) {
  $ipId = $_GET['ip'];
  $locationId = $_GET['local'];
  $location = find_location_by_id($locationId);
  if(!$location) {
    redirect_to(url_for('/index.php'));
  }
  $ip = find_ip_by_id($ipId, $location);
  if(!$ip) {
    redirect_to(url_for('/index.php'));
  }

} elseif(isset($_GET['local'])) {
  $locationId = $_GET['local'];
  $location = find_location_by_id($locationId);
  if(!$location) {
    redirect_to(url_for('/index.php'));
  }
  $ip_set = find_ips_by_location($location);
  $ip = mysqli_fetch_assoc($ip_set); // first page
  mysqli_free_result($ip_set);
  if(!$ip) {
    redirect_to(url_for('/index.php'));
  }
  $ip_id = $ip['ip_address'];
} else {
  // nothing selected; show the homepage
}

?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <?php include(SHARED_PATH . '/public_navigation.php'); ?>


</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>

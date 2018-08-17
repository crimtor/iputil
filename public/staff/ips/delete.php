<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['ip']) || !isset($_GET['local']) ) {
  redirect_to(url_for('/staff/locations/index.php'));
}
$ipId = $_GET['ip'] ?? '1'; // PHP > 7.0
$locationId = $_GET['local'] ?? '1';

$location = find_location_by_id($locationId);
$ip = find_ip_by_id($ipId, $location);

if(is_post_request()) {

  $result = delete_ip($ip['ip_address'], $location);
  $_SESSION['message'] = 'The IP was deleted successfully.';
  redirect_to(url_for('/staff/locations/show.php?id=' . h(u($ip['location_id']))));

}

?>

<?php $page_title = 'Delete IP Address'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/locations/show.php?id=' . h(u($ip['location_id']))); ?>">&laquo; Back to Location Page</a>

  <div class="page delete">
    <h1>Delete IP Address</h1>
    <p>Are you sure you want to delete this ip address?</p>
    <p class="item"><?php echo h($ip['ip_address']); ?></p>

    <form action="<?php echo url_for('/staff/ips/delete.php?ip=' . h(u($ip['ip_address'])) . '&local=' . h(u($location['id']))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete IP" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>

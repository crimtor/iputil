<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

if(!isset($_GET['ip']) || !isset($_GET['local']) ) {
  redirect_to(url_for('/staff/locations/index.php'));
}

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$ipId = $_GET['ip'] ?? '1'; // PHP > 7.0
$locationId = $_GET['local'] ?? '1';

$location = find_location_by_id($locationId);
$ip = find_ip_by_id($ipId, $location);

?>

<?php $page_title = 'Show IP Address Info'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/locations/show.php?id=' . h(u($location['id']))); ?>">&laquo; Back to Location Page</a>

  <div class="page show">

    <h1>IP Address Info <?php echo h($ip['ip_address']); ?></h1>

    <div class="attributes">
      <dl>
        <dt>IP Address</dt>
        <dd><?php echo h($ip['ip_address']); ?></dd>
      </dl>
      <dl>
        <dt>MAC Address</dt>
        <dd><?php echo h($ip['mac_address']); ?></dd>
      </dl>
      <dl>
        <dt>Description</dt>
        <dd><?php echo h($ip['description']); ?></dd>
      </dl>
      <dl>
        <dt>Availability</dt>
        <dd><?php echo $ip['available'] == 1 ? 'Available' : 'Not available'; ?></dd>
      </dl>
      <dl>
        <dt>Online Status</dt>
        <dd><?php echo $ip['online'] == 1 ? 'true' : 'false'; ?></dd>
      </dl>
    </div>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>

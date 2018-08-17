<?php require_once('../../../private/initialize.php'); ?>

<?php
require_login();

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$id = $_GET['id'] ?? '1'; // PHP > 7.0

$location = find_location_by_id($id);
$ip_set = find_ips_by_location($location);

?>

<?php $page_title = 'Show Location'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/locations/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject show">

    <h1>Location: <?php echo h($location['location_name']); ?></h1>

    <div class="attributes">
      <dl>
        <dt>Location Name</dt>
        <dd><?php echo h($location['location_name']); ?></dd>
      </dl>
      <dl>
        <dt>State</dt>
        <dd><?php echo h($location['state']); ?></dd>
      </dl>
      <dl>
        <dt>City</dt>
        <dd><?php echo h($location['city']); ?></dd>
      </dl>
    </div>

    <hr />

    <div class="pages listing">
      <h2>IPs</h2>

      <table class="list">
        <tr>
          <th>IP Address</th>
          <th>MAC Address</th>
          <th>Description</th>
          <th>Availability</th>
          <th>Online Status</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>

        <?php while($ip = mysqli_fetch_assoc($ip_set)) { ?>
          <tr>
            <td><?php echo h($ip['ip_address']); ?></td>
            <td><?php echo h($ip['mac_address']); ?></td>
            <td><?php echo h($ip['description']); ?></td>
            <td><?php echo $ip['available'] == 1 ? 'Available' : 'Not available'; ?></td>
            <td><?php echo $ip['online'] == 1 ? 'true' : 'false'; ?></td>
            <td><a class="action" href="<?php echo url_for('/staff/ips/show.php?ip=' . h(u($ip['ip_address'])) . '&local=' . h(u($location['id']))); ?>">View</a></td>
            <td><a class="action" href="<?php echo url_for('/staff/ips/edit.php?ip=' . h(u($ip['ip_address'])) . '&local=' . h(u($location['id']))); ?>">Edit</a></td>
            <td><a class="action" href="<?php echo url_for('/staff/ips/delete.php?ip=' . h(u($ip['ip_address'])) . '&local=' . h(u($location['id']))); ?>">Delete</a></td>
          </tr>
        <?php } ?>
      </table>

      <?php mysqli_free_result($ip_set); ?>

    </div>



  </div>

</div>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>

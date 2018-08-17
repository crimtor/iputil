<?php require_once('../../../private/initialize.php'); ?>

<?php

  require_login();

  $location_set = find_all_locations();

?>

<?php $page_title = 'Locations'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="subjects listing">
    <h1>Locations</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/locations/new.php'); ?>">Create New Location</a>
    </div>

  	<table class="list">
  	  <tr>
        <th>ID</th>
        <th>Location</th>
        <th>State</th>
  	    <th>City</th>
        <th>IPs</th>
  	    <th>&nbsp;</th>
  	    <th>&nbsp;</th>
        <th>&nbsp;</th>
  	  </tr>

      <?php while($location = mysqli_fetch_assoc($location_set)) { ?>
        <?php $ip_count = count_ips_by_location($location['location_name']); ?>
        <tr>
          <td><?php echo h($location['id']); ?></td>
          <td><?php echo h($location['location_name']); ?></td>
          <td><?php echo h($location['state']); ?></td>
    	    <td><?php echo h($location['city']); ?></td>
          <td><?php echo $ip_count; ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/locations/show.php?id=' . h(u($location['id']))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/locations/edit.php?id=' . h(u($location['id']))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/locations/delete.php?id=' . h(u($location['id']))); ?>">Delete</a></td>
    	  </tr>
      <?php } ?>
  	</table>

    <?php
      mysqli_free_result($location_set);
    ?>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>

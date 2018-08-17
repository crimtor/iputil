<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['ip'])) {
  redirect_to(url_for('/staff/locations/index.php'));
}
$id = $_GET['ip'];
$locationId = $_GET['local'] ?? '1';

$location = find_location_by_id($locationId);
$ip = find_ip_by_id($id, $location);

if(is_post_request()) {

  $location = [];
  $location['id'] = $id;
  $location['location_name'] = $_POST['location_name'] ?? '';
  $location['state'] = $_POST['state'] ?? '';
  $location['city'] = $_POST['city'] ?? '';

  $ipRange = [];
  $ipRange[0] = $_POST['start_ip'] ?? '';
  $ipRange[1] = $_POST['number_hosts'] ?? '';

  $result = update_location($location, $ipRange);

  if($result === true) {
    $new_location = find_location_by_name($location['location_name']);
    $new_id = $new_location['id'];
    $_SESSION['message'] = 'The location was created successfully.';
    redirect_to(url_for('/staff/locations/show.php?id=' . $new_id));
  } else {
    $errors = $result;
  }

} else {
  // display the blank form
  $location = find_location_by_id($id);

  $ipRange = [];
  $ipRange[0] = '';
  $ipRange[1] = '';
}

?>

<?php $page_title = 'Edit Location'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject edit">
    <h1>Edit Location</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/locations/edit.php?id=' . h(u($id))); ?>" method="post">
      <dl>
        <dt>Location Name - </dt>
        <dd><input type="text" name="location_name" value="<?php echo h($location['location_name']); ?>" /></dd>
      </dl>
      <p>
        Name must be unique.
      </p>
      <dl>
        <dt>State</dt>
      <dd><input type="text" name="state" value="<?php echo h($location['state']); ?>" /></dd>
      </dl>
      <p>
        Use 2 letter abbreviation of State Name
      </p>
      <dl>
        <dt>City</dt>
      <dd><input type="text" name="city" value="<?php echo h($location['city']); ?>" /></dd>
      </dl>
      <dl>
        <dt>Subnet Starting IP</dt>
      <dd><input type="text" name="start_ip" value="<?php echo h($ipRange[0]); ?>" /></dd>
      </dl>
      <p>
        Use standard IPv4 dot notation i.e 192.168.1.1
      </p>
      <dl>
        <dt>How Many Hosts in Subnet</dt>
      <dd><input type="text" name="number_hosts" value="<?php echo h($ipRange[1]); ?>" /></dd>
      </dl>
      <p>
        Standard would be 254 hosts
      </p>
      <div id="operations">
        <input type="submit" value="Edit Location" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>

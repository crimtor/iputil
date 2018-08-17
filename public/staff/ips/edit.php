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

  $ip['description'] = $_POST['description'] ?? '';

  $result = update_ip($ip, $location);

  if($result === true) {
    $_SESSION['message'] = 'The ip was updated successfully.';
    redirect_to(url_for('/staff/ips/show.php?id=' . $id));
  } else {
    $errors = $result;
  }
}else{
  $ip = find_ip_by_id($ipId, $location);
}

?>

<?php $page_title = 'Edit IP Info'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/locations/show.php?id=' . h(u($ip['location_id']))); ?>">&laquo; Back to Locations Page</a>

  <div class="page edit">
    <h1>Edit IP info</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/ips/edit.php?ip=' . h(u($ip['ip_address'])) . '&local=' . h(u($location['id']))); ?>" method="post">
      <dl>
        <dt>Decription</dt>
        <dd><input type="text" name="description" value="<?php echo h($ip['description']); ?>" /></dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Edit IP" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>

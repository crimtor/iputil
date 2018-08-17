<?php
  // Default values to prevent errors
  $ip_id = $ip_id ?? '';
  $location_id = $location_id ?? '';
?>

<navigation>
  <!-- <?php $nav_locations = find_all_locations(); ?> -->
  <ul class="subjects">
    <?php while($nav_location = mysqli_fetch_assoc($nav_locations)) { ?>
      <li class="<?php if($nav_location['id'] == $location_id) { echo 'selected'; } ?>">
        <a href="<?php echo url_for('index.php?local=' . h(u($nav_location['id']))); ?>">
          <?php echo h($nav_location['location_name']); ?>
        </a>

        <?php if($nav_location['id'] == $location_id) { ?>
          <?php $ip_set = find_ips_by_location($nav_location); ?>
          <ul class="pages">
            <?php while($ip = mysqli_fetch_assoc($ip_set)) { ?>
              <li class="<?php if($ip['ip_address'] == $ip_id) { echo 'selected'; } ?>">
                <a href="<?php echo url_for('index.php?ip=' . h(u($ip['ip_address']))); ?>">
                  <?php echo h($ip['ip_address']); ?>
                </a>
              </li>
            <?php } // while $nav_pages ?>
          </ul>
          <?php mysqli_free_result($ip_set); ?>
        <?php } // if($nav_location['id'] == $location_id) ?>

      </li>
    <?php } // while $nav_locations ?>
  </ul>
  <?php mysqli_free_result($nav_locations); ?>
</navigation>

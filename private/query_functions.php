<?php

 // IP Queries
  function find_all_ips($location) {
    global $db;

    $sql = "SELECT * FROM ip_table_for_" . db_escape($db, $location['location_name']) ;
    $sql .= " WHERE location_id='" . db_escape($db, $location['id']) . "' ";
    $sql .= "ORDER BY available ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function validate_ip($ip) {
    $errors = [];

    if(is_blank($ip['description'])) {
      $errors[] = "Decription cannot be blank.";
    } elseif(!has_length($ip['description'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Description must be between 2 and 255 characters.";
    }
    return $errors;
  }

  function find_ip_by_id($id, $location, $options=[]) {
    global $db;

    $visible = $options['online'] ?? false;

    $sql = "SELECT * FROM ip_table_for_" . db_escape($db, $location['location_name']) ;
    $sql .= " WHERE ip_address='" . db_escape($db, $id) . "' ";
    if($visible) {
      $sql .= "AND online = 1";
    }
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $ip = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $ip; // returns an assoc. array
  }

  function find_ips_by_location($location, $options=[]) {
    global $db;

    $visible = $options['online'] ?? false;

    $sql = "SELECT * FROM ip_table_for_" . db_escape($db, $location['location_name']) ;
    $sql .= " WHERE location_id='" . db_escape($db, $location['id']) . "' ";
    if($visible) {
      $sql .= "AND online = 1 ";
    }
    $sql .= "ORDER BY available ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function update_ip($ip, $location) {
    global $db;

    $errors = validate_ip($ip);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE ip_table_for_" . db_escape($db, $location['location_name']) ;
    $sql .= " SET description='" . db_escape($db, $ip['description']) . "' ";
    $sql .= "WHERE ip_address='" . db_escape($db, $ip['ip_address']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function delete_ip($id, $location) {
    global $db;

    $sql = "DELETE FROM ip_table_for_" . db_escape($db, $location['location_name']) ;
    $sql .= " WHERE ip_address='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function count_ips_by_location($location_name, $options=[]) {
    global $db;

    $visible = $options['online'] ?? false;

    $sql = "SELECT COUNT(ip_address) FROM ip_table_for_" . db_escape($db, $location_name) ;
    if($visible) {
      $sql .= " WHERE online = 1 ";
    }
    $sql .= " ORDER BY ip_address ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];
    return $count;
  }

    // Location Queries
      function find_all_locations($options=[]) {
        global $db;

        $incity = $options['city'] ?? null;

        $sql = "SELECT * FROM locations ";
        if($incity) {
          $sql .= "WHERE city='" . db_escape($db, $incity) . "'";
        }
        $sql .= "ORDER BY city ASC";
        //echo $sql;
        $result = mysqli_query($db, $sql);
        confirm_result_set($result);
        return $result;
      }

    //Validate adding a location
    function validate_location($location, $ipRange) {
      $errors = [];

      // menu_name
      if(is_blank($location['location_name'])) {
        $errors[] = "Location's name cannot be blank.";
      } elseif(!has_length($location['location_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
      }

      // State
      if(is_blank($location['state'])) {
        $errors[] = "Location's state cannot be blank.";
      } elseif(!has_length($location['location_name'], ['min' => 2, 'max' => 15])) {
        $errors[] = "State must be less than 15 characters.";
      }

      // State
      if(is_blank($location['city'])) {
        $errors[] = "Location's city cannot be blank.";
      } elseif(!has_length($location['city'], ['min' => 2, 'max' => 50])) {
        $errors[] = "City must be between 2 and 50 characters.";
      }

      // ipRange
      // Make sure we are working with an integer
      $ip_int = (int) $ipRange[1];
      if($ip_int <= 0) {
        $errors[] = "Subnet IP range must be greater than zero.";
      }
      if($ip_int > 255) {
        $errors[] = "Subnet IP range must be less than 255.";
      }

      return $errors;
    }


    //find location with id
      function find_location_by_id($id, $options=[]) {
        global $db;

        $incity = $options['city'] ?? false;

        $sql = "SELECT * FROM locations ";
        $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
        if($incity) {
          $sql .= "AND city=''". db_escape($db, $incity) . "'";
        }
        // echo $sql;
        $result = mysqli_query($db, $sql);
        confirm_result_set($result);
        $mysite = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $mysite; // returns an assoc. array
      }

      //find location with name
        function find_location_by_name($name) {
          global $db;

          $incity = $options['city'] ?? false;

          $sql = "SELECT * FROM locations ";
          $sql .= "WHERE location_name='" . db_escape($db, $name) . "' ";
          // echo $sql;
          $result = mysqli_query($db, $sql);
          confirm_result_set($result);
          $mysite = mysqli_fetch_assoc($result);
          mysqli_free_result($result);
          return $mysite; // returns an assoc. array
        }

    // Inserting a location into the database
    // todo: Make it create a new table for the ips to go into.
      function insert_location($location, $ipRange) {
        global $db;

        $errors = validate_location($location, $ipRange);
        if(!empty($errors)) {
          return $errors;
        }

        $sql = "INSERT INTO locations ";
        $sql .= "(location_name, state, city) ";
        $sql .= "VALUES (";
        $sql .= "'" . db_escape($db, $location['location_name']) . "',";
        $sql .= "'" . db_escape($db, $location['state']) . "',";
        $sql .= "'" . db_escape($db, $location['city']) . "'";
        $sql .= ")";
        $result = mysqli_query($db, $sql);
        // For INSERT statements, $result is true/false
        if($result) {
          $delsql = "DROP TABLE IF EXISTS ip_table_for_" . db_escape($db, $location['location_name']) . " ";
          $noResult = mysqli_query($db, $delsql);
          $sql2 = "CREATE TABLE ip_table_for_" . db_escape($db, $location['location_name']) . " ";
          $sql2 .= "(ip_address varchar(16) NOT NULL, ";
          $sql2 .= "mac_address varchar(55) DEFAULT NULL, ";
          $sql2 .= "description varchar(255) DEFAULT NULL, ";
          $sql2 .= "location_id int(11) NOT NULL, ";
          $sql2 .= "available tinyint(1) DEFAULT NULL, ";
          $sql2 .= "online tinyint(1) DEFAULT NULL, ";
          $sql2 .= "PRIMARY KEY (ip_address), ";
          $sql2 .= "KEY fk_location_id (location_id) )";
          $result2 = mysqli_query($db, $sql2);

            if($result2) {
              $locationData = find_location_by_name($location['location_name']);
              for ($x = 1; $x < $ipRange[1]; $x++) {
                $sql3 = "INSERT INTO ip_table_for_" . db_escape($db, $location['location_name']) . " ";
                $sql3 .= "VALUES ('192.168.1.{$x}', null, null, " .
                db_escape($db, $locationData['id']) . ", 1, 0)";
                $result3 = mysqli_query($db, $sql3);
                if($result3){

                }else {
                  // INSERT failed
                  echo mysqli_error($db);
                  db_disconnect($db);
                  exit;
                }
              }
              return true;
          } else {
            // INSERT failed
            echo mysqli_error($db);
            db_disconnect($db);
            exit;
          }
          return true;
      }else {
          // INSERT failed
          echo mysqli_error($db);
          db_disconnect($db);
          exit;
       }

       return true;
      }

  // Update location
  // todo: decide what should happen to corrosponding table it creates
    function update_location($location, $ipRange) {
      global $db;

      $errors = validate_location($location, $ipRange);
      if(!empty($errors)) {
        return $errors;
      }


      $old_location = find_location_by_id($location['id']);

      if($location['location_name'] != $old_location['location_name']) {
      $delsql = "DROP TABLE IF EXISTS ip_table_for_" . db_escape($db, $old_location['location_name']) . " ";
      $noResult = mysqli_query($db, $delsql);
    }

    $sql = "UPDATE locations SET ";
    $sql .= "location_name='" . db_escape($db, $location['location_name']) . "', ";
    $sql .= "state='" . db_escape($db, $location['state']) . "', ";
    $sql .= "city='" . db_escape($db, $location['city']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $location['id']) . "' ";
    $sql .= "LIMIT 1";



      $result = mysqli_query($db, $sql);
      // For UPDATE statements, $result is true/false
      if($result) {
        $delsql = "DROP TABLE IF EXISTS ip_table_for_" . db_escape($db, $location['location_name']) . " ";
        $noResult = mysqli_query($db, $delsql);
        $sql2 = "CREATE TABLE ip_table_for_" . db_escape($db, $location['location_name']) . " ";
        $sql2 .= "(ip_address varchar(16) NOT NULL, ";
        $sql2 .= "mac_address varchar(55) DEFAULT NULL, ";
        $sql2 .= "description varchar(255) DEFAULT NULL, ";
        $sql2 .= "location_id int(11) NOT NULL, ";
        $sql2 .= "available tinyint(1) DEFAULT NULL, ";
        $sql2 .= "online tinyint(1) DEFAULT NULL, ";
        $sql2 .= "PRIMARY KEY (ip_address), ";
        $sql2 .= "KEY fk_location_id (location_id) )";
        $result2 = mysqli_query($db, $sql2);

          if($result2) {
            $locationData = find_location_by_name($location['location_name']);
            for ($x = 1; $x < $ipRange[1]; $x++) {
              $sql3 = "INSERT INTO ip_table_for_" . db_escape($db, $location['location_name']) . " ";
              $sql3 .= "VALUES ('192.168.1.{$x}', null, null, " .
              db_escape($db, $locationData['id']) . ", 1, 0)";
              $result3 = mysqli_query($db, $sql3);
              if($result3){

              }else {
                // INSERT failed
                echo mysqli_error($db);
                db_disconnect($db);
                exit;
              }
            }
            return true;
        } else {
          // INSERT failed
          echo mysqli_error($db);
          db_disconnect($db);
          exit;
        }
        return true;
    }else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
      }
    }


    function delete_location($id) {
      global $db;

      $old_location = find_location_by_id($id);

      $delsql = "DROP TABLE IF EXISTS ip_table_for_" . db_escape($db, $old_location['location_name']) . " ";
      $noResult = mysqli_query($db, $delsql);

      $sql = "DELETE FROM locations ";
      $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
      $sql .= "LIMIT 1";
      $result = mysqli_query($db, $sql);

      // For DELETE statements, $result is true/false
      if($result) {
        return true;
      } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
      }

    }

    //Ping a specific IP Address and recieve whether it responded or not.
    function ping($ip) {
        exec ("ping -l 4 -n 2 -i 1 " . $ip, $output);

        $status = true;
        foreach ($output as $v) {
            if (strpos($v, '100% loss') !== false) {
                $status = false;
                break;
            }
        }

        return $status;
    }

    //Ping an entire range of IP Addresses and get back an array of
    // objects with key value pairs that corrospond to database values
    function ping_range($start, $end) {
      $start = ip2long($start);
      $end = ip2long($end);
      $ipRange = array_map('long2ip', range($start, $end));
      $networkMap = array();
      $isIpUp = [];
      for ($x = 0; $x< count($ipRange); $x++){
          $isIpUp[$x] = ping($ipRange[$x]);
      }
      for ($y = 0; $y < count($ipRange); $y++) {
        $networkMap[$y] = (object) array('ip_address' => $ipRange[$y], 'available' => $isIpUp[$y] );
      }
      return $networkMap;
    }



  // Admins

  // Find all admins, ordered last_name, first_name
  function find_all_admins() {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "ORDER BY last_name ASC, first_name ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function find_admin_by_id($id) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
  }

  function find_admin_by_username($username) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result); // find first
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
  }

  function validate_admin($admin, $options=[]) {

    $password_required = $options['password_required'] ?? true;

    if(is_blank($admin['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($admin['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    }

    if(is_blank($admin['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($admin['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    }

    if(is_blank($admin['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif (!has_length($admin['email'], array('max' => 255))) {
      $errors[] = "Last name must be less than 255 characters.";
    } elseif (!has_valid_email_format($admin['email'])) {
      $errors[] = "Email must be a valid format.";
    }

    if(is_blank($admin['username'])) {
      $errors[] = "Username cannot be blank.";
    } elseif (!has_length($admin['username'], array('min' => 8, 'max' => 255))) {
      $errors[] = "Username must be between 8 and 255 characters.";
    } elseif (!has_unique_username($admin['username'], $admin['id'] ?? 0)) {
      $errors[] = "Username not allowed. Try another.";
    }

    if($password_required) {
      if(is_blank($admin['password'])) {
        $errors[] = "Password cannot be blank.";
      } elseif (!has_length($admin['password'], array('min' => 8))) {
        $errors[] = "Password must contain 8 or more characters";
      } elseif (!preg_match('/[A-Z]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 uppercase letter";
      } elseif (!preg_match('/[a-z]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 lowercase letter";
      } elseif (!preg_match('/[0-9]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 number";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
        $errors[] = "Password must contain at least 1 symbol";
      }

      if(is_blank($admin['confirm_password'])) {
        $errors[] = "Confirm password cannot be blank.";
      } elseif ($admin['password'] !== $admin['confirm_password']) {
        $errors[] = "Password and confirm password must match.";
      }
    }

    return $errors;
  }

  function insert_admin($admin) {
    global $db;

    $errors = validate_admin($admin);
    if (!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins ";
    $sql .= "(first_name, last_name, email, username, hashed_password) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $admin['first_name']) . "',";
    $sql .= "'" . db_escape($db, $admin['last_name']) . "',";
    $sql .= "'" . db_escape($db, $admin['email']) . "',";
    $sql .= "'" . db_escape($db, $admin['username']) . "',";
    $sql .= "'" . db_escape($db, $hashed_password) . "'";
    $sql .= ")";
    $result = mysqli_query($db, $sql);

    // For INSERT statements, $result is true/false
    if($result) {
      return true;
    } else {
      // INSERT failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function update_admin($admin) {
    global $db;

    $password_sent = !is_blank($admin['password']);

    $errors = validate_admin($admin, ['password_required' => $password_sent]);
    if (!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

    $sql = "UPDATE admins SET ";
    $sql .= "first_name='" . db_escape($db, $admin['first_name']) . "', ";
    $sql .= "last_name='" . db_escape($db, $admin['last_name']) . "', ";
    $sql .= "email='" . db_escape($db, $admin['email']) . "', ";
    if($password_sent) {
      $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "username='" . db_escape($db, $admin['username']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);

    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_admin($admin) {
    global $db;

    $sql = "DELETE FROM admins ";
    $sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
    $sql .= "LIMIT 1;";
    $result = mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // DELETE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }



?>

<?php





// Database wrappers

function DB($sql, $param=[]) {
  global $db;
  if ($sth = $db->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])) {
    $sth->execute($param);
    $rs = $sth->fetchAll();
    // remove numeric indices
    foreach ($rs as $k_ => $v_) {
      foreach ($v_ as $k => $v) {
        if (is_int($k)) {
          unset($rs[$k_][$k]);
        }
      }
    }
    return $rs;
  } else {
    exit("Database Error: ".$db->error);
  }
}

// $rs = DB("SELECT * FROM common_member WHERE username= :username OR uid= :uid", [":username" => "尖头勺子", ":uid" => 2]);
// print_r($rs);


// Output wrapper

function printv($arr, $ret=false) {
  $buf = "";
  if (is_array($arr)) {
    $buf .= "<pre>";
    $buf .= print_r($arr, true);
    $buf .= "</pre>";
    if ($ret) {
      return $buf;
    } else {
      echo $buf;
    }
  } else {
    return false;
  }
}

function template($file, ...$extrafiles) {
  // render the html template
  // this function will terminate the php execution
  global $title;
  global $body;
  global $lang;
  global $_GET;
  global $redirect;
  global $_starttime;
  $_endtime = microtime();
  $_runtime = $_endtime - $_starttime;
  include_once(ROOT."templates/common_header_html.htm");
  include_once(ROOT."templates/common_header_visual.htm");
  include_once(ROOT."templates/".$file.".htm");
  foreach ($extrafiles as $k => $v) {
    include_once(ROOT."templates/".$v.".htm");  
  }
  include_once(ROOT."templates/common_footer_visual.htm");
  include_once(ROOT."templates/common_footer_html.htm");

  exit();
}





function randomStr($length) {
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz';
    $str = '';
    $max = strlen($keyspace) - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

function usernameMarkCensor($string, $sensorlist) {
  foreach ($sensorlist as $k => $v) {
    if (strpos($string, $v) !== false) {
      return $v;
    }
  }
  return false;
}

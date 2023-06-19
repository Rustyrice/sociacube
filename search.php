<?php

include("classes/autoload.php");

$login = new Login();
$_SESSION['mybook_userid'] = isset($_SESSION['mybook_userid']) ? $_SESSION['mybook_userid'] : 0;

$user_data = $login->check_login($_SESSION['mybook_userid'], false);

$USER = $user_data;

if (isset($URL[1])) {

  $profile = new Profile();
  $profile_data = $profile->get_profile($URL[1]);

  if (is_array($profile_data)) {
    $user_data = $profile_data[0];
  }
}

if (isset($_GET['find'])) {

  $find = addslashes(str_replace(" ", "|", $_GET['find']));
  //$find = addslashes($_GET['find']);

  $sql = "select * from users where first_name rlike '$find' || last_name rlike '$find' order by rand() limit 15";
  $DB = new Database();
  $results = $DB->read($sql);
}

$pageTitle = "Search | Sociacube";

?>

<?php include("header.php"); ?>

<!--cover area-->
<div style="width: 800px;margin: auto;min-height: 400px;">


  <!--below cover area-->
  <div style="display:flex;">

    <!--posts area-->
    <div style="min-height: 400px;flex: 2.5;padding: 20px;padding-right: 0px;">

      <div style="padding: 10px;background-color: white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-radius: 10px;">

        <?php

        $User = new User();
        $image_class = new Image();

        //if (!empty($_GET['find'])) {

        if (is_array($results)) {

          foreach ($results as $row) {

            $FRIEND_ROW = $User->get_user($row['userid']);

            if ($row['type'] == "profile") {
              include("user.php");
            } else
                if ($row['type'] == "group") {
              include("group.inc.php");
            }
          }
        } else {

          echo "<br><div style='text-align:center;font-size:17px;'>We didn't find any results</div><br>";
          echo "<div style='text-align:center;font-size:12px;width:300px;margin:auto;color:gray;'>Make sure that everything is spelt correctly or try different keywords.</div><br>";
        }
        //}

        ?>

        <br style="clear:both;">
      </div>

    </div>

  </div>

  </body>

  </html>
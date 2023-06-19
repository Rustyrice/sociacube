<style media="screen">
  #search_box {

    width: 400px;
    height: 20px;
    border-radius: 20px;
    border: none;
    padding: 4px;
    font-size: 14px;
    background-image: url(../../images/search.png);
    background-size: 20px;
    background-repeat: no-repeat;
    background-position: 375px;
    outline: none;
  }
</style>

<div style="min-height:400px;width:100%;background-color:white;text-align:center;position: relative;top:20px;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
  <div style="padding:20px;max-width:450px;display:inline-block;">
    <form method="post" enctype="multipart/form-data">

      <?php

      $settings_class = new Settings();

      $settings = $settings_class->get_settings($group_data['userid']);

      if (is_array($settings)) {

        echo "<p style='font-size:11px;color:grey;float:left;margin:-20px;padding:30px;'>Group Name</p>";
        echo "<input maxlength='20' type='text' id='textbox' name='first_name' value='" . htmlspecialchars($settings['first_name']) . "' placeholder='Group Name' required/>";

        echo "<p style='font-size:11px;color:grey;float:left;margin:-20px;padding:30px;'>Group Type</p>";
        echo "<select id='textbox' name='group_type' style='height:30px;width:102%;'>
              <option>" . htmlspecialchars($settings['group_type']) . "</option>
              <option>public</option>
              <option>private</option>
            </select>";

        echo "<br>";
        echo "<br>About the group:<br>
              <textarea maxlength='50' id='textbox' style='height:200px;' name='about' placeholder='Tell us a bit about your group...'>" . htmlspecialchars($settings['about']) . "</textarea>
            ";

        echo '<input id="post_button" type="submit" value="Save">';
      }

      ?>
    </form>
  </div>
</div>
<br><br>

<script>
  function maxLength(el) {
    if (!('maxLength' in el)) {
      var max = el.attributes.maxLength.value;
      el.onkeypress = function() {
        if (this.value.length >= max) return false;
      };
    }
  }

  maxLength(document.getElementById("textbox"));
</script>
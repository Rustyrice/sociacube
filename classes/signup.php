<?php

class Signup
{

	private $error = "";

	public function evaluate($data)
	{

		foreach ($data as $key => $value) {
			# code...

			if (empty($value)) {
				$this->error = $this->error . $key . " is empty<br>";
			}

			if ($key == "email") {
				if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {

					$this->error = $this->error . "Please enter a valid email<br>";
				}
			}

			if ($key == "first_name") {
				if (!preg_match("/^[a-zA-Z-' ]*$/", $value)) {

					$this->error = $this->error . "First name can only contain letters<br>";
				}

				if (strlen($value) > 15) {
					$this->error = $this->error . "First name is too long. Please choose a shorter name<br>";
				}
			}

			if ($key == "last_name") {
				if (!preg_match("/^[a-zA-Z-' ]*$/", $value)) {

					$this->error = $this->error . "Last name can only contain letters<br>";
				}

				if (strlen($value) > 15) {
					$this->error = $this->error . "Last name is too long. Please choose a shorter name<br>";
				}
			}

			if ($key == "password") {

				$password = $data['password'];
				$password2 = $data['password2'];
				if ($password != $password2) {

					$this->error = $this->error . "Passwords do not match<br>";
				}

				if (strstr($value, " ")) {

					$this->error = $this->error . "Password can't have spaces<br>";
				}

				if (strlen($value) < 8) {

					$this->error = $this->error . "Password must be at least 8 characters long<br>";
				}
			}
		}

		$DB = new Database();

		//check tag name
		$data['tag_name'] = strtolower($data['first_name'] . $data['last_name']);

		$sql = "select id from users where tag_name = '$data[tag_name]' limit 1";
		$check = $DB->read($sql);
		while (is_array($check)) {

			$data['tag_name'] = strtolower($data['first_name'] . $data['last_name']) . rand(0, 9999);
			$sql = "select id from users where tag_name = '$data[tag_name]' limit 1";
			$check = $DB->read($sql);
		}

		$data['userid'] = $this->create_userid();

		//check userid
		$sql = "select id from users where userid = '$data[userid]' limit 1";
		$check = $DB->read($sql);
		while (is_array($check)) {

			$data['userid'] = $this->create_userid();
			$sql = "select id from users where userid = '$data[userid]' limit 1";
			$check = $DB->read($sql);
		}

		//check email
		$sql = "select id from users where email = '$data[email]' limit 1";
		$check = $DB->read($sql);
		if (is_array($check)) {

			$this->error = $this->error . "The email address you entered already exists<br>";
		}

		if ($this->error == "") {

			//no error
			$this->create_user($data);
		} else {
			return $this->error;
		}
	}

	public function create_user($data)
	{

		$first_name = test_input(ucfirst($data['first_name']));
		$last_name = test_input(ucfirst($data['last_name']));
		$gender = $data['gender'];
		$email = htmlspecialchars($data['email']);
		$password = htmlspecialchars($data['password']);
		$userid = $data['userid'];
		$tag_name = $data['tag_name'];
		$date = date("Y-m-d H:i:s");
		$type = "profile";

		$password = hash("sha1", $password);

		//create these
		$url_address = strtolower($first_name) . "." . strtolower($last_name);

		$query = "insert into users
		(type,userid,first_name,last_name,gender,email,password,url_address,tag_name,date)
		values
		('$type','$userid','$first_name','$last_name','$gender','$email','$password','$url_address','$tag_name','$date')";


		$DB = new Database();
		$DB->save($query);
	}

	private function create_userid()
	{

		$length = rand(4, 19);
		$number = "";
		for ($i = 0; $i < $length; $i++) {
			# code...
			$new_rand = rand(0, 9);

			$number = $number . $new_rand;
		}

		return $number;
	}
}

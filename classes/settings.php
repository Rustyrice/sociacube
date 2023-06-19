<?php

class Settings
{

	private $error = "";

	public function evaluate($data)
	{

		foreach ($data as $key => $value) {

			if ($key == "first_name") {
				if (!preg_match("/^[a-zA-Z-' ]*$/", $value)) {

					$this->error = $this->error . "Your first name can only contain letters<br>";
				}

				if (strlen($value) > 15) {
					$this->error = $this->error . "Your first name is too long. Please choose a shorter name<br>";
				}
			}

			if ($key == "last_name") {
				if (!preg_match("/^[a-zA-Z-' ]*$/", $value)) {

					$this->error = $this->error . "Your last name can only contain letters<br>";
				}

				if (strlen($value) > 15) {
					$this->error = $this->error . "Your last name is too long. Please choose a shorter name<br>";
				}
			}

			if ($key == "password") {

				$password = $data['password'];
				$password2 = $data['password2'];

				if ($password != $password2) {

					$this->error = $this->error . "Your passwords do not match<br>";
				}

				if (strstr($value, " ")) {

					$this->error = $this->error . "Your password can't have spaces<br>";
				}

				if (strlen($value) < 8) {

					$this->error = $this->error . "Your password must be at least 8 characters long<br>";
				}
			}
		}

		if (!$this->error == "") {

			return $this->error;
		}
	}

	public function get_settings($id)
	{
		$DB = new Database();
		$sql = "select * from users where userid = '$id' limit 1";
		$row = $DB->read($sql);

		if (is_array($row)) {

			return $row[0];
		}
	}

	public function save_settings($data, $id)
	{

		$DB = new Database();

		$password = isset($data['password']) ? $data['password'] : "";

		if (strlen($password) < 30 && isset($data['password2'])) {

			if ($data['password'] == $data['password2']) {
				$data['password'] = hash("sha1", $password);
			} else {

				unset($data['password']);
			}
		}

		unset($data['password2']);

		$sql = "update users set ";
		foreach ($data as $key => $value) {
			# code...

			$sql .= $key . "='" . $value . "',";
		}

		$sql = trim($sql, ",");
		$sql .= " where userid = '$id' limit 1";
		$DB->save($sql);
	}
}

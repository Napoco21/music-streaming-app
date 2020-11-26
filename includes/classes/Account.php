<?php 

	class Account
	{
		private $errorArray;
		private $conn;

		public function __construct($conn)
		{
			$this->conn = $conn;
			$this->errorArray = array();
		}

		public function login($uname, $pwd)
		{
			$pwd = md5($pwd);    // hashing
			$query = mysqli_query($this->conn, "SELECT * FROM users where username='$uname' AND password='$pwd' LIMIT 1");
			// echo "SELECT * FROM users where username='$uname' AND password='$pwd'";
			if(mysqli_num_rows($query) > 0)     // check if the user exists
			{
				return true;
			}
			else
			{
				array_push($this->errorArray, Constants::$loginFailed);
				return false;
			}
		}

		public function register($uname, $fname, $lname, $email, $email2, $pwd, $pwd2)
		{
		    $this->validateUsername($uname);
		    $this->validateFirstname($fname);
		    $this->validateLastname($lname);
		    $this->validateEmail($email, $email2);
		    $this->validatePassword($pwd, $pwd2);

		    if (empty($this->errorArray) == true)
		    {
		    	// Insert into database
		    	return $this->insertUserInfo($uname, $fname, $lname, $email, $pwd);
		    }
		    else
		    {
		    	return false;
		    }
		}

		public function getError($error)
		{
			if(!(in_array($error, $this->errorArray)))  // checks if error is in error array
			{
				$error="";                 
			}
			return "<span class='errorMessage'>$error</span>";
		}

		private function insertUserInfo($uname, $fname, $lname, $email, $pwd)
		{
			$hashed_password = md5($pwd);   /// hashing password using MD5
			$profile_picture = "assets/images/profilePics/profile-126.png";
			$date = date("Y-m-d");

			$result = mysqli_query($this->conn, "INSERT INTO users(username, firstName, lastName, email, password, registerDate, profilePicture) VALUES ('$uname', '$fname', '$lname', '$email', '$hashed_password', '$date', '$profile_picture')") or die("Bad query");    // are inserted according to their position in the db

			return $result;   // mysqli_query returns true if insertion was successful and false otherwise
		}


			# validator functions
		private function validateUsername($uname)
		{
			if(strlen($uname) > 20 || strlen($uname) < 4)
			{
				array_push($this->errorArray, Constants::$username_length_error);    // add error to the error array
				return;
			}
			// checks if username exists

			$usernameCheckQuery = mysqli_query($this->conn, "SELECT username FROM users WHERE username='$uname'");
			if(mysqli_num_rows($usernameCheckQuery) != 0)  // checks if there are rows in the db for that username
			{
				array_push($this->errorArray, Constants::$usernameAlreadyExists);
				return;
			}
		}
		private function validateFirstname($fname)
		{
			if(strlen($fname) > 20 || strlen($fname) < 2)
			{
				array_push($this->errorArray, Constants::$firstname_length_error); 
				return;
			}
		}
		private function validateLastname($lname)
		{
			if(strlen($lname) > 20 || strlen($lname) < 2)
			{
				array_push($this->errorArray, Constants::$lastname_length_error); 
				return;
			}
		}
		private function validateEmail($email, $email2)
		{
			if($email != $email2)
			{
				array_push($this->errorArray, Constants::$email_match_error); 
				return;
			}
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				array_push($this->errorArray, Constants::$email_invalid_error); 
				return;
			}
			$emailCheckQuery = mysqli_query($this->conn, "SELECT email FROM users WHERE email='$email'");
			if(mysqli_num_rows($emailCheckQuery) != 0)  // checks if there are rows in the db for that email
			{
				array_push($this->errorArray, Constants::$emailAlreadyExists);
				return;
			}
		}
		private function validatePassword($pwd, $pwd2)
		{
			if($pwd != $pwd2)
			{
				array_push($this->errorArray, Constants::$password_match_error); 
				return;
			}

			if(strlen($pwd) > 30 || strlen($pwd) < 8)
			{
				array_push($this->errorArray, Constants::$password_length_error); 
				return;
			}

			// TODO: add restrictions: e.g: must have at least one symbol
		}


	}



?>
<?php 

class Account()
{
	private errorArray;

	public function __construct()
	{
		this->errorArray = array();
	}
	public function register($uname, $fname, $lname, $email, $email2, $pwd, $pwd2)
	{
	    this->validateUsername($uname);
	    this->validateFirstname($fname);
	    this->validateLastname($lname);
	    this->validateEmail($email, $email2);
	    this->validatePassword($pwd, $pwd2);

	    if (empty($this->errorArray) == true)
	    {
	    	// Insert into database
	    	return true;
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function getError($error)
	{
		if(!(in_array($error, this->errorArray)))  // checks if error is in error array
		{
			$error="";                 
		}
		return "<span class='errorMessage'>$error</span>";
	}


		# validator functions
	private function validateUsername($uname)
	{
		if(str_len($uname) > 20 || str_len($uname) < 4)
		{
			array_push($this->errorArray, Constants::$username_length_error);    // add error to the error array
			return;
		}
		// checks if username exists

	}
	private function validateFirstname($fname)
	{
		if(str_len($fname) > 20 || str_len($fname) < 2)
		{
			array_push($this->errorArray, Constants::$firstname_length_error); 
			return;
		}
	}
	private function validateLastname($lname)
	{
		if(str_len($lname) > 20 || str_len($lname) < 2)
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
	}
	private function validatePassword($pwd, $pwd2)
	{
		if($pwd != $pwd2)
		{
			array_push($this->errorArray, Constants::$password_match_error); 
			return;
		}

		if(str_len($pwd) > 30 || str_len($pwd) < 8)
		{
			array_push($this->errorArray, Constants::$password_length_error); 
			return;
		}

		// TODO: add restrictions: e.g: must have at least one symbol
	}


}



?>
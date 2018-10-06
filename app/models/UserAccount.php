<?php
	class UserAccount
	{
		private $id;
		private $email;
		private $firstName;
		private $lastName;
		private $links;

		public function __construct($id, $email, $firstName, $lastName) {
			$this->id = $id;
			$this->email = $email;
			$this->firstName = $firstName;
			$this->lastName = $lastName;
		}
		
		public function getId() {
			return $this->id;
		}
	}
?>
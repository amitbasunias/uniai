<?php
require_once(dirname(__FILE__) . '/users.php');
    
class PageProtection {
	var $error = '';
	var $logged = false;
	var $isAdmin = false;
	
	public function  __construct () {
		if (session_status() == PHP_SESSION_NONE) { session_start(); error_reporting(0);}
	} //end __construct
	
	public function secure ($value) {
		if ($value) {
			$value = htmlspecialchars(trim($value));
		} else {
			$value = "";	
		}
		
		return $value;
	}
	
	public function enable ($role = 'user') {
		global $passwords, $url;
	
		$login_user = $this->secure($_SESSION["user"]);
		$login_pass = $this->secure($_SESSION["hash"]);
				
		if (isset($login_user)) {
			if (isset($passwords[$login_user])) {
				$data = $passwords[$login_user];
				if ($data['pass'] == $login_pass) {
					if (($data['role'] == $role) OR ($data['role'] == 'admin')) {
					$this->logged = true;
					if ($data['role'] == 'admin') {
						$this->isAdmin = true;
					} //check is admin
					} else {
						
						$this->error = 'You are not an ADMIN!';
						$this->logged = false;
					}//check role access
				} //check password
			} //check isset user
		} //check user session 
	
	if (!$this->logged) {
		$url = $this->secure($_SERVER['REQUEST_URI']);
		$login_template = file_get_contents(dirname(__FILE__) . '/login.html' );
		if ($this->error != "") {
			$login_template = str_replace('{$error}',  '<div class="text-danger">'.$this->error.'</div>',  $login_template);
		} else {
			$login_template = str_replace('{$error}',  '',  $login_template);
		}
		$login_template = str_replace('{$url}', $url,  $login_template);
		die($login_template);
	} //end view login form
	
	} //end protectEnable
	
	public function login ($user, $passwd) {
		global $passwords;
		if (isset($user) && isset($passwd)){

			$user = $this->secure($user);
			$passwd = $this->secure($passwd);

		if (isset($passwords[$user])) {
				$pass = md5($passwd);
				$data = $passwords[$user];
				if ($data['pass'] == $pass) {
					$_SESSION["user"] = $user;
					$_SESSION["hash"] = $pass;
					
					header( 'Location:' . $this->secure( $_SERVER['HTTP_REFERER'] ) );
				} else {
					$this->error = 'Wrong password! Try again!';
				} //check password
		} else {
			$this->error = 'Wrong Username! Try again!';
		} //check isset user
	}//check isset input
	
	} //login
	
	public function createLogout () {
		global $url;
		$url = $this->secure($_SERVER['REQUEST_URI']);
		$parseUrl =  parse_url($url);
		parse_str($parseUrl['query'], $query);
		$query['logout'] = 1;
		echo $parseUrl['host']."?".http_build_query(array_unique($query), '', '&amp;');
			
	} //create link logout
	
	public function logout () {
		session_start();
		$_SESSION = array();
		session_destroy();
		$this->logged = false;

		header( 'Location:' . $this->secure( $_SERVER['HTTP_REFERER'] ) );
		die();
		
	} //logout
	
	public function createUser ($user, $pass, $role, $date, $expdate) {
		global $passwords;
		$date      = date("Y/m/d");//date("Y/m/d h:i:sa")
		$expdate   = date('Y/m/d', strtotime('+1 years'));
		if ($this->isAdmin) {
		if (!isset($passwords[$user])) {
			$passwords[$user] = array('pass' => md5($pass), 'role' => $role, 'date' => $date, 'expdate' => $expdate);
			$line = '<?php 
';
			foreach ($passwords as $name => $password) {
				$line.='$passwords["'.$name.'"] = array("pass" => "'.$password['pass'].'", "role" => "'.$password['role'].'", "date" => "'.$password['date'].'", "expdate" => "'.$password['expdate'].'");
';
			}
			$line.= '?>';
			
			file_put_contents(dirname(__FILE__) . '/users.php', $line);
		} //check isset user
		} //check admin
	} //createUser
	
	public function delete ($user) {
		global $passwords;
		$response = 0;
		if ($this->isAdmin) { 
		$adminBlock = false;
		$user = $this->secure($user);
		
		if (isset($passwords[$user])) {
			if ($passwords[$user]['role'] == 'admin') {
				$admins = 0;
				foreach ($passwords as $name => $password) {
					if ($password['role'] == 'admin') { $admins++; }
				} //count admins
				if ($admins == 1) {
					$response = 1;
					$adminBlock = true;
				}
			} 
			
		if (!$adminBlock) {
			$response = 2;
			unset($passwords[$user]);
			$admins = 0;
			$line = '<?php 
';
			foreach ($passwords as $name => $password) {
				if ($password['role'] == 'admin') { $admins++; }
				$line.='$passwords["'.$name.'"] = array("pass" => "'.$password['pass'].'", "role" => "'.$password['role'].'", "date" => "'.$password['date'].'", "expdate" => "'.$password['expdate'].'");
';
			}
			$line.= '?>';
			
			file_put_contents(dirname(__FILE__) . '/users.php', $line);
	
		} //check role admin
		} //check isset user
		} //check admin
		
		return $response;
	} //delete user
	
} //end class

$protect = new PageProtection();
if (isset($_GET['logout'])) { $protect->logout(); }
if (isset($_POST['login'])) { $protect->login($_POST['user'],  $_POST['pass']); }


?>

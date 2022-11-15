<?php
require ("class_password.php");
$protect->enable('admin');
if ($protect->isAdmin) {
$error = false;
$error_div = '';
/* DELETE USER */
if (isset($_GET['delete'])) {
$error_message = '';
$res = $protect->delete($_GET['delete']); 

if ($res == 0) {
	$error_message = 'An error has occurred, please try again';
} elseif ($res == 1) {
	$error_message = 'Do not delete ADMIN before creating a new admin.';
} 

if ($error_message) {
	$error_div = str_replace("$1", $error_message, '<div class="text-danger">$1</div>');
}

} //end delete user
/*  CREATE USER */
if (isset($_POST['create'])) { 
$user = $protect->secure($_POST['user']);
$pass = $protect->secure($_POST['pass']);
$role = $protect->secure($_POST['role']);
$date = $protect->secure($_POST['date']);
$expdate = $protect->secure($_POST['expdate']);

if (strlen($user) == 0) {
$error = true;
$error_message = 'You must write the username';
} elseif (strlen($pass) == 0) {
$error = true;
$error_message = 'You must write the password';	
} 
//check empty input
if (!$error) {
	if (isset($passwords[$user])) {
		$error = true;
		$error_message = 'This Username already exists';
	} 
}
//check isset user
if (!$error) {
$protect->createUser($user, $pass, $role, $date, $expdate);
} else {
$error_div = str_replace("$1", $error_message, '<div class="text-danger">$1</div>');
} //check error
} //end create user
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin Page">
    <title>Admin Page</title>
	<link href="../assets/css/fontawesome.min.css" rel="stylesheet">
	<link href="../assets/css/app.css" rel="stylesheet">
	<style>
	table { 
		width: 100%; 
		border-collapse: collapse; 
		margin:50px auto;
		color: var(--navy);
	}

	/* Zebra striping */
	tr:nth-of-type(odd) { 
		background: #eee; 
	}
	
	th { 
		background: var(--primary); 
		color: var(--white); 
		font-weight: normal; 
	}
	
	td, th { 
		padding: 10px; 
		border: 1px solid var(--hover); 
		text-align: left; 
		font-size: 18px;
	}
	
	@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {

	table { 
	  	width: 100%; 
	}

	/* Force table to not be like tables anymore */
	table, thead, tbody, th, td, tr { 
		display: block; 
	}
	
	/* Hide table headers (but not display: none;, for accessibility) */
	thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; }
	
	td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}

	td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		/* Label the data */
		content: attr(data-column);

		color: #000;
		font-weight: bold;
	}

	}
	</style>
  </head>
<body>
<div id="files-wrap">
<div id="dashboard">
<h1>BgWriter<span class="primary">.</span>ai</h1>
<div id="dash-menu" class="admin">
<p>Create New User</p>
<form action="admin.php" method="POST">
	<?php echo $error_div; ?>
	<input type="text" name="user" class="admin-input" placeholder="Username" required>
	<input type="password" name="pass" class="admin-input" placeholder="Password" required>
	<select name="role">
		<option value="admin" selected>🛡️ Admin</option>
		<option value="user">🦱 User</option>
	</select>
	<input type="submit" class="admin-submit" value="Create" name="create">
</form>	
</div>
</div>
 <div class="grid">
	<h2><?php $total = count($passwords); echo $total; ?> Users <a href="<?php $protect->createLogout(); ?>" title="Logout" class="float-right"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></h2>
    <table class="table caption-top">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Role</th>
            <th scope="col">Registration Date</th>
            <!-- <th scope="col" style="display:none">Expiration Date</th>-->
            <th scope="col">Options</th>
        </tr>
    </thead>
    <tbody> <?php
	$i = 0;
	
	foreach ($passwords as $name => $password) {
		$i++;
		echo '
    <tr><th scope="row">'.$i.'</th><td data-column="Username">'.$name.'</td><td data-column="Role">'.$password['role'].'</td><td data-column="Registration Date">'.$password['date'].'</td><!--<td style="display:none" data-column="Expiration Date">'.$password['expdate'].'</td>--><td data-column="Options">'.($total == 1 ? '' : '<a href="admin.php?delete='.$name.'" class="delete" onclick="return confirm(\'Sure you want to cancel '.$name.'?\')">Delete User</a>').'</td></tr>';
			}
	?></tbody>
    </table>
</div>
</div>
<script src="https://kit.fontawesome.com/d128a728e7.js" crossorigin="anonymous"></script>
<script src="../assets/js/fontawesome.min.js"></script>			
<script>
//For iFrame blocking
if (self == top) {
  // Everything checks out, show the page.
  document.documentElement.style.display = 'block';
} else {
  // Break out of the frame.
  top.location = self.location;
}
</script>
  </body>
</html>
<?php
} else {
die('You can not access this page');	
} //end check admin
?>
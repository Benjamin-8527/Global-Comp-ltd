<!DOCTYPE html>
<html lang="en">
<head>
    <title>USERS</title>
</head>
	<form action="users.php" method="post">
    <label>broad:</label>
    <input type="broad" name="broad" required>
    
    <label>small:</label>
    <input type="small" name="small" required>
</form>

<?php
$broad==A
$small==2

if($broad==A && $small==1){
	echo"Admin:Manager";

}
else($broad==A && $small==2){
	echo"Admin:Supervisor";
}
else($broad==A && $small==3){
	echo"Admin:System Admin";
}
else($broad==A && $small==4){
	echo"Admin:System Admin";
}
else($broad==A && $small==5){
	echo"Admin:Directors";
}
else($broad==B && $small==1){
	echo"Middle level Management:HODs";
}
else($broad==B && $small==2){
	echo"Middle level Management:Head of Sections";
}
else($broad==B && $small==3){
	echo"Middle level Management:Estate Manager";
}
else($broad==C && $small==1){
	echo"General Workers:Tellers";
}
else($broad==C && $small==2){
	echo"General Workers:Office Assistant";
}
}
else($broad==C && $small==3){
	echo"General Workers:Secretaries";
}
else($broad==C && $small==4){
	echo"General Workers:Hygiene Practitioner";
}
else
	echo"Invalid user";
}
?>
	
</head>
</body>
</html>

		

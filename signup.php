<?php
require_once("db.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Career Day Selection</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
		<div id="container">
			<section id="header">
				
			</section>
			<section id="process">
			</section>
			<section id="conformation">
				Student ID #:<br>
				First Name:<br>
				Last Name:<br>
				Homeroom #:<br>
				Grade:<br>
				Choice 1:<br>
				Choice 2:<br>
				Choice 3:<br>
				Choice 4:
			</section>
			<section id="form">
				
				<?php
				$db = new Database();
				$notice = "";
				if ( array_key_exists("submit", $_POST) )
				{
					$result = $db->addStudent($_POST['id'], $_POST['first'], $_POST['last'], $_POST['grade'], $_POST['homeroom']);
					echo mysql_error();
					if ( $result )
					{
						if ( !$db->setStudentChoices($_POST['id'], $_POST['c1'], $_POST['c2'], $_POST['c3'], $_POST['c4']) )
							$notice = "Please choose a unique perference for all four choices.";
						else
							$notice = "Success!";
					}
					else
					{
						$notice = "Invalid Student Data";
					}
				}
			
				$careers = $db->getCareers();


				if ( !empty($notice) )
				{
					echo $notice."<br><br>";
					if ( $notice != "Success!")
					{
						$database->resetStudent($_POST['id']);
					}
				}
				?>

				<form method="post" action="">
					<div id="sect-0" class="sect">
						Student ID#: <input type="text" name="id">
					</div>
					<div id="sect-1" class="sect">
						First Name: <input type="text" name="first">
					</div>
					<div id="sect-2" class="sect">
						Last Name: <input type="text" name="last">
					</div>
					<div id="sect-3" class="sect">
						Homeroom #: <input type="text" name="homeroom">
					</div>
					<div id="sect-4" class="sect">
						Grade:
						<select name="grade">
							<option value="0" selected="selected" disabled="disabled">Select One</option>
							<option value="9">Freshman</option>
							<option value="10">Sophomore</option>
							<option value="11">Junior</option>
							<option value="12">Senior</option>
						</select>
					</div>
					<?php 
				for ( $i = 0; $i < 4; $i++ )
				{
					$n = $i+1;
					?>
					<div id="sect-<?php echo ($n + 5) ?>" class="sect">
						Choice <?php echo $n; ?>: 
						<select name="c<?php echo $n; ?>">
							<option value="0" selected="selected" disabled="disabled">Select One</option>
							<?php 
							foreach ( $careers as $career )
							{
							?>
								<option value="<?php echo $career['id']; ?>"><?php echo $career['name']; ?></option>
							<?php
							}
							?>
						</select>
				</div>	
				<?php
				}
				?>
				<input type="submit" name="submit" value="Signup">
			</form>
			</section>
			<section id="footer">
				
			</section>
		</div>
	</body>
</html>
<?php
require_once "db.php";
$careers = $database->getCareers(false);
$careersSortPivot = array();

foreach ( $careers as $k=>$v )
{
	$careersSortPivot[$k] = $v['name'];
}

array_multisort($careersSortPivot, SORT_ASC, $careers);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Career Day Selection</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="main.css">
		<script src="jquery.js"></script>
		<script src="keyValidator.js"></script>
		<script>
		var form = 0;
		
		function init(){
			showForm(0);
		}
		
		function showForm(form){
			for(i=0;i<9;i++)
				$("#sect-"+i).hide();
			$("#sect-"+form).fadeIn();
		}
		
		function choicesAreUnique()
		{
			for ( a = 5; a <= 8; a++ )
			{
				aVal = document.getElementById("f"+a).value;
				for ( b = 5; b <= 8; b++ )
				{
					bVal = document.getElementById("f"+b).value;
					if ( a != b && aVal == bVal && aVal != 0 )
						return false;
				}
			}
			return true;
		}

		function getCareerNameForID(id)
		{
			var careerList = document.getElementById("f5").options;
			for ( var i = 0; i < careerList.length; i++ )
			{
				if ( careerList[i].value == id )
					return careerList[i].text;
			}
			return "";
		}

		function update(num){
			if ( !choicesAreUnique() )
			{
				alert("Every career choice must be different");
				return;
			}

			if ( num >= 5 && num <= 8 ) // Career lists
			{
				var careerList = document.getElementById("f5").options;
				document.getElementById("c"+num).innerHTML = getCareerNameForID(document.getElementById("f"+num).value);
			}
			else // Normal Fields
				document.getElementById("c"+num).innerHTML = document.getElementById("f"+num).value;

			var process = 0;
			var bar = 20;
			var isOptOut = document.getElementById("optOutButton").checked;
			for(i=0;i<9;i++){
				if ( isOptOut && i >= 5 && i <= 8 )
				{
					process = process + (100/10);
					bar = bar + (860/10);
				}
				else if(document.getElementById("f"+i).value != "" && document.getElementById("f"+i).value != 0){
					process = process + (100/10);
					bar = bar + (860/10);
				}
			}
			
			$("#precent").html(Math.floor(process));
			$("#InBar").animate({
				width: bar + "px"},
				1000,
				function(){
					if(bar >= ((860/10)*9) && (choicesAreUnique() || document.getElementById("optOutButton").checked) )
						$("#submitarea").slideDown();
					else
						$("#submitarea").slideUp();
				}
			);
		}
		
		function seniorCheck(){
			update(4);
			if(document.getElementById("f4").value == 12) // Senior Selected
			{
				document.getElementById("optOutButton").disabled = false;
				document.getElementById("optOutContainer").style.display="block";
			}
			else
			{
				document.getElementById("optOutContainer").style.display="none";
				document.getElementById("optOutButton").disabled = true;
				document.getElementById("optInButton").checked = true;
				document.getElementById("optOutButton").checked = false;
				disabledChoicesCheck();
			}
		}
		
		function disabledChoicesCheck()
		{
			update(0);
			state = document.getElementById("optOutButton").checked;
			var careerList = document.getElementById("f5").options;
			for ( i = 5; i <= 8; i++ )
			{
				document.getElementById("f"+i).disabled = state;
				
				if ( state )
				{
					document.getElementById("c"+i).innerHTML = "N/A";
				}
				else
				{
					careerID = document.getElementById("f"+i).value;
					document.getElementById("c"+i).innerHTML = (careerID==0 ? "" : getCareerNameForID(careerID));
				}
			}
		}
		
		function doFinishAnimationAndSubmit()
		{
			$("#precent").html("100");
			$("#InBar").animate({
				width: "880px"},
				500,
				function()
				{
					submitToServer();
				});
		}
		
		function submitToServer()
		{			
			_id = document.getElementById("f0").value;
			_first = document.getElementById("f1").value;
			_last = document.getElementById("f2").value;
			_homeroom = document.getElementById("f3").value;
			_grade = document.getElementById("f4").value;
			dataToSend = "";
			if ( _grade == 12 && document.getElementById("optOutButton").checked )
			{
				dataToSend = {id: _id, first: _first, last: _last, homeroom: _homeroom, grade:_grade, isSeniorOptOut:true};
			}
			else
			{
				_c1 = document.getElementById("f5").value;
				_c2 = document.getElementById("f6").value;
				_c3 = document.getElementById("f7").value;
				_c4 = document.getElementById("f8").value;
				dataToSend = {id: _id, first: _first, last: _last, homeroom: _homeroom, grade:_grade, c1: _c1, c2: _c2, c3: _c3, c4: _c4, isSeniorOptOut:false};
			}
			
			$.ajax({
				type: "POST",
				url: "signupHandler.php",
				data: dataToSend,
				success: function (data)
				{
					if ( data == "fail" )
					{
						alert("Please make sure that all of the information you have entered is valid.\n");
						update(0);
					}
					else if ( data == "dup" )
					{
						alert("You have already submitted your career choices.\n\nYou must call the help desk to clear your choices at extention 1776");
						update(0);
					}
					else
					{
						alert("Thank you! Your choices have been recorded.\n");
						window.location.reload();
					}
				}
			});
			
		}
		</script>
	</head>
	<body onload="init()">
		<div id="container">
			<section id="header">
				<h1>Career Day</h1>
			</section>
			<div id="content">
				<section id="process">
					Process: <span id="precent">0</span>% complete.
					<div id="ExBar">
						<div id="InBar"></div>
					</div>
					<div id="submitarea">
						Click here to submit: <button id="submit" type="button" onClick="doFinishAnimationAndSubmit()">Submit</button>
					</div>
				</section>
				<section id="conformation">
					<a onclick="">Student ID #: <span id="c0"></span><br></a>
					<a onclick="">First Name: <span id="c1"></span><br></a>
					<a onclick="">Last Name: <span id="c2"></span><br></a>
					<a onclick="">Homeroom #: <span id="c3"></span><br></a>						
					<a onclick="">Grade: <span id="c4"></span><br></a>
					<a onclick="">Choice 1: <span id="c5"></span><br></a>
					<a onclick="">Choice 2: <span id="c6"></span><br></a>
					<a onclick="">Choice 3: <span id="c7"></span><br></a>
					<a onclick="">Choice 4: <span id="c8"></span><br></a>
				</section>
				<section id="formarea">
					<div id="sect-0" class="sect">
						Student ID #: <input type="text" id="f0" onblur="update(0)" onkeypress="return validateKeypress(event,2,6)"><br>
						First Name: <input type="text" id="f1" onblur="update(1)" onkeypress="return validateKeypress(event,1,999)"><br>
						Last Name: <input type="text" id="f2" onblur="update(2)" onkeypress="return validateKeypress(event,1,999)"><br>
						Homeroom: <select id="f3" onchange="update(3)">
							<option value="" selected="selected" disabled="disabled">-Select One-</option>
							<?php
							$homerooms = $database->getHomerooms();
							foreach ( $homerooms as $homeroom )
							{
								echo "<option value=\"".$homeroom['name']."\">".$homeroom['name']."</option>";
							}
							?>
						</select><br>
						Grade:
						<select id="f4" onchange="seniorCheck()">
							<option value="0" selected="selected" disabled="disabled">-Select One-</option>
							<option value="9">Freshman (9)</option>
							<option value="10">Sophomore (10)</option>
							<option value="11">Junior (11)</option>
							<option value="12">Senior (12)</option>
						</select><br>
						<button id="next" type="button" onclick="showForm(1)" value="Next">Next</button>
					</div>
					<div id="sect-1" class="sect">
						<div id="optOutContainer"><input type="radio" id="optOutButton" name="seniorOptOut" value="1" disabled="disabled" onChange="disabledChoicesCheck()" /><label for="optOutButton">I am going to career shadow or attend a college visit.</label><br><br>
						<input type="radio" id="optInButton" name="seniorOptOut" value="0" checked="checked" onChange="disabledChoicesCheck()" /><label for="optInButton">I am going to participate in career day.</label><br></div>
						Choice 1:
						<select id="f5" onchange="update(5)">
							<option value="0" selected="selected" disabled="disabled">-Select One-</option>
							<?php 
							foreach ( $careers as $career )
							{
								echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
							}
							?>
						</select><br>
						Choice 2:
						<select id="f6" onchange="update(6)">
							<option value="0" selected="selected" disabled="disabled">-Select One-</option>
							<?php 
							foreach ( $careers as $career )
							{
								echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
							}
							?>
						</select><br>
						Choice 3: 
						<select id="f7" onchange="update(7)">
							<option value="0" selected="selected" disabled="disabled">-Select One-</option>
							<?php 
							foreach ( $careers as $career )
							{
								echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
							}
							?>
						</select><br>
						Choice 4:
						<select id="f8" onchange="update(8)">
							<option value="0" selected="selected" disabled="disabled">-Select One-</option>
							<?php 
							foreach ( $careers as $career )
							{
								echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
							}
							?>
						</select><br>
						<button id="prev" type="button" onclick="showForm(0)" value="Prev">Prev</button>
					</div>
					
				</section>	
			</div>
			
		</div>
		<section id="footer">Algorithm by Andrew Lobos<br>Design by Ben Thomas</section>
	</body>
</html>

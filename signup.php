<?php
/*
	Career Day Sorting - Career Day Signup and Scheduling system
    Copyright (C) 2013 Andrew Lobos and Benjamin Thomas

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
		<style>
		body { padding-bottom: 70px; }
		</style>
		<script src="jquery-1.10.2.min.js"></script>
		<script src="keyValidator.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
		<script>
		var form = 0;
		
		function init(){
			seniorCheck();
			showFormInput(0);
			$("#submitarea").hide();
		}
		
		function showFormInput(inputNum){
			if(inputNum < 0 || inputNum > 9)
				return false;
			if(inputNum < 5) {
				$('#tabs a[href="#student-info"]').tab('show');
			}
			else {
				$('#tabs a[href="#student-choice"]').tab('show');
			}
			$('#f' + inputNum).focus();
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
				document.getElementById("c"+num).innerHTML = getCareerNameForID(document.getElementById("f"+num).value);
				$(".cf").children()
					.not("[value='0']")
					.removeAttr("disabled");
				for(i=5;i <= 8;i++){
					$(".cf").children("[value='" + document.getElementById("f"+i).value + "']")
						.not(":selected")
						.attr("disabled", true);
				}
			}
			else if (num == 4)
			document.getElementById("c"+num).innerHTML = document.getElementById("f"+num).value != 0 ? document.getElementById("f"+num).value : "";
			else // Normal Fields
				document.getElementById("c"+num).innerHTML = document.getElementById("f"+num).value;

			var process = 0;
			var isOptOut = document.getElementById("optOutButton").checked;
			for(i=0;i<9;i++){
				if ( isOptOut && i >= 5 && i <= 8 )
				{
					process = process + (100/10);
				}
				else if(document.getElementById("f"+i).value != "" && document.getElementById("f"+i).value != 0){
					process = process + (100/10);
				}
			}
			$("#precent").html(Math.floor(process));
			$("#InBar").css("width", process + "%")
				.delay(500)
				.queue( function(next){
					if(process >= ((100/10)*9) && (document.getElementById("optOutButton").checked || choicesAreUnique()) ){
						$("#submitarea").slideDown();
					}
					else{
						$("#submitarea").slideUp();
					}
					next();
			});
			
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
				width: "100%"},
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
		<div class="container">
			<section id="header" class="text-center">
				<h1>Career Day</h1>
			</section>
			<div id="content">
				<section id="process">
					<div class="progress">
						<div class="progress-bar" id="InBar"></div>
					</div>
					Process: <span id="precent">0</span>% complete.
					<div id="submitarea">
						<div class="row">
							<div class="col-xs-offset-4 col-xs-4">
								<p class="text-center">Click here to submit:<br><button id="submit" class="btn btn-primary" type="button" onClick="doFinishAnimationAndSubmit()">Submit</button></p>
								
							</div>
						</div>
					</div>
				</section>
				<br>
				<div class="row">
					<section class="col-md-4" id="conformation">
						<div class="well">
							<a onclick="showFormInput(0)">Student ID #: <span id="c0"></span><br></a>
							<a onclick="showFormInput(1)">First Name: <span id="c1"></span><br></a>
							<a onclick="showFormInput(2)">Last Name: <span id="c2"></span><br></a>
							<a onclick="showFormInput(3)">Homeroom #: <span id="c3"></span><br></a>
							<a onclick="showFormInput(4)">Grade: <span id="c4"></span><br></a>
							<a onclick="showFormInput(5)">Choice 1: <span id="c5"></span><br></a>
							<a onclick="showFormInput(6)">Choice 2: <span id="c6"></span><br></a>
							<a onclick="showFormInput(7)">Choice 3: <span id="c7"></span><br></a>
							<a onclick="showFormInput(8)">Choice 4: <span id="c8"></span><br></a>
						</div>
					</section>
					<form class="form-horizontal">
						<section class="col-md-8" id="formarea">
							<ul id="tabs" class="nav nav-tabs">
								<li class="active"><a href="#student-info" data-toggle="tab">Student Information</a></li>
								<li><a href="#student-choice" data-toggle="tab">Student Choices</a></li>
							</ul>
							<br>
							<div class="tab-content">
								<div id="student-info" class="tab-pane active">
									<div class="form-group">
										<label class="control-label col-sm-3">Student ID #:</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="f0" onblur="update(0)" onkeypress="return validateKeypress(event,2,6)">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">First Name:</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="f1" onblur="update(1)" onkeypress="return validateKeypress(event,1,999)">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Last Name:</label>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="f2" onblur="update(2)" onkeypress="return validateKeypress(event,1,999)">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Homeroom:</label>
										<div class="col-sm-9">
											<select class="form-control" id="f3" onchange="update(3)">
											<option value="" selected="selected" disabled="disabled">-Select One-</option>
											<?php
											$homerooms = $database->getHomerooms();
											foreach ( $homerooms as $homeroom )
											{
												echo "<option value=\"".$homeroom['name']."\">".$homeroom['name']."</option>";
											}
											?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Grade:</label>
										<div class="col-sm-9">
											<select class="form-control" id="f4" onchange="seniorCheck()">
												<option value="0" selected="selected" disabled="disabled">-Select One-</option>
												<option value="9">Freshman (9)</option>
												<option value="10">Sophomore (10)</option>
												<option value="11">Junior (11)</option>
												<option value="12">Senior (12)</option>
											</select>
										</div>
									</div>
									<button id="next" class="btn btn-primary pull-right" type="button" onclick="showFormInput(5)" value="Next">Next</button>
								</div>
								<div id="student-choice" class="tab-pane">
									<div id="optOutContainer" class="form-group">
										<div class="col-sm-offset-3">
											<div class="radio">
												<label for="optOutButton">
													<input type="radio" id="optOutButton" name="seniorOptOut" value="1" disabled="disabled" onChange="disabledChoicesCheck()" />
													I am going to career shadow or attend a college visit.
												</label>
											</div>
										</div>
										<br>
										<div class="col-sm-offset-3">
											<div class="radio">
												<label for="optInButton">
													<input type="radio" id="optInButton" name="seniorOptOut" value="0" checked="checked" onChange="disabledChoicesCheck()" />
													I am going to participate in career day.
												</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Choice 1:</label>
										<div class="col-sm-9">
											<select class="cf form-control" id="f5" onchange="update(5)">
												<option value="0" selected="selected" disabled="disabled">-Select One-</option>
												<?php 
												foreach ( $careers as $career )
												{
													echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Choice 2:</label>
										<div class="col-sm-9">
											<select class="cf form-control" id="f6" onchange="update(6)">
												<option value="0" selected="selected" disabled="disabled">-Select One-</option>
												<?php 
												foreach ( $careers as $career )
												{
													echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Choice 3:</label>
										<div class="col-sm-9">
											<select class="cf form-control" id="f7" onchange="update(7)">
												<option value="0" selected="selected" disabled="disabled">-Select One-</option>
												<?php 
												foreach ( $careers as $career )
												{
													echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Choice 4:</label>
										<div class="col-sm-9">
											<select class="cf form-control" id="f8" onchange="update(8)">
												<option value="0" selected="selected" disabled="disabled">-Select One-</option>
												<?php 
												foreach ( $careers as $career )
												{
													echo "<option value=\"".$career['id']."\">".$career['name']."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<button id="prev" class="btn btn-primary pull-right" type="button" onclick="showFormInput(0);" value="Prev">Prev</button>
								</div>
							</div>
						</section>
					</form>
				</div>
			</div>
			<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
				<div class="collapse navbar-collapse">
					<p class="navbar-text">Algorithm by Andrew Lobos. Design by Benjamin Thomas</p>
				</div>
			</nav>
		</div>
	</body>
</html>

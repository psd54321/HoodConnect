<?php session_start();?>
<?php include "header2.php"; ?>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="pjfirstpage.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
<link rel="stylesheet" type="text/css" href="pjcss.css">

<form action="signupcomplete.php" method="post">


<input type="hidden" id="lat" name="lat" size="10">
<input type="hidden" id="lng" name="lng" size="10">

<input type="hidden" id="bno" name="bno">
<input type="hidden" id="street" name="street">
<input type="hidden" id="zip" name="zip" required>
<div id="enterinfo" style="margin-left:10px; color:#1e1c1c;">

<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>       
        <script>
            var myApp = angular.module("myapp", []);
            myApp.controller("PasswordController", function($scope) {
 
                var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
                var mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");
 
                $scope.passwordStrength = {
                    "float": "left",
                    "width": "140px",
                    "height": "25px",
                    "margin-left": "145px"
					
                };
 
                $scope.analyze = function(value) {
				if($scope.username==$scope.password)
				{
				alert("Password cannot be same as username");
				}
				else
				{
                    if(strongRegex.test(value)) {
                        $scope.passwordStrength["background-color"] = "green";
						document.getElementById("passwordStrength").innerHTML="Strong Password";
                    } else if(mediumRegex.test(value)) {
                        $scope.passwordStrength["background-color"] = "orange";
						document.getElementById("passwordStrength").innerHTML="Medium Password";
                    } else {
                        $scope.passwordStrength["background-color"] = "red";
						 document.getElementById("passwordStrength").innerHTML="Weak Password";
                    }
				}
                };
 
            });
        </script>
    
    <body ng-app="myapp">
        <div ng-controller="PasswordController">
            Username<input type="text" ng-model="username" style="margin-left:80px;margin-top:10px;" id="username" name="username" onBlur="checkAvailability()" required><span id="user-availability-status"></span> <br/>
              <p> Password : <input type="password" ng-model="password" ng-Blur="analyze(password)" id="pwd" name ="pwd" style="margin-left:73px;" /> </p>
			  <p>	<div id ="passwordStrength" ng-style="passwordStrength" ></div><br> </p>
			              
        </div>
    </body>


Re-type Password<input type="password" style="margin-left:30px;" id="pwd1" name="pwd1" required><br/>
First Name<input type="text"  style="margin-left:73px;" id="fname" name="fname" required><br/>
Last Name<input type="text" style="margin-left:75px;" id="lname" name="lname" required><br/>
<div class="editor-label">Enter Something about you</div><div class="editor-field"><textarea required name="profile" rows="5" cols="50"></textarea></div>
</div>
<div id="map_canvas"></div>
<br/>
<input style="margin-left:20px;" type="submit" value"Register" onclick="return validate_register();">

</form>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>AnyShare - Sign In</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/loader.css">
<!--===============================================================================================-->
	<link rel="apple-touch-icon" sizes="57x57" href="images/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="images/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="images/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="images/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="images/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="images/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="images/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
	<link rel="manifest" href="images/manifest.json">
	<meta name="msapplication-TileColor" content="#63B7AA">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#63B7AA">
<!--===============================================================================================-->
</head>
	<body>
		<div id="loader">
		 <div class="spinner"></div>
		</div>
		<script>
			window.onload = function() {
				var imageno = Math.floor(Math.random() * 5);
				bgc.style.backgroundImage = "url('images/background" + imageno + ".jpg')";
			}
		</script>
		<div class="limiter">
			<div class="container-login100" id="bgc" style="">
				<div class="wrap-login100 p-t-40 p-b-30">
					<form class="login100-form validate-form" action="auth.php" method="post" id="loginform">
						<div class="login100-form-avatar">
							<img src="images/icon_128.png" alt="AnyShare">
						</div>

						<span class="login100-form-title p-t-10 p-b-0">
							AnyShare
						</span>
						<div class="text-center w-full p-b-20">
							<span class="txt1" href="#">
								store. share. synchronize.
							</span>
						</div>


						<div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
							<input class="input100" type="text" name="un" id="un" placeholder="Email ID/Username">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-user"></i>
							</span>
						</div>

						<div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
							<input class="input100" type="password" name="pw" id="pw" placeholder="Password">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock"></i>
							</span>
						</div>

						<div class="container-login100-form-btn p-t-10">
							<button class="login100-form-btn">
								Sign In
							</button>
						</div>

						<div class="text-center w-full p-t-25 p-b-50">
							<a href="#" class="txt1">
								Forgot Username / Password?
							</a>
						</div>

						<div class="text-center w-full txt1">
							New to AnyShare?
							<a class="txt1" href="#">
								Sign up
								<i class="fa fa-long-arrow-right"></i>
							</a>
						</div>
					</form>

				</div>
			</div>
		</div>

		<!-- Message Modal -->
		<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="msgModalTitle">[Title]</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				  <div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="text-center">
									<form id="msgModalForm" method="POST">
										<p style="margin-bottom: 32px" id="msgModalText">[Text]</p>
										<div class="container-login100-form-btn" style="margin-top: 24px; margin-bottom: 16px;">
											<div class="wrap-login100-form-btn">
												<div class="login100-form-bgbtn"></div>
												<button class="login100-form-btn" id="msgModalButton" data-dismiss="modal">
													[Caption]
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
			  </div>
		  </div>
		  </div>
		</div>
	<!--===============================================================================================-->
	<!--================================Client Side Authentication Script==============================-->
	<script language="javascript">

	</script>
	<!--===============================================================================================-->

	<!--===============================================================================================-->
		<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
		<script src="vendor/bootstrap/js/popper.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
		<script src="vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
		<script src="js/main.js"></script>
	<!--===============================================================================================-->
		<script>
			$(document).ready(function(){
				loader.classList.remove('fadeOut');
				var dbflag = checkDashboardFlag();
				if (dbflag) {

				} else {
					checkSigninCookie();
					getSignout();
				}
				$("#resetpasswordform").submit(function(){
					var frm = $(this);
					var url = "sendrplink.php";
					var req = $.ajax({
						   type: "POST",
						   url: url,
						   data: frm.serialize(), // serializes the form's elements.
						   success: function(data)
						   {
							   // validate response and show message
								 if (data == "ok") {
									 alert("A password reset link has been sent to the given e-mail id. Please check your e-mail to reset password.");
								 } else if (data == "alreadysent") {
									 alert("A password reset link has already been generated within 30 minutes of current time. If you want to generate an another one, please try after half-an-hour.");
								 } else if (data == "notexist") {
									 alert("No account exists with the given e-mail id. If you're trying to register, please click the 'Sign up' link.");
								 } else if (data == "noargs") {
									 alert("Error with client side.")
								 } else if (data == "dberror") {
									 alert("Server error.")
								 } else {
									 alert(data);
								 }
								 $('#pwdModal').modal('hide');
						   },
						 error: function(errorCode)
						 {
							 alert("Error " + eval(errorCode.status) + ": Unexpected error while making request to server.");
							 $('#pwdModal').modal('hide');
						 }
					 });

					event.preventDefault(); // avoid to execute the actual submit of the form.
				});
			});

			/* Ajax Authentication Logic */
			function authenticateUser(formvalid) {
				if (formvalid) {
					// show loader
					loader.style.opacity = 0.5;
					loader.classList.remove('fadeOut');
					// make ajax request
					var frm = $("#loginform");
					var url = "auth.php";
					var req = $.ajax({
						   type: "POST",
						   url: url,
						   data: frm.serialize(), // serializes the form's elements.
						   success: function(data)
						   {
							   // validate response and show message
							   if (data == "authok") {
								   window.location.href = "dashboard.php";
							   } else if (data == "invalidpw") {
								   showMsgModal("Authentication Failure", "Invalid password. Please check and try again.", "OK", 2);
								   pw.value = "";
							   } else if (data.startsWith("alreadysignedin")) {
								   var msgsplit = data.split("~");
								   showMsgModal("Authentication Failure", "The username has already been signed in somewhere. Signout it to sign-in again. Normally a session will timeout in a day from sign-in time. <br><br><b>## LAST SIGN-IN DETAILS ##</b><br> <b>User Agent:</b> " + msgsplit[1] + "<br> <b>Client:</b> " + msgsplit[2] + "<br> <b>Timestamp:</b> " + msgsplit[3], "OK", 1);
								   pw.value = "";
							   } else if (data == "sessiontimeout") {
								   showMsgModal("Authentication Failure", "Session timed out. Please sign-in again.", "OK", 1);
								   un.value = "";
								   pw.value = "";
							   } else if (data == "invaliduser") {
								   showMsgModal("Authentication Failure", "No account exists with the entered username. If you're trying to register, please click the 'Sign up' link.", "OK", 2);
								   un.value = "";
								   pw.value = "";
							   } else if (data == "argserror") {
								   showMsgModal("Internal Error", "Error with client side.", "OK", 2);
							   } else if (data == "invalidtoken") {
								   showMsgModal("Security Error", "Cannot sign-in with invalid token.", "OK", 2);
							   } else if (data == "dbfail") {
								   showMsgModal("Internal Error", "Server error.", "OK", 2);
							   } else {
								   alert(data);
							   }
							   loader.classList.add('fadeOut');
						   },
						 error: function(errorCode)
						 {
							 alert("Error " + eval(errorCode.status) + ": Unexpected error while making request to server.");
							 loader.classList.add('fadeOut');
						 }
					 });
				}
			}

			function showMsgModal(title, text, buttonCaption, msgType) {
				/*	msgType can be of the following values
					0 - Information
					1 - Warning
					2 - Error
				*/

				switch (msgType) {
					case 0:
						msgModalTitle.innerHTML = "<b style=\"color: royalblue;\"><i class=\"fa fa-info\" style=\"padding-right: 16px;\"></i>" + title + "</b>";
						break;
					case 1:
						msgModalTitle.innerHTML = "<b style=\"color: orange;\"><i class=\"fa fa-exclamation\" style=\"padding-right: 16px;\"></i>" + title + "</b>";
						break;
					case 2:
						msgModalTitle.innerHTML = "<b style=\"color: red;\"><i class=\"fa fa-times\" style=\"padding-right: 16px;\"></i>" + title + "</b>";
						break;
					default:
						msgModalTitle.innerHTML = "<b style=\"color: royalblue;\"><i class=\"fa fa-info\" style=\"padding-right: 16px;\"></i>" + title + "</b>";
						break;
				}
				msgModalText.innerHTML = text;
				msgModalButton.innerHTML = buttonCaption;
				$("#msgModal").modal("show");
				msgModalButton.focus();
			}

			function dismissMsgModal() {
				$("#msgModal").modal("hide");
			}

			$("#msgModalForm").submit(function(){
				event.preventDefault();
				dismissMsgModal();
			});

			function checkSigninCookie() {
				var cookie = '<?php if (isset($_COOKIE["anyshareAuth"])) { echo $_COOKIE["anyshareAuth"]; } else { echo "nil"; } ?>';
				console.log("Cookie info: " + cookie);
				if (cookie != "nil") {
					var url = "auth.php";
					var req = $.ajax({
						   type: "POST",
						   url: url,
						   data: "token=" + cookie, // serializes the form's elements.
						   success: function(data)
						   {
							   loader.classList.add('fadeOut');
							   // validate response and show message
							   if (data == "authok") {
								   window.location.href = "dashboard.php";
							   } else if (data == "wrongpw") {
								   showMsgModal("Authentication Failure", "Invalid password. Please check and try again.", "OK", 2);
								   pw.value = "";
							   } else if (data.startsWith("alreadysignedin")) {
								   var msgsplit = data.split("~");
								   showMsgModal("Authentication Failure", "The username has already been signed in somewhere. Signout it to sign-in again. Normally a session will timeout in a day from sign-in time. <br><br><b>## LAST SIGN-IN DETAILS ##</b><br> <b>User Agent:</b> " + msgsplit[1] + "<br> <b>Client:</b> " + msgsplit[2] + "<br> <b>Timestamp:</b> " + msgsplit[3], "OK", 1);
								   pw.value = "";
							   } else if (data == "sessiontimeout") {
								   showMsgModal("Authentication Failure", "Session timed out. Please sign-in again.", "OK", 1);
								   un.value = "";
								   pw.value = "";
							   } else if (data == "notexist") {
								   showMsgModal("Authentication Failure", "No account exists with the entered username. If you're trying to register, please click the 'Sign up' link.", "OK", 2);
								   un.value = "";
								   pw.value = "";
							   } else if (data == "argserror") {
								   showMsgModal("Internal Error", "Error with client side.", "OK", 2);
							   } else if (data == "invalidtoken") {
								   showMsgModal("Security Error", "Cannot sign-in with invalid token.", "OK", 2);
							   } else if (data == "dberror") {
								   showMsgModal("Internal Error", "Error with server side.", "OK", 2);
							   } else {
								   alert(data);
							   }
						   },
						 error: function(errorCode)
						 {
							 alert("Error " + eval(errorCode.status) + ": Unexpected error while making request to server.");
							 loader.classList.add('fadeOut');
						 }
					 });
				} else {
					loader.classList.add('fadeOut');
				}
			}

			function getSignout() {
				var soflag = '<?php if (isset($_POST['signout'])) { echo $_POST['signout']; } else { echo "noflag"; }?>';

				if (soflag == "true") {
					showMsgModal("Sign Out Successful", "You have been signed out successfully.", "OK", 0);
				} else if (soflag == "false") {
					showMsgModal("Sign Out Failure", "There was an internal error signing you out.", "OK", 1);
				} else {

				}
			}

			function checkDashboardFlag() {
				var flag = '<?php if (isset($f)) { echo $f; } ?>';
				if (flag == "sessiontimeout") {
					showMsgModal("Authentication Failure", "Session timed out. Please sign-in again.", "OK", 2);
					return true;
				} else if (flag == "nocookie") {
					showMsgModal("Warning", "You have to sign-in first.", "OK", 1);
					return true;
				} else if (flag == "unknownerror") {
					showMsgModal("Error", "Unknown error occurred. Please try again later.", "OK", 1);
					return true;
				} else {
					return false;
				}
			}
		</script>
	</body>
</html>

<!DOCTYPE html>
<html>
<body>
    <div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="circle_avatar.jpg" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<!-- <form action="login_processing.php" method="post" onsubmit="return validPassword()"> -->
					<form action="login_processing.php" method="post">
						<table>
							<tr>
								<td style="text-align: right;">Username:</td>
								<td><input type="text" name="username"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style="text-align: right;">Password:</td>
								<td><input type="password" name="password" id="pwd"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						<!-- <script>
							function validPassword() {
								var str = document.getElementById("pwd").value;
								var list = [];
								const letters = (() => {
									const caps = [...Array(26)].map((val, i) => String.fromCharCode(i + 65));
									return caps;
								})();

								for (var i = 0; i <= 9; i++) {
									list.push(i.toString());
								}
								
								error = "";
								num_check = false;
								uppercase_check = false;
								if (str.length < 8){
									error += "Min length is 8 chars";
								}
								for (let i = 0; i < str.length; i++){
									if (list.includes(str[i])){
										num_check = true;
									}
									if (letters.includes(str[i])){
										uppercase_check = true;
									}
								}
								if (num_check == false){
									if (error.length == 0){
										error = "At lease one number";
									}
									else{
										error += ", at least one number";
									}
								}
								if (uppercase_check == false){if (error.length == 0){
										error = "At least one uppercase letter";
									}
									else{
										error += ", at least one uppercase letter"
									}
									
								}
								if (error.length > 0) {
									document.getElementById("txtError").innerHTML = error;
									alert(error);
									return false;
								}
								else{
									return true;
								}
							}
						</script> -->
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customControlInline">
								<label class="custom-control-label" for="customControlInline">Remember me</label>
							</div>
						</div>
						<div class="d-flex justify-content-center mt-3 login_container">
				 			<button type="submit" name="button" class="btn login_btn">Login</button>
				   		</div>
					</form>
				</div>
		
				<div class="mt-4">
					<div class="d-flex justify-content-center links">
						Don't have an account? <a href="index.php?page=register" class="ml-2">Sign Up</a>
					</div>
					<div class="d-flex justify-content-center links">
						<a href="#">Forgot your password?</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
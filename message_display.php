<?php
	function error($message)
	{
		return '<script>
					document.getElementById("message").innerHTML = "'.$message.'";
					document.getElementById("alert-message").style.display = "block";
					document.getElementById("alert-message").className = "mt-3 alert alert-warning text-center";
				</script>';
	}
	
	function error_with_field($message, $field)
	{
		return '<script>
					document.getElementById("error").innerHTML = "'.$message.'";
					document.getElementById("error-message").style.display = "block";
					document.getElementById("'.$field.'").className += " error-field";
				</script>';
	}
	
	function success($message)
	{
		return '<script>
					document.getElementById("message").innerHTML = "'.$message.'";
					document.getElementById("alert-message").style.display = "block";
					document.getElementById("alert-message").className = "mt-3 alert alert-success text-center";
				</script>';
	}

?>
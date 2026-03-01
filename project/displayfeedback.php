<?php

session_start();
include "user_password.php";
if ((!isset($_SESSION["admin_ps"])) ||   $_SESSION["admin_ps"] != $user_password) {
	unset($_POST);
	unset($_FILES);
	unset($_SESSION["admin_ps"]);
	return header("Location: ./login.php");
}


// $error = "";
// $sucess = "";
// $file_path1 = $_GET['f1'];
// $file_path2  = $_GET['f2'];
// if (!isset($file_path1)  || !isset($file_path2)) {
//     header("Location:./upload_file.php");
// }

// if (!file_exists($file_path1) || !file_exists($file_path2)) {
//     header("Location:./upload_file.php");
// }



?>
<!doctype html>
<html lang="en">

<head>
	<title>Display Feedback</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/style.css">

</head>
<style>
	.rejct-btn {
		background-color: red !important;
		margin-left: 4px;
	}

	.ftco-section {
		padding-top: 30px;
	}
</style>

<body>
	<section class="ftco-section">

		<div class="container">

			<div class="row">
				<div class="col-md-12">
					<h4 class="text-center mb-4">Recevied Feedback</h4>
					<div class="table-wrap">
						<table class="table">
							<thead class="thead-primary">
								<tr>
									<th>S.No.</th>
									<th>Name</th>
									<th>Email</th>
									<th>Feedback</th>
									<th>Action</th>

								</tr>
							</thead>
							<tbody id='tbody'>




								<!-- <tr>

									<td>3</td>
									<td>Ramesh Kumar</td>
									<td>ramesh@gmail.com</td>
									<td>Please Upload Data Strucure Question bank for 5th Sem </td>
									<td>
										<a href="#" class="btn btn-primary">Approve</a>
										<a href="#" class="btn btn-primary rejct-btn">Reject</a>
									</td>

								</tr> -->









							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

	<script>


		function handle_status(status, id) {

			console.log(status, id)


			let req_body = {
				"status": status,
				"id": id,


			}

			const formData = new FormData();

			for (const name in req_body) {
				// console.log(name, req_body[name] )
				formData.append(name, req_body[name]);
			}
			// console.log(formData)

			let res = fetch('./api/update_feedback.php', {
					method: "POST",
					body: (formData)

				})


				.then(async (res) => {
					let data;
					// console.log( res) ;  
					if (res.status >= 200 && res.status < 300) {
						// console.log("success data", res.status)
						data = await res.json();
						// console.log(data)
						alert(data.success)
						window.location.reload();
					} else {

						data = await res.json();

						if (data.error) {
							alert(data.error)
						} else {
							alert("something went wrong")
						}
					}

				})
				.catch(async (err) => {
					console.error("err")
					console.error(err)
					data = await err.json();

					if (data.error) {
						alert(data.error)
					} else {
						alert("something went wrong")
					}
				});
		}

		function create_row(row, index) {


			return `	
					<tr>

						<td>${index +1 } </td>
						<td>${row.name} </td>
						<td>${row.email} </td>
						<td>${row.feedback}  </td>
						<td style='display:flex'>
							<a href="#" onclick=handle_status('approved',${row.id}) class="btn btn-primary">${row.status ? 'Approved' : 'Approve'}</a> 
							<a href="#" onclick=handle_status('rejected',${row.id})  class="btn btn-primary rejct-btn">Reject</a>
						</td>

					</tr>

`

		}





		let res = fetch('./api/get_feedback.php', {

			})


			.then(async (res) => {
				let data;
        
				if (res.status >= 200 && res.status < 300) {
					console.log("success data", res.status)
					data = await res.json();
					console.log(data)

					data = data.data;
					let result_html = '';
					for (let i = 0; i < data.length; i++) {
						const element = data[i];
						result_html += create_row(data[i], i);
					}

					document.getElementById('tbody').innerHTML = result_html;

				} else {

					data = await res.json();

					if (data.error) {
						alert(data.error)
					} else {
						alert("something went wrong")
					}
				}

			})
			.catch(async (err) => {
				console.error("err")
				console.error(err)
				data = await err.json();

				if (data.error) {
					alert(data.error)
				} else {
					alert("something went wrong")
				}
			});
	</script>

</body>

</html>
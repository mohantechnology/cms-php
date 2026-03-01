<?php

// session_start();
// include "user_password.php";
// if ((!isset($_SESSION["admin_ps"])) ||   $_SESSION["admin_ps"] != $user_password) {
//     unset($_POST);
//     unset($_FILES);
//     unset($_SESSION["admin_ps"]);
//     return header("Location: ./login.php");
// }


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



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Submit Feedback</title>
 

</head>
<style>
  body {
    background-color: #d1f3e7;
    padding-top: 62px;
  }

  #title-Tag-Line {
    font-size: 20px;
  }

  /* .card-item__bg{
  width: 150px;
  margin-left: auto;
  margin-right: auto;
  left: 0;
  right: 0;
  display: block;
  position: relative;
  margin: 30px auto;
  transform: translate(0px, 50px);
  z-index: 5;
} */

  /* form animation starts */
  .form {
    background: #fff;
    box-shadow: 0 30px 60px 0 rgba(90, 116, 148, 0.4);
    border-radius: 5px;
    max-width: 480px;
    margin-left: auto;
    margin-right: auto;
    padding-top: 5px;
    padding-bottom: 5px;
    left: 0;
    right: 0;
    position: absolute;
    border-top: 5px solid #0e3721;
    /*   z-index: 1; */
    animation: bounce 1.5s infinite;
  }

  ::-webkit-input-placeholder {
    font-size: 1.3em;
  }

  .title {
    display: block;
    font-family: sans-serif;
    margin: 10px auto 5px;
    width: 300px;
  }

  .termsConditions {
    margin: 0 auto 5px 80px;
  }

  .pageTitle {
    font-size: 2em;
    font-weight: bold;
  }

  .secondaryTitle {
    color: grey;
  }

  .name {
    background-color: #ebebeb;
    color: white;
  }

  .name:hover {
    border-bottom: 5px solid #0e3721;
    /* height: 30px;
    width: 380px; */
    /* transition: ease 0.5s; */
  }

  .email {
    background-color: #ebebeb;
    height: 2em;
  }

  .email:hover {
    border-bottom: 5px solid #0e3721;
    /* height: 30px;
    width: 380px; */
    transition: ease 0.5s;
  }

  .message {
    background-color: #ebebeb;
    overflow: hidden;
    height: 10rem;
  }

  .message:hover {
    border-bottom: 5px solid #0e3721;
    /* height: 12em;
    width: 380px; */
    transition: ease 0.5s;
  }

  .formEntry {
    display: block;
    margin: 30px auto;
    min-width: 300px;
    padding: 10px;
    border-radius: 2px;
    border: none;
    transition: all 0.5s ease 0s;
  }

  .submit {
    width: 200px;
    color: white;
    background-color: #0e3721;
    font-size: 20px;
  }

  .submit:hover {
    box-shadow: 15px 15px 15px 5px rgba(78, 72, 77, 0.219);
    /* transform: translateY(-3px); */
    /* width: 300px; */
    border-top: 5px solid #0e3750;
    border-radius: 0%;
  }

  #name {
    color: black;
  }
</style>

<body>
  <div class="wrapper">
    <form class="form">
      <div class="pageTitle title">Feedback Form </div>
      <div class="secondaryTitle title">Please fill this form .</div>
      <input type="text" id='name' class="name formEntry" placeholder="Name" />
      <input type="text" id='email' class="email formEntry" placeholder="Email" />
      <textarea id='feedback' class="message formEntry" placeholder="Write Your Feedback here..."></textarea>
      <!-- <input type="checkbox" class="termsConditions" value="Term"> -->
      <!-- <label style="color: grey" for="terms"> I Accept the <span style="color: #0e3721">Terms of Use</span> & <span style="color: #0e3721">Privacy Policy</span>.</label><br> -->
      <button class="submit formEntry" type='button' onclick="save_feedback()">Submit</button>
    </form>
  </div>
  <!-- <script src=" app.js"></script> -->

  <script>
    function save_feedback() {

      let name = document.getElementById('name');
      let email = document.getElementById('email');
      let feedback = document.getElementById('feedback');



      let req_body = {
        "name": name.value,
        "email": email.value,
        "feedback": feedback.value,

      }

      const formData = new FormData();

      for (const name in req_body) {
        // console.log(name, req_body[name] )
        formData.append(name, req_body[name]);
      }
      // console.log(formData)

      let res = fetch('./api/save_feedback.php', {
          method: "POST",
          body: (formData)

        })


        .then(async (res) => {
          let data ; 

          if (res.status >= 200 && res.status < 300) {
            console.log("success data", res.status)
            data = await res.json(); 
            console.log(data)
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
  </script>
</body>

</html>
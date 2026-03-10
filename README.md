![cms-upload_compressed](https://github.com/user-attachments/assets/4827342d-9180-407e-9995-b6bc1b73326b)Content Management System <br> Html, Css ,Javascript, PHP, MySQL <br>

<h3>Account Details </h3>
Phone Number :<b> 1111122222</b> <br>
 Password : <b>1111122222</b>

<button> <h2> <a href="https://college-cms.myselfproject.org/ssipmt.php" target="_blank"><img src="https://cdn.glitch.com/89f82df8-eb2c-4c0e-883d-494391c85865%2FScreenshot%20(2511).png?v=1607934281911" alt="" width="200px"></a></h2> </button>

<h1> Configuration</h1> <hr width="800" align="left"> <ul> <li>Configure your database detail in <b>config_db.php </b> file <p> </p> 
<pre>
// host name 
$__hostname =   "127.0.0.1" ; <br>
// database   username 
$__username =  "root" ; <br>
// database password
$__password =  "password" ;<br>
 //  database name 
 $__db_name =  "php_db" <br>
</pre>
</ul> <h1>Overview</h1> <hr width="800" align="left"> <ul> <p>It is a CMS website. Administrator of website can manage the contents and visitor can download the content. It is completely responsive website.It has the feature of checking existence of  current selected file  in  already uploaded files.  </ul>

<h1>DEMO </h1> <hr width="800" align="left"> <ul> <b> For Visitors </b><br> <br> <li> visitors can see the heading of each subject. Each  heading contains uploaded file links   and clicking on the link will download the files. <br> <img src="https://github.com/user-attachments/assets/f4e017f8-0687-4fd0-93bd-bfa47d08a961" alt="" width="700px"> </li>
 
 <br> <br> <br> <b>For Administrator </b> <li> Administrator can log into website and   upload Pdf and choose subjects and units using  dropdown. <br> <img src="https://github.com/user-attachments/assets/a642ab10-9447-44c4-86c3-27c43a1a0188" alt="" width="700px"> </li>
 

  <br> <li>Before uploading  Admin can check similarity in already uploaded file to prevent unnecessary uploads. After Some processing , all matching files are listed and also Admin can see the matching contents of the pdf by clicking on View Similarity. It will compare and show the matching tokens in both the pdf. Matching is done using   <a href="https://github.com/google/diff-match-patch" target="_blank">  Myer's diff algorithm. </a> <br> <img src="https://github.com/user-attachments/assets/bbdc575e-5077-4e9b-8f68-54ee087daaaa" alt="" width="700px"> </li>
  
  
  
 <br>  <br> <li>Uploaded contents can also be deleted easily.  If clicked on yes file is permanently removed from the server through AJAX call.  <br> <img src="https://github.com/user-attachments/assets/aa06afb5-0b67-4505-a523-a85b5ebb6718" alt="" width="700px"> </li>

 
  <br> <li> Visitor can send feedback to Admin to upload  new content  they want. <br> <img src="https://github.com/user-attachments/assets/58fa58f2-e85d-4d8d-b5ec-5b5041b7d7da" alt="" width="700px"> </li>
    <br> <li> Admin can Approve/Disapprove the visitor's request. An Email is sent to visitor's account regarding the feedback status.  <br> <img src="https://github.com/user-attachments/assets/b88098b7-5477-4c90-aa91-d3ee8f9e4aa0" alt="" width="700px"> </li>

 </ul>

 

<div style="height:60px"></div>

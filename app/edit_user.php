<?php
session_start();
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);
include_once("db.php");
$username = "";
$loggedIn = false;
$role = "";
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
    $username = $_SESSION["username"];
    $role = $_SESSION["role"];
    $loggedIn = $_SESSION["loggedIn"];
    if ($role == "operations") {
        header("Location: main.php");
    }
} else {
    header("Location: index.php");
}
$users = getStudents();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Edit User</title>
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light p-0">
    <a class="navbar-brand p-0" href="main.php">
        <img src="./logo.svg" width="120" height="90" alt="" loading="lazy">
    </a>
    <!-- <a class="navbar-brand" href="#">Code Gurukul</a> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
        <span class="nav-item float-right">
            <a class="nav-link" href="main.php" tabindex="-1">My Dashboard</a>
        </span>


        <span class="nav-item float-right">
            <?php
            if ($loggedIn) {
                echo '       
          <a class="nav-link" href="logout.php" tabindex="-1" >Logout</a>
       ';
            }
            ?>
        </span>
    </div>
</nav>
<div class="container mt-5">
    <div class="row text-center">
        <div class="col form-check">
            <input class="form-check-input" type="radio" name="sortby" id="emailRadio" value="0" checked>
            <label class="form-check-label" for="emailRadio">
                <b>Email</b>
            </label>
        </div>
        <div class="col form-check">
            <input class="form-check-input" type="radio" name="sortby" id="usernameRadio" value="1">
            <label class="form-check-label" for="usernameRadio">
                <b> Username</b>
            </label>
        </div>
        <div class=" col form-check">
            <input class="form-check-input" type="radio" name="sortby" id="nameRadio" value="2">
            <label class="form-check-label" for="nameRadio">
                <b> Name</b>
            </label>
        </div>
    </div>
    <div class="form-group">
        <input type="text" id="searchName" placeholder="Enter" class="form-control" onkeyup="search()">
    </div>
    <div class="col-sm">
        <table class="table" id="allUsername">
            <thead>
                <tr>
                    <th scope="col">Email</th>
                    <th scope="col">Username</th>
                    <th scope="col">Name</th>
                    <th scope="col" width="100px">Role</th>
                    <th scope="col" width="50px"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>$user->email</td>";
                    echo "<td>$user->username</td>";
                    echo "<td>" . ucfirst($user->first_name) . " "  . ucfirst($user->last_name) . "</td>";
                    echo "<td>$user->role</td>";
                ?>
                    <td>
                        <div class="input-group mb-3">
                            <span class="input-group-text p-0">
                                <?php
                                if (strtolower($user->role) == "student") {
                                ?>
                                    <button type="button" class="btn btn-sm  btn-success" onclick='edit_student(<?= json_encode($user) ?>)' data-toggle="modal" data-target="#editUserModal">
                                        Edit
                                    </button>
                                <?php
                                }
                                if (strtolower($user->role) == "teacher") {
                                ?>
                                    <button type="button" class="btn btn-sm  btn-success" onclick='edit_teacher(<?= json_encode($user) ?>)' data-toggle="modal" data-target="#editUserModal">
                                        Edit
                                    </button>
                                <?php
                                }
                                ?>

                            </span>
                        </div>

                    </td>
                <?php
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<!-- edit user  modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="editUserBody">

    </div>
</div>

<body>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
    <script>
        function intl_tel_input() {
            var phoneNumber = $("#phoneNumber");
            // initialise plugin
            phoneNumber.intlTelInput({
                initialCountry: "in",
                separateDialCode: true,
                preferredCountries: ["in", "us", "gb"],
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js",
            });
        }

        function validateNumber() {
            var phoneNumber = $("#phoneNumber");
            // console.log(phoneNumber.val());
            if (phoneNumber.val() != "") {
                if (phoneNumber.intlTelInput("isValidNumber")) {
                    var getCode = phoneNumber.intlTelInput("getSelectedCountryData");
                    // console.log(StelInput.intlTelInput("getNumber"));
                    $("#phoneNumberError").html("");
                    return true;
                } else {
                    $("#phoneNumberError").html(
                        "<span class='text-warning'><small>✶ Enter Valid Number</small></span>"
                    );
                    return false;
                }
            } else {
                $("#phoneNumberError").html("");
                return true;
            }
        }

        var phoneNumber = $("#phoneNumber");
        phoneNumber.blur(function() {
            validateNumber();
        });



        function edit_teacher(data) {
            // console.log(data)
            data = JSON.parse(JSON.stringify(data).replace(/\:null/gi, "\:\"\""));
            // console.log(data);
            let edit = `
            
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">${data.first_name} ${data.last_name}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id = "edit-user-form">
                <input type="hidden" name ="user_id" id="user_id" value="${data.user_id}" />
                    <!-- student -->
                    <div class="form-row">
                        <div class="col">
                            <small>Teacher First name</small>
                            <input type="text" class="form-control" name="first_name" placeholder=" Teacher First name" value="${data.first_name}">
                        </div>
                        <div class="col">
                            <small>Teacher last name</small>
                            <input type="text" class="form-control" name="last_name" placeholder="Teacher Last name" value="${data.last_name}">
                        </div>
                    </div>
                   
                    <!-- email and username -->
                    <div class="form-row">
                        <div class="col">
                            <small>Email</small>
                            <input type="text" class="form-control"  placeholder="Email" value="${data.email}" >
                        </div>
                        <div class="col">
                            <small>Username</small>
                            <input type="text" class="form-control" name="username" onkeyup="checkuser()" id="username" placeholder="username" value="${data.username}">
                            <span id="usernameError"></span>
                        </div>
                    </div>

                     <!-- qualifications -->
                    <div class="form-row">
                        <div class="col">
                            <small>Qualifications</small>
                            <input type="text" class="form-control" name="qualifications" placeholder="Qualifications" value="${data.qualifications}">
                        </div>
                    </div>

                    <!-- password and gender -->
                    <div class="form-row">
                        <div class="col">
                            <small>Password</small>
                            <input type="text" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="col">
                            <small>Course or Category</small>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="beginner_intermediate">Beginner+Intermediate</option>
                                <option value="beginner_advanced">Beginner+Advanced</option>
                                <option value="intermediate_advanced">Intermediate+Advanced</option>
                                <option value="beginner_intermediate_advanced">Beginner+Intermediate+Advanced</option>
                            </select>
                        </div>
                    </div>
                    <!--  username -->
                    <div class="form-row">
                        <div class="col">
                            <small>Gender</small>
                            <select name="gender" class="form-control input-field" id="gender">
                                <option selected="" value="">Choose...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="transgender">Transgender</option>
                                <option value="Prefer Not To Say">
                                Prefer Not To Say
                                </option>
                            </select>
                        </div>
                        <div class="col">
                            <small>DOB</small>
                            <input type="date" name="birthday_date" class="form-control" value="${data.birthday_date}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <small>Mobile Number</small>
                            <input type="text" class="form-control" placeholder="Phone Number" value="${data.mobile}" disabled>
                        </div>
                        <div class="col">
                            <br>
                            <input type="tel" class="form-control inp" onkeyup="validateNumber()" id="phoneNumber">
                            <span id="phoneNumberError"></span>
                        </div>
                    </div>
                     <div class="form-row">
                        <div class="col">
                            <button type="button" onclick="save_changes()" on class="btn btn-success form-control mt-3">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            `;

            $("#editUserBody").empty();
            $("#editUserBody").append(edit);
            let category = data.category.toLowerCase()
            let gender = data.gender.toLowerCase()
            $(`#category option[value="${category}"]`).attr("selected", true);
            $(`#gender option[value="${gender}"]`).attr("selected", true);
            intl_tel_input();
        }



        function edit_student(data) {
            // console.log(data)
            data = JSON.parse(JSON.stringify(data).replace(/\:null/gi, "\:\"\""));
            let edit = `
            
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">${data.first_name} ${data.last_name}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id = "edit-user-form">
                <input type="hidden" name ="user_id" id="user_id" value="${data.user_id}" />
                    <!-- student -->
                    <div class="form-row">
                        <div class="col">
                            <small>Student First name</small>
                            <input type="text" class="form-control" name="first_name" placeholder=" Student First name" value="${data.first_name}">
                        </div>
                        <div class="col">
                            <small>Student last name</small>
                            <input type="text" class="form-control" name="last_name" placeholder="Student Last name" value="${data.last_name}">
                        </div>
                    </div>
                    <!-- parent -->
                    <div class="form-row">
                        <div class="col">
                            <small>Parent First name</small>
                            <input type="text" class="form-control" name="parent_first_name" placeholder="Parent First name" value="${data.parent_first_name}">
                        </div>
                        <div class="col">
                            <small>Parent last name</small>
                            <input type="text" class="form-control" name="parent_last_name" placeholder="Parent Last name" value="${data.parent_last_name}">
                        </div>
                    </div>
                    <!-- email and username -->
                    <div class="form-row">
                        <div class="col">
                            <small>Email</small>
                            <input type="text" class="form-control"  placeholder="Email" value="${data.email}" disabled>
                        </div>
                        <div class="col">
                            <small>Username</small>
                            <input type="text" class="form-control" name="username" onkeyup="checkuser()" id="username" placeholder="username" value="${data.username}">
                            <span id="usernameError"></span>
                        </div>
                    </div>

                    <!-- password and gender -->
                    <div class="form-row">
                        <div class="col">
                            <small>Password</small>
                            <input type="text" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="col">
                            <small>Course or Category</small>
                            <select name="category" id="category" class="form-control" required>
                                '<option value="" >Select Categary</option>
                                '<option value="beginner"  >Beginner</option>
                                '<option value="intermediate">Intermediate</option>
                                '<option value="advanced" >Advanced</option>'
                            </select>
                        </div>
                    </div>
                    <!--  username -->
                    <div class="form-row">
                        <div class="col">
                            <small>Gender</small>
                            <select name="gender" class="form-control input-field" id="gender">
                                <option selected="" value="">Choose...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="transgender">Transgender</option>
                                <option value="Prefer Not To Say">
                                Prefer Not To Say
                                </option>
                            </select>
                        </div>
                        <div class="col">
                            <small>DOB</small>
                            <input type="date" name="birthday_date" class="form-control" value="${data.birthday_date}">
                        </div>
                    </div>
                     <div class="form-row">
                        <div class="col">
                            <small>Mobile Number</small>
                            <input type="text" class="form-control" placeholder="Phone Number" value="${data.mobile}" disabled>
                        </div>
                        <div class="col">
                        <br>
                            <input type="tel" class="form-control inp" onkeyup="validateNumber()" id="phoneNumber">
                            <span id="phoneNumberError"></span>
                        </div>
                    </div>
                     <div class="form-row">
                        <div class="col">
                            <button type="button" onclick="save_changes()" on class="btn btn-success form-control mt-3">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            `;

            $("#editUserBody").empty();
            $("#editUserBody").append(edit);
            let category = data.category.toLowerCase()
            let gender = data.gender.toLowerCase()
            $(`#category option[value="${category}"]`).attr("selected", true);
            $(`#gender option[value="${gender}"]`).attr("selected", true);
            intl_tel_input();
        }

        function checkuser() {
            username = $("#username").val();
            user_id = $("#user_id").val();
            // console.log(username + " " + user_id);
            // console.log("The text has been changed." + username.length);
            if (username.length >= 4) {
                var user = new FormData();
                user.append("username", username);
                user.append("user_id", user_id);
                user.append("action", "checkUsernameForEdit");
                var status;
                $.ajax({
                    async: false,
                    url: "a.php",
                    type: "POST",
                    dataType: "script",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: user,
                    success: function(data) {
                        // console.log(data)
                        if (data == 1) {
                            $("#usernameError").html(
                                "<span class='text-success'><small>✶ Username Available</small></span>"
                            );
                            status = true;
                        } else {
                            $("#usernameError").html(
                                "<span class='text-danger'><small>✶ all ready taken</small></span>"
                            );
                            // console.log("all ready taken");
                            status = false;
                        }
                    },
                });
                return status;
            } else {
                $("#usernameError").html(
                    "<span class='text-danger'><small>✶ Username must 4 character</small></span>"
                );
                return false;
            }
        }

        function search() {
            sortby = $("input[type='radio'][name='sortby']:checked").val();
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchName");
            filter = input.value.toUpperCase();
            table = document.getElementById("allUsername");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[sortby];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function save_changes() {
            if (!checkuser() || !validateNumber()) {
                if (!checkuser()) {
                    alert("please enter valid username")
                }
                if (!validateNumber()) {
                    alert("please enter valid moblie number")
                }
            } else {
                var data = $("#edit-user-form").serializeArray().reduce(function(obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {});

                if (data.category == "beginner") {
                    data.course_id = 1;
                }
                if (data.category == "intermediate") {
                    data.course_id = 2;
                }
                if (data.category == "advanced") {
                    data.course_id = 3;
                }

                if (validateNumber()) {
                    if ($("#phoneNumber").val() != "") {
                        data.mobile = $("#phoneNumber").intlTelInput("getNumber");
                    }
                }


                data.action = "edit_user_details";
                console.log(data);
                $.post("a.php", data)
                    .done(function(resultData) {
                        alert(resultData);
                        location.reload();
                    });
            }
        }
    </script>
</body>

</html>
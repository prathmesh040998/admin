<?php
include_once("db.php");
$students = getStudents();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>All Sessions</title>
</head>
<div class="container mt-5">
    <div class="form-group">
        <label>Search Email</label>
        <input type="text" id="searchName" placeholder="Enter username" class="form-control" onkeyup="search()">
    </div>
    <div class="col-sm">
        <table class="table" id="allUsername">
            <thead>
                <tr>
                    <th scope="col">Email</th>
                    <th scope="col">Name</th>
                    <th scope="col">New Password</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($students as $student) {
                    echo "<tr>";
                    echo "<td>$student->email</td>";
                    echo "<td>" . ucfirst($student->first_name) . " "  . ucfirst($student->last_name) . "</td>";
                    echo '<td>
                            <form class="new-password-form">
                                <input type="hidden" name="user_id" value="' . $student->user_id . '"  class="form-control">
                                <div class="input-group mb-3"> 
                                    <input type="text" name="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1" required>
                                    <span class="input-group-text p-0"><button type="submit" class="btn btn-sm  btn-success">Success</button></span>
                                </div>
                            </form>

                        </td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<body>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function search() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchName");
            filter = input.value.toUpperCase();
            table = document.getElementById("allUsername");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
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
        $(".new-password-form").submit(function(event) {
            event.preventDefault();
            var data = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            console.log(data);

            $.post("db.php", {
                    user_id: data.user_id,
                    password: data.password,
                    change_password: "change_password",
                })
                .done(function(data) {
                    alert(data);
                    $(".new-password-form").trigger('reset');
                });


        });
    </script>
</body>

</html>
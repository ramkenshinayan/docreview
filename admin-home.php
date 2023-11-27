<?php
session_start();
include("includes/admin.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="SLU Document Review Tracker">
  <title>SLU Document Review Tracker</title>

  <link rel="icon" type="image/png" href="assets/slu_logo.png">

  <!-- MAIN CSS -->
  <link href="resources/css/user-home.css" rel="stylesheet">
  <link href="resources/css/admin-home.css" rel="stylesheet">
</head>

<body>
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="assets/slu_logo.png" alt="logo">
        </span>
        <div class="text header-text">
          <span class="name">Saint Louis University</span>
          <span class="task">Document Review Tracker</span>
        </div>
      </div>
      <ion-icon name="chevron-forward-outline" class="toggle"></ion-icon>
    </header>
    <div class="menu-bar">
      <div class="menu">
        <ul class="menu-links">
          <li class="nav-link">
            <a href="#home">
              <ion-icon name="home-outline"></ion-icon>
              <span class="text nav-text">Home</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#manage">
              <ion-icon name="people-outline"></ion-icon>
              <span class="text nav-text">Manage Users</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="bottom-content">
        <li class="">
          <a href="includes/logout.php">
            <ion-icon name="log-out-outline"></ion-icon>
            <span class="text nav-text">Logout</span>
          </a>
        </li>
        <li class="mode">
          <div class="moon-sun">
            <ion-icon name="moon-outline" class="moon"></ion-icon>
            <ion-icon name="sunny-outline" class="sun"></ion-icon>
          </div>
          <span class="mode-text text">Dark Mode</span>
          <div class="toggle-switch">
            <span class="switch"></span>
          </div>
        </li>
      </div>
    </div>
  </nav>

  <section class="home" id="home">
    <div class="top">
      <div class="profile-details">
        <img src="assets/slu_logo.png" alt="">
        <span class="user_name">
          <?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?>
          <ion-icon name="radio-button-on-outline" class="profile-icon"></ion-icon>
      </div>
    </div>

    <div class="home-content">
      <div class="overview">
        <div class="title">
          <ion-icon name="bar-chart-outline" class="content-icon"></ion-icon>
          <span class="text">Home</span>
        </div>
        <div class="boxes">
          <div class="box box1">
            <ion-icon name="people-outline" class="box-icon"></ion-icon>
            <span class="text">Total Users</span>
            <span class="number" id="total-users"><?php echo $totalUsers ?></span>
          </div>
          <div class="box box2">
            <ion-icon name="radio-button-on-outline" class="box-icon"></ion-icon>
            <span class="text">Online Users</span>
            <span class="number" id="online-users"><?php echo $onlineUsers ?></span>
          </div>
          <div class="box box3">
            <ion-icon name="radio-button-off-outline" class="box-icon"></ion-icon>
            <span class="text">Offline Users</span>
            <span class="number" id="offline-users"><?php echo $offlineUsers ?></span>
          </div>
        </div>

        <div class="user-list">
          <div class="title">
            <ion-icon name="people-outline" class="content-icon"></ion-icon>
            <span class="text">List of Users</span>
          </div>
          <div class="user-list-data">
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Password</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>";
                    echo "<td>" . $row["firstName"] . "</td>";
                    echo "<td>" . $row["lastName"] . "</td>";
                    echo "<td>" . $row["role"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>";
                    echo "<button class='update-user-btn' data-email='" . $row["email"] . "'>Update</button>";
                    echo "<button class='delete-user-btn' data-email='" . $row["email"] . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";                }
                ?>
            </tbody>
        </table>
        </div>
        <button id="addUserBtn">+</button>
      </div>
  </section>
  <section class="manage" id="manage">
  </section>

  <script>
      document.addEventListener('DOMContentLoaded', function () {
        //Add User
        document.getElementById('addUserBtn').addEventListener('click', function () {
        showAddUserForm();
        });

    function showAddUserForm() {
        let addUserForm = `
            <tr id="addUserRow">
                <td><input type="text" name="newEmail" placeholder="Email"></td>
                <td><input type="text" name="newPassword" placeholder="Password"></td>
                <td><input type="text" name="newFirstName" placeholder="First Name"></td>
                <td><input type="text" name="newLastName" placeholder="Last Name"></td>
                <td>
                    <select name="newRole">
                        <option value="Admin">Admin</option>
                        <option value="Requester">Requester</option>
                        <option value="Reviewer">Reviewer</option>
                    </select>
                </td>
                <td>Offline</td>
                <td>
                    <button class='save-user-btn' id="saveNewUserBtn">Save</button>
                    <button class='cancel-update-btn' id="cancelNewUserBtn">Cancel</button>
                </td>
            </tr>`;

        document.querySelector('.user-list-data tbody').insertAdjacentHTML('beforeend', addUserForm);

        document.getElementById('saveNewUserBtn').addEventListener('click', function () {
            saveNewUser();
        });

        document.getElementById('cancelNewUserBtn').addEventListener('click', function () {
            cancelAddUser();
        });
    }

    function saveNewUser() {
        let newEmail = document.querySelector('input[name="newEmail"]').value;
        let newPassword = document.querySelector('input[name="newPassword"]').value;
        let newFirstName = document.querySelector('input[name="newFirstName"]').value;
        let newLastName = document.querySelector('input[name="newLastName"]').value;
        let newRole = document.querySelector('select[name="newRole"]').value;

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'includes/admin.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    alert(xhr.responseText);
                    if (xhr.responseText.includes("Error: User with this email already exists.")) {
                        // Display error message to the user
                        alert("Error: User with this email already exists.");
                    } else {
                        location.reload();
                    }
                }
            }
        };
        xhr.send('add_user=true&email=' + encodeURIComponent(newEmail) +
            '&password=' + encodeURIComponent(newPassword) +
            '&firstName=' + encodeURIComponent(newFirstName) +
            '&lastName=' + encodeURIComponent(newLastName) +
            '&role=' + encodeURIComponent(newRole));
    }

    function cancelAddUser() {
        let addUserRow = document.getElementById('addUserRow');
        addUserRow.parentNode.removeChild(addUserRow);
    }

        // Update User
        document.addEventListener('click', function (event) {
        if (event.target.classList.contains('update-user-btn')) {
          let row = event.target.closest('tr');
          let originalEmail = row.cells[0].textContent;
          let password = row.cells[1].textContent;
          let firstName = row.cells[2].textContent;
          let lastName = row.cells[3].textContent;
          let role = row.cells[4].textContent;
          let status = row.cells[5].textContent;

          row.innerHTML = `
          <td><input type="text" name="email" value="${originalEmail}"></td>
          <td><input type="text" name="password" value="${password}"></td>
          <td><input type="text" name="firstName" value="${firstName}"></td>
          <td><input type="text" name="lastName" value="${lastName}"></td>
          <td>
              <select name="role" value="${role}">
                  <option value="Admin" ${role === 'Admin' ? 'selected' : ''}>Admin</option>
                  <option value="Requester" ${role === 'Requester' ? 'selected' : ''}>Requester</option>
                  <option value="Reviewer" ${role === 'Reviewer' ? 'selected' : ''}>Reviewer</option>
              </select>
          </td>
          <td>${status}</td>
          <td>
            <button class='save-user-btn'>Save</button>
            <button class='cancel-update-btn'>Cancel</button>
          </td>`;

          row.querySelector('.save-user-btn').addEventListener('click', function () {
          let updatedEmail = row.querySelector('input[name="email"]').value;
          let updatedPassword = row.querySelector('input[name="password"]').value;
          let updatedFirstName = row.querySelector('input[name="firstName"]').value;
          let updatedLastName = row.querySelector('input[name="lastName"]').value;
          let updatedRole = row.querySelector('select[name="role"]').value;

          let xhr = new XMLHttpRequest();
          xhr.open('POST', 'includes/admin.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            location.reload(); 
            }
          };
          xhr.send('update_user=true&original_email=' + encodeURIComponent(originalEmail) +
            '&updated_email=' + encodeURIComponent(updatedEmail) +
            '&password=' + encodeURIComponent(updatedPassword) +
            '&firstName=' + encodeURIComponent(updatedFirstName) +
            '&lastName=' + encodeURIComponent(updatedLastName) +
            '&role=' + encodeURIComponent(updatedRole));
          });

          row.querySelector('.cancel-update-btn').addEventListener('click', function () {
          location.reload(); 
          });
        }
        });

        // Delete User
        document.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-user-btn')) {
          const email = event.target.getAttribute('data-email');
          if (confirm('Are you sure you want to delete this user?')) {
          let xhr = new XMLHttpRequest();
          xhr.open('POST', 'includes/admin.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            location.reload();
            }
          };
          xhr.send('delete_user=' + encodeURIComponent(email));
          }
        }
        });
      });
  </script>
  <!-- CUSTOM JS -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="resources/js/user-home.js"></script>
  <script src="resources/js/admin-home.js"></script>
</body>

</html>

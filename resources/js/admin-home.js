(function () {
	const body = document.querySelector("body"),
	sidebar = body.querySelector(".sidebar"),
	toggle = body.querySelector(".toggle"),
	modeSwitch = body.querySelector(".mode"),
	modeText = body.querySelector(".mode-text");

	document.addEventListener('DOMContentLoaded', function () {
		//Add User
		document.getElementById('addUserBtn').addEventListener('click', function () {
			showAddUserForm();
		});

		function showAddUserForm() {
			let addUserForm = `
				<tr id="addUserRow">
					<td><input type="text" name="newEmail" placeholder="Email"></td>
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

			document.querySelector('.container-table tbody').insertAdjacentHTML('beforeend', addUserForm);

			document.getElementById('saveNewUserBtn').addEventListener('click', function () {
				saveNewUser();
			});

			document.getElementById('cancelNewUserBtn').addEventListener('click', function () {
				cancelAddUser();
			});
		}

		function saveNewUser() {
			let newEmail = document.querySelector('input[name="newEmail"]').value;
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
							alert("Error: User with this email already exists.");
						} else {
							location.reload();
						}
					}
				}
			};
			xhr.send('add_user=true&email=' + encodeURIComponent(newEmail) +
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
				let firstName = row.cells[1].textContent;
				let lastName = row.cells[2].textContent;
				let role = row.cells[3].textContent;

				row.innerHTML = `
				<td><input type="text" name="email" value="${originalEmail}"></td>
				<td><input type="text" name="firstName" value="${firstName}"></td>
				<td><input type="text" name="lastName" value="${lastName}"></td>
				<td>
					<select name="role">
						<option value="Admin" ${role === 'Admin' ? 'selected' : ''}>Admin</option>
						<option value="Requester" ${role === 'Requester' ? 'selected' : ''}>Requester</option>
						<option value="Reviewer" ${role === 'Reviewer' ? 'selected' : ''}>Reviewer</option>
					</select>
				</td>
				<td>
					<button class='save-user-btn'>Save</button>
					<button class='cancel-update-btn'>Cancel</button>
				</td>`;

				row.querySelector('.save-user-btn').addEventListener('click', function () {
					let updatedEmail = row.querySelector('input[name="email"]').value;
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
})();

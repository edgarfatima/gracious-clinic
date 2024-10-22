<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Employee</title>

    @vite([ 
    'resources/scss/admin/adminemployee.scss', 
    'resources/scss/sidebar.scss', 
    'resources/scss/footer.scss', 
    'resources/js/sidebar.js', 
    'resources/js/admin/employee.js',
    'resources/scss/modal.scss'
    ])
</head>

<body>
    <div class="container">

        @include('/partials/sidebar')
        <div class="content">
            <div class="content-header">
                <div class="content-header-heading">
                    <h1>Admin | <span>Employees</span></h1>
                </div>
            </div>
            <div class="content-body">
                <div class="wrapper-body">
                    <div class="content-body-header">
                        <div class="search">
                            <input type="text" placeholder="Search">
                                <select name="filer" id="filer">
                                    <option value="">Filter By</option>
                                    <option value="">Role | Admin</option>
                                    <option value="">Role | Staff</option>
                                    <option value="">Role | Doctor</option>
                                    <option value="">Status | Active</option>
                                    <option value="">Status | Inactive</option>
                                </select>
                        </div>
                        <div class="add-employee">
                            <button id="add-btn">
                                Add Employee
                            </button>
                        </div>
                    </div>
                    <div class="scrollable-table">
                        <table class="employee-table table-sortable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                
                            </tbody>
                    
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="addModal">
            <div class="modal">
                <div class="form-header">
                    <div id="add-close-modal">
                        X
                    </div>
                </div>
                <div class="form-content">
                    <h2>Add Employee</h2>
                    <form id="addForm" method="POST">
                        @csrf
                        <div id="add-container" class="form-control">
                            <ul id="validation-errors" class="text-danger">

                            </ul>
                        </div>
                        <div class="form-control">
                            <input name="first_name" id="addFirstname" type="text" placeholder="First Name" required>
                        </div>
                        <div class="form-control">
                            <input name="last_name" id="addLastname" type="text" placeholder="Last Name" required>
                        </div>
                        <div class="form-control">
                            <input name="email" id="addEmail" type="email" placeholder="Email" required>
                        </div>
                        <div class="form-control">
                            <input name="number" id="addNumber" type="text"  placeholder="Phone Number" required>
                        </div>
                        <div class="form-control">
                            <select name="role" id="addRole" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="staff">Staff</option>
                        </select>
                        </div>
                        <div class="form-control">
                            <select name="status" id="addStatus" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            </select>
                            </div>
                            <div class="form-control">
                                <input name="password" id="password" type="password"  placeholder="Password" required>
                            </div>

                            <div class="form-control">
                                <input name="confirm_password" id="confirm_password" type="password"  placeholder="Confirm Password" required>
                            </div>
                            <div class="form-control">
                                <button type="submit" class="submit-btn">Confirm</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>

        <div id="updateModal">
            <div class="modal">
                <div class="form-header">
                    <div id="update-close-modal">
                        X
                    </div>
                </div>
                <div class="form-content">
                    <h2>Edit Employee</h2>
                    <form id="updateForm" method="POST">
                        @csrf

                        <div id="validation-error" class="form-control">
                            <ul id="validation-errors" class="text-danger">

                            </ul>
                        </div>

                        <input type="hidden" name="id" id="edit-employee-id" value="">
                        <div class="form-control">
                            <input name="first_name" id="updateFirstname" type="text" placeholder="First Name" required>
                        </div>
                        <div class="form-control">
                            <input name="last_name" id="updateLastname" type="text" placeholder="Last Name" required>
                        </div>
                        <div class="form-control">
                            <input name="email" id="updateEmail" type="email" placeholder="Email" required>
                        </div>
                        <div class="form-control">
                            <input name="number" id="updateNumber" type="text"  placeholder="Phone Number" required>
                        </div>
                        <div class="form-control">
                            <select name="role" id="updateRole" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="staff">Staff</option>
                        </select>
                        </div>
                        <div class="form-control">
                            <select name="status" id="updateStatus" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            </select>
                            </div>
                            <div class="form-control">
                                <button type="submit" class="submit-btn">Confirm</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
 /**
 * Sorts a HTML table.
 *
 * @param {HTMLTableElement} table The table to sort
 * @param {number} column The index of the column to sort
 * @param {boolean} asc Determines if the sorting will be in ascending
 */
function sortTableByColumn(table, column, asc = true) {
	const dirModifier = asc ? 1 : -1;
	const tBody = table.tBodies[0];
	const rows = Array.from(tBody.querySelectorAll("tr"));

	// Sort each row
	const sortedRows = rows.sort((a, b) => {
		let aColText = a.querySelector(`td:nth-child(${column + 1})`).textContent.trim();
		let bColText = b.querySelector(`td:nth-child(${column + 1})`).textContent.trim();

		// If both are numbers, convert them to integers before comparing
		if (!isNaN(aColText) && !isNaN(bColText)) {
			aColText = parseInt(aColText);
			bColText = parseInt(bColText);
		}

		return aColText > bColText ? (1 * dirModifier) : (-1 * dirModifier);
	});

	// Remove all existing TRs from the table
	while (tBody.firstChild) {
		tBody.removeChild(tBody.firstChild);
	}

	// Re-add the newly sorted rows
	tBody.append(...sortedRows);

	// Remember how the column is currently sorted
	table.querySelectorAll("th").forEach(th => th.classList.remove("th-sort-asc", "th-sort-desc"));
	table.querySelector(`th:nth-child(${column + 1})`).classList.toggle("th-sort-asc", asc);
	table.querySelector(`th:nth-child(${column + 1})`).classList.toggle("th-sort-desc", !asc);
}

// Add click listeners to the table headers (except for the 'Action' column)
document.querySelectorAll(".table-sortable th").forEach((headerCell, index) => {
	// Exclude sorting for the 'Action' column (e.g., the last column)
	if (index < document.querySelectorAll(".table-sortable th").length - 1) {
		headerCell.addEventListener("click", () => {
			const tableElement = headerCell.parentElement.parentElement.parentElement;
			const headerIndex = Array.prototype.indexOf.call(headerCell.parentElement.children, headerCell);
			const currentIsAscending = headerCell.classList.contains("th-sort-asc");

			sortTableByColumn(tableElement, headerIndex, !currentIsAscending);
		});
	}
});
 
$(document).ready(function() {
        $('#addNumber').on('input', function() {
            // Remove non-numeric characters
            $(this).val($(this).val().replace(/\D/g,''));

            // Limit to 11 digits
            var maxDigits = 11;
            if ($(this).val().length > maxDigits) {
                $(this).val($(this).val().slice(0, maxDigits));
            }
        });

        $('#updateNumber').on('input', function() {
            // Remove non-numeric characters
            $(this).val($(this).val().replace(/\D/g,''));

            // Limit to 11 digits
            var maxDigits = 11;
            if ($(this).val().length > maxDigits) {
                $(this).val($(this).val().slice(0, maxDigits));
            }
        });
    });
</script>
</body>

</html>
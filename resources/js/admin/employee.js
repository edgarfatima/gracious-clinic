import axios from 'axios';
document.addEventListener('DOMContentLoaded', function () {
    // Place all your existing JavaScript code inside this function

    

    // Function to fetch employees and populate the table
    function fetchEmployees() {
        axios.post('/admin/employee/populate')
            .then(response => {
                const employees = response.data;
                const tableBody = document.getElementById('employeeTableBody');
                tableBody.innerHTML = '';
                employees.forEach(employee => {
                    const createdAt = new Date(employee.created_at).toISOString().split('T')[0];
                    const row = `
                        <tr>
                            <td>${employee.id}</td>
                            <td>${employee.first_name} ${employee.last_name}</td>
                            <td>${employee.email}</td>
                            <td>${employee.number}</td>
                            <td>${employee.role}</td>
                            <td>${employee.status}</td>
                            <td>${createdAt}</td>
                            <td>
                                <button id="edit-btn-${employee.id}" data-id="${employee.id}" class="edit-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                                        <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('There was an error fetching the employees!', error);
            });
    }

    // Fetch the employee data initially
    fetchEmployees();
    // Add Button click handler

    $(document).on('click', '#add-btn', function () {
        // Clear form fields and any previous error messages
        $('#addForm')[0].reset();
        $('#validation-errors').empty();
        $('.success-message').hide();

        // Show the modal
        $('#addModal').fadeIn().css("display", "flex");
    });

    // Edit button click handler
    $(document).on('click', '.edit-btn', function () {
        const employeeId = $(this).data('id');

        axios.get(`/admin/employee/fetch/${employeeId}`)
            .then(response => {
                const data = response.data;

                $('#edit-employee-id').val(data.id);
                $('#updateFirstname').val(data.first_name);
                $('#updateLastname').val(data.last_name);
                $('#updateEmail').val(data.email);
                $('#updateNumber').val(data.number);
                $('#updateStatus').val(data.status);
                $('#updateRole').val(data.role);

                // Show the modal
                $('#updateModal').fadeIn().css("display", "flex");
            })
            .catch(error => {
                console.error('Error fetching employee data:', error);
            });
    });

    // Close modal
    $('#add-close-modal').click(function () {
        $('#addModal').fadeOut();
    });
    $('#update-close-modal').click(function () {
        $('#updateModal').fadeOut();
    });

    $('#addForm').submit(function (e) {
        e.preventDefault();
    
        const formData = new FormData(this);
    
        axios.post('/admin/employee/store', formData)
            .then(response => {
                console.log('Employee added successfully:', response.data);
    
                // Fetch updated employees and close modal
                fetchEmployees();
                $('#addModal').fadeOut();
    
                // Display success alert
                alert('Employee added successfully!');
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
    
                    const divContainer = document.getElementById('validation-error');
                    const errorContainer = document.getElementById('validation-errors');
                    if (errorContainer) {
                        errorContainer.innerHTML = '';
    
                        // Display new validation errors
                        Object.values(errors).forEach(errorList => {
                            errorList.forEach(errorMessage => {
                                const errorItem = document.createElement('li');
                                errorItem.textContent = errorMessage;
                                errorContainer.appendChild(errorItem);
                            });
                        });
    
                        // Display error container if hidden
                        divContainer.style.display = 'block';
                    } else {
                        console.error('Validation error container not found.');
                    }
                } else {
                    console.error('Error adding employee:', error);
                }
            });
    });

    // Update form submission
    $('#updateForm').submit(function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        axios.post('/admin/employee/update', formData)
            .then(response => {
                console.log('Employee updated successfully:', response.data);

                // Fetch updated employees and close modal
                fetchEmployees();
                $('#updateModal').fadeOut();

                alert('Employee updated successfully!');
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;

                    const divContainer = document.getElementById('validation-error');
                    const errorContainer = document.getElementById('validation-errors');
                    if (errorContainer) {
                        errorContainer.innerHTML = '';

                        // Display new validation errors
                        Object.values(errors).forEach(errorList => {
                            errorList.forEach(errorMessage => {
                                const errorItem = document.createElement('li');
                                errorItem.textContent = errorMessage;
                                errorContainer.appendChild(errorItem);
                            });
                        });

                        // Display error container if hidden
                        divContainer.style.display = 'block';
                    } else {
                        console.error('Validation error container not found.');
                    }
                } else {
                    console.error('Error updating employee data:', error);
                }
            });
    });
});

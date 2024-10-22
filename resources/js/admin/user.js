import axios from 'axios';

// Function to fetch employees and populate the table
function fetchUsers() {
    axios.get('/admin/user/populate')
        .then(response => {
            const employees = response.data;
            const tableBody = document.getElementById('userTableBody');
            tableBody.innerHTML = ''; // Clear table

            employees.forEach(user => {
                const createdAt = new Date(user.created_at).toISOString().split('T')[0];
                const row = `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.first_name} ${user.last_name}</td>
                        <td>${user.email}</td>
                        <td>${user.number}</td>
                        <td>${user.address}</td>
                        <td>${user.status}</td>
                        <td>${createdAt}</td>
                        <td><button id="edit-btn-${user.id}" data-id="${user.id}" class="edit-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg></button>
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

$(function() {
    // Fetch the employee data and populate the table when the page loads
    fetchUsers();

    // Edit button click handler (using event delegation)
    $(document).on('click', '.edit-btn', function () {
        const userId = $(this).data('id');

        // Fetch employee data using Axios
        axios.get(`/admin/user/fetch/${userId}`)
             .then(response => {
                 const data = response.data;

                 // Populate the form fields with the fetched data
                 $('#edit-user-id').val(data.id);
                 $('#updateFirstname').val(data.first_name);
                 $('#updateLastname').val(data.last_name);
                 $('#updateEmail').val(data.email);
                 $('#updateNumber').val(data.number);
                 $('#updateStatus').val(data.status);

                 // Show the modal
                 $('#updateModal').fadeIn().css("display", "flex");
             })
             .catch(error => {
                 console.error('Error fetching employee data:', error);
             });
    });

    // Close modal button click handler
    $('#close-modal').click(function() {
        $('#updateModal').fadeOut();
    });

    // Update form submission handler
    $('#updateForm').submit(function (e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        axios.post('/admin/user/update', formData)
            .then(response => {
                console.log('Employee updated successfully:', response.data);

                fetchEmployees();

                $('#updateModal').fadeOut();
            })
            .catch(error => {
                console.error('Error updating employee data:', error);
            });
    });
});

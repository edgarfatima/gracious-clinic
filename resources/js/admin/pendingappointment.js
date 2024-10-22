import axios from 'axios';
document.addEventListener('DOMContentLoaded', function () {

function fetchAppointments() {
    axios.post('/staff/appointment/pending/populate')
        .then(response => {
            const appointments = response.data;
            const tableBody = document.getElementById('appointmentTableBody');
            tableBody.innerHTML = '';
            appointments.forEach(appointment => {
                const createdAt = new Date(appointment.created_at).toISOString().split('T')[0];
                const row = `
                    <tr>
                        <td>${appointment.id}</td>
                        <td>${appointment.name}</td>
                        <td>${appointment.appointment_date}</td>
                        <td>${appointment.status}</td>
                        <td>${createdAt}</td>
                        <td>
                            <button id="accept-btn" data-id="${appointment.id}" class="accept-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>
                            </button>
                            <button id="reject-btn" data-id="${appointment.id}" class="reject-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('There was an error fetching the pending appointments!', error);
        });
}

fetchAppointments();
$(document).on('click', '#accept-btn', function () {
    const appointmentId = $(this).data('id');

        axios.get(`/staff/appointment/fetch/${appointmentId}`)
            .then(response => {
                const data = response.data;

                $('#accept-appointment-id').val(data.id);

                // Show the modal
                $('#acceptModal').fadeIn().css("display", "flex");
            })
            .catch(()=> {
                alert('Error fetching appointment data');
            });
});

// Close modal
$('#accept-close-modal').click(function () {
    $('#acceptModal').fadeOut();
});

$('#acceptForm').submit(function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    axios.post('/staff/appointment/confirm', formData)
    .then(() => {
        fetchAppointments();
        $('#acceptModal').fadeOut();

        // Display success alert
        alert('Appointment accepted!');
    })
    .catch(() => {
        alert('Error accepting appointment');
    });
});

$(document).on('click', '#reject-btn', function () {
    const appointmentId = $(this).data('id');

        axios.get(`/staff/appointment/fetch/${appointmentId}`)
            .then(response => {
                const data = response.data;

                $('#reject-appointment-id').val(data.id);

                // Show the modal
                $('#rejectModal').fadeIn().css("display", "flex");
            })
            .catch(()=> {
                alert('Error fetching appointment data');
            });
});

// Close modal
$('#reject-close-modal').click(function () {
    $('#rejectModal').fadeOut();
});

$('#rejectForm').submit(function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    axios.post('/staff/appointment/reject', formData)
    .then(() => {
        fetchAppointments();
        $('#rejectModal').fadeOut();

        // Display success alert
        alert('Appointment rejected!');
    })
    .catch(() => {
        alert('Error accepting appointment');
    });
});
});
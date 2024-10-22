import axios from 'axios';
document.addEventListener('DOMContentLoaded', function () {

function fetchAppointments() {
    axios.post('/staff/appointment/populate')
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
});
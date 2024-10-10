const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
const hours = Array.from({ length: 96 }, (_, i) => {
    const hour = String(Math.floor(i / 4)).padStart(2, '0');
    const minutes = String((i % 4) * 15).padStart(2, '0');
    return `${hour}:${minutes}`;
});
let dayToDuplicate = '';

function addDay() {
    const daysContainer = document.getElementById('days-container');
    days.forEach(day => {
        const dayDiv = document.createElement('div');
        dayDiv.classList.add('day-row');
        dayDiv.innerHTML = `
            <label class="day-label">
                <input type="checkbox" class="day-checkbox" onclick="toggleDay('${day}')"> ${day}
            </label>
            <div id="${day}-content" class="day-content no-disponible">
                <div class="interval-row">
                    <select class="form-control">
                        ${hours.map(hour => `<option value="${hour}">${hour}</option>`).join('')}
                    </select>
                    <span>-</span>
                    <select class="form-control">
                        ${hours.map(hour => `<option value="${hour}">${hour}</option>`).join('')}
                    </select>
                    <img src="./img/03config_disponibilidad/x0.svg" alt="Remove Interval" onclick="removeInterval(this)" class="remove-icon">
                </div>
            </div>
            <div class="action-icons">
                <img src="./img/03config_disponibilidad/plus0.svg" alt="Add Interval" onclick="addInterval('${day}')" class="action-icon">
                <img src="./img/03config_disponibilidad/copy0.svg" alt="Duplicate Day" onclick="openDuplicateModal('${day}')" class="action-icon">
            </div>
        `;
        daysContainer.appendChild(dayDiv);
    });
}

function toggleDay(day) {
    const dayContent = document.getElementById(`${day}-content`);
    if (dayContent.classList.contains('no-disponible')) {
        dayContent.classList.remove('no-disponible');
        dayContent.innerHTML = '';
        dayContent.appendChild(createIntervalRow(day, "09:00:00", "14:00:00"));  // Primer intervalo predeterminado
    } else {
        dayContent.classList.add('no-disponible');
        dayContent.innerHTML = 'No disponible';
    }
}

function createIntervalRow(day, startHour = "00:00:00", endHour = "00:00:00") {
    const intervalRow = document.createElement('div');
    intervalRow.classList.add('interval-row');
    intervalRow.innerHTML = `
        <div class="interval">
            <select class="form-control">
                ${hours.map(hour => `<option value="${hour}" ${hour === startHour.substring(0,5) ? 'selected' : ''}>${hour}</option>`).join('')}
            </select>
            <span>-</span>
            <select class="form-control">
                ${hours.map(hour => `<option value="${hour}" ${hour === endHour.substring(0,5) ? 'selected' : ''}>${hour}</option>`).join('')}
            </select>
            <img src="./img/03config_disponibilidad/x0.svg" alt="Remove Interval" onclick="removeInterval(this)" class="remove-icon">
        </div>
    `;
    return intervalRow;
}

function removeDay(day) {
    const dayDiv = document.querySelector(`.day-row input[onclick="toggleDay('${day}')"]`).closest('.day-row');
    dayDiv.remove();
}

function removeInterval(button) {
    button.closest('.interval-row').remove();
}

function openDuplicateModal(day) {
    dayToDuplicate = day;
    const duplicateDaysContainer = document.getElementById('duplicate-days-container');
    duplicateDaysContainer.innerHTML = '';
    days.forEach(d => {
        if (d !== dayToDuplicate) {
            const dayCheckbox = document.createElement('div');
            dayCheckbox.classList.add('form-check');
            dayCheckbox.innerHTML = `
                <input class="form-check-input" type="checkbox" value="${d}" id="duplicate-${d}">
                <label class="form-check-label" for="duplicate-${d}">${d}</label>
            `;
            duplicateDaysContainer.appendChild(dayCheckbox);
        }
    });
    $('#duplicateModal').modal('show'); // Asegurarse de que el modal se abra
}

function confirmDuplicate() {
    const selectedDays = [];
    document.querySelectorAll('#duplicate-days-container .form-check-input').forEach(input => {
        if (input.checked) {
            selectedDays.push(input.value);
        }
    });

    const originalDayContent = document.getElementById(`${dayToDuplicate}-content`);
    const intervals = Array.from(originalDayContent.getElementsByClassName('interval-row'));

    selectedDays.forEach(day => {
        let dayDiv = document.querySelector(`.day-row input[onclick="toggleDay('${day}')"]`);
        
        // Si no existe el día, lo creamos
        if (!dayDiv) {
            addDayForDuplication(day);
            dayDiv = document.querySelector(`.day-row input[onclick="toggleDay('${day}')"]`);
        }

        const dayDivActualizado = dayDiv.closest('.day-row');
        const dayContent = dayDivActualizado.querySelector('.day-content');
        dayContent.innerHTML = ''; // Limpiar contenido

        intervals.forEach(interval => {
            const startHour = interval.querySelector('select').value;
            const endHour = interval.querySelectorAll('select')[1].value;
            dayContent.appendChild(createIntervalRow(day, startHour, endHour));
        });

        dayContent.classList.remove('no-disponible');
        dayDivActualizado.querySelector('.day-checkbox').checked = true;  // Activar el checkbox del día duplicado
    });

    $('#duplicateModal').modal('hide');
}

function addDayForDuplication(day) {
    const daysContainer = document.getElementById('days-container');
    const dayDiv = document.createElement('div');
    dayDiv.classList.add('day-row');
    dayDiv.innerHTML = `
        <label class="day-label">
            <input type="checkbox" class="day-checkbox" onclick="toggleDay('${day}')"> ${day}
        </label>
        <div id="${day}-content" class="day-content no-disponible"></div>
        <div class="action-icons">
            <img src="./img/03config_disponibilidad/plus0.svg" alt="Add Interval" onclick="addInterval('${day}')" class="action-icon">
            <img src="./img/03config_disponibilidad/copy0.svg" alt="Duplicate Day" onclick="openDuplicateModal('${day}')" class="action-icon">
        </div>
    `;
    daysContainer.appendChild(dayDiv);
}

function addInterval(day) {
    const dayContent = document.getElementById(`${day}-content`);
    const intervals = dayContent.getElementsByClassName('interval-row');
    
    // Si no hay franjas horarias, añade la primera franja predeterminada (09:00 a 14:00)
    if (intervals.length === 0) {
        dayContent.appendChild(createIntervalRow(day, "09:00:00", "14:00:00"));
    }
    // Si ya hay una franja horaria, añade la segunda predeterminada (16:00 a 20:00)
    else if (intervals.length === 1) {
        dayContent.appendChild(createIntervalRow(day, "16:00:00", "20:00:00"));
    }
    // Para franjas adicionales, las deja vacías para que el usuario las configure
    else {
        dayContent.appendChild(createIntervalRow(day, "00:00", "00:00")); // Vacío para franjas adicionales
    }
}

// Inicializar días
addDay();

// Agregar evento submit al formulario
document.getElementById('horario-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    
    // Aquí puedes recorrer todos los días y sus intervalos para añadirlos a formData
    days.forEach(day => {
        const dayContent = document.getElementById(`${day}-content`);
        if (!dayContent.classList.contains('no-disponible')) {
            const intervals = Array.from(dayContent.getElementsByClassName('interval-row'));
            intervals.forEach((interval, index) => {
                const startHour = interval.querySelector('select').value;
                const endHour = interval.querySelectorAll('select')[1].value;
                formData.append(`${day}[${index}][start]`, startHour);
                formData.append(`${day}[${index}][end]`, endHour);
            });
        }
    });

    fetch('guardar_horario.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            // Redirigir al usuario a listado-servicios.php
            window.location.href = 'configuracion-servicios.html';
        } else {
            // Manejar error si es necesario
            console.error('Error en la solicitud:', response.statusText);
        }
    }).catch((error) => {
        console.error('Error:', error);
    });
});

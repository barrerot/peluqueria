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
        dayDiv.classList.add('form-group', 'day-div');
        dayDiv.innerHTML = `
            <label>
                <input type="checkbox" class="day-checkbox" onclick="toggleDay('${day}')"> ${day}
            </label>
            <div id="${day}-content" class="day-content no-disponible">No disponible</div>
            <img src="./img/03config_disponibilidad/x0.svg" alt="Remove" onclick="removeDay('${day}')" style="cursor: pointer; width: 16px; height: 16px;">
        <img src="./img/03config_disponibilidad/copy0.svg" alt="Duplicate" onclick="openDuplicateModal('${day}')" style="cursor: pointer; width: 16px; height: 16px;">
    <img src="./img/03config_disponibilidad/plus0.svg" alt="Add Interval" onclick="addInterval('${day}')" style="cursor: pointer; width: 16px; height: 16px;">

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
        </div>
        
    `;
    setTimeout(() => {
        const selects = intervalRow.querySelectorAll('select');
        selects.forEach(select => new Choices(select, { shouldSort: false, itemSelectText: '' }));
    }, 0);
    return intervalRow;
}

function removeDay(day) {
    const dayDiv = document.querySelector(`.day-div input[onclick="toggleDay('${day}')"]`).closest('.day-div');
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
        const dayDiv = document.querySelector(`.day-div input[onclick="toggleDay('${day}')"]`).closest('.day-div');
        const dayContent = dayDiv.querySelector('.day-content');
        dayContent.innerHTML = '';
        intervals.forEach(interval => {
            const startHour = interval.querySelector('select').value;
            const endHour = interval.querySelectorAll('select')[1].value;
            dayContent.appendChild(createIntervalRow(day, startHour, endHour));
        });
        dayContent.classList.remove('no-disponible');
        dayDiv.querySelector('.day-checkbox').checked = true;  // Activar el checkbox del día duplicado
    });

    $('#duplicateModal').modal('hide');
}

function addInterval(day) {
    const dayContent = document.getElementById(`${day}-content`);
    if (!dayContent.classList.contains('no-disponible')) {
        const intervals = dayContent.getElementsByClassName('interval-row');
        if (intervals.length === 0) {
            dayContent.appendChild(createIntervalRow(day, "09:00:00", "14:00:00")); // Primer intervalo predeterminado
        } else if (intervals.length === 1) {
            dayContent.appendChild(createIntervalRow(day, "16:00:00", "20:00:00")); // Segundo intervalo predeterminado
        } else {
            dayContent.appendChild(createIntervalRow(day)); // Intervalos adicionales sin valores predeterminados
        }
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

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
                    <select name="${day}[start][]" class="form-control">
                        ${hours.map(hour => `<option value="${hour}">${hour}</option>`).join('')}
                    </select>
                    <span>-</span>
                    <select name="${day}[end][]" class="form-control">
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
        dayContent.appendChild(createIntervalRow(day, "09:00", "14:00")); // Primer intervalo predeterminado
    } else {
        dayContent.classList.add('no-disponible');
        dayContent.innerHTML = 'No disponible';
    }
}

function createIntervalRow(day, startHour = "00:00", endHour = "00:00") {
    const intervalRow = document.createElement('div');
    intervalRow.classList.add('interval-row');
    intervalRow.innerHTML = `
        <div class="interval">
            <select name="${day}[start][]" class="form-control">
                ${hours.map(hour => `<option value="${hour}" ${hour === startHour ? 'selected' : ''}>${hour}</option>`).join('')}
            </select>
            <span>-</span>
            <select name="${day}[end][]" class="form-control">
                ${hours.map(hour => `<option value="${hour}" ${hour === endHour ? 'selected' : ''}>${hour}</option>`).join('')}
            </select>
            <img src="./img/03config_disponibilidad/x0.svg" alt="Remove Interval" onclick="removeInterval(this)" class="remove-icon">
        </div>
    `;
    return intervalRow;
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
    $('#duplicateModal').modal('show');
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
        if (!dayDiv) {
            addDayForDuplication(day);
            dayDiv = document.querySelector(`.day-row input[onclick="toggleDay('${day}')"]`);
        }

        const dayDivActualizado = dayDiv.closest('.day-row');
        const dayContent = dayDivActualizado.querySelector('.day-content');
        dayContent.innerHTML = ''; 

        intervals.forEach(interval => {
            const startHour = interval.querySelector('select').value;
            const endHour = interval.querySelectorAll('select')[1].value;
            dayContent.appendChild(createIntervalRow(day, startHour, endHour));
        });

        dayContent.classList.remove('no-disponible');
        dayDivActualizado.querySelector('.day-checkbox').checked = true;
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
    if (intervals.length === 0) {
        dayContent.appendChild(createIntervalRow(day, "09:00", "14:00"));
    } else if (intervals.length === 1) {
        dayContent.appendChild(createIntervalRow(day, "16:00", "20:00"));
    } else {
        dayContent.appendChild(createIntervalRow(day, "00:00", "00:00"));
    }
}

// Inicializar días
addDay();

// Agregar evento submit al formulario
document.getElementById('horario-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('guardar_horario.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            window.location.href = 'listado-servicios.php';
        } else {
            response.text().then(text => console.error('Error en la solicitud:', text));
        }
    }).catch((error) => {
        console.error('Error:', error);
    });
});

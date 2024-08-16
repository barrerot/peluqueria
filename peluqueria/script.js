$(document).ready(function() {
  var calendar;

  // Mapeo de días de la semana a índices de FullCalendar
  var dayMap = {
    "Lunes": 1,
    "Martes": 2,
    "Miércoles": 3,
    "Jueves": 4,
    "Viernes": 5,
    "Sábado": 6,
    "Domingo": 0
  };

  // Cerrar modal al inicio, si está abierto por alguna razón
  $('#eventModal').hide();

  // Obtener horarios de apertura desde la base de datos
  $.ajax({
    url: 'fetch_horarios.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      var businessHours = data.map(function(horario) {
        return {
          daysOfWeek: [dayMap[horario.dia]],
          startTime: horario.hora_inicio,
          endTime: horario.hora_fin
        };
      });

      var minStartTime = Math.min(...data.map(h => parseInt(h.hora_inicio.split(":")[0])));
      var maxEndTime = Math.max(...data.map(h => parseInt(h.hora_fin.split(":")[0])));

      initializeCalendar(businessHours, minStartTime, maxEndTime);
    },
    error: function() {
      alert('Error al cargar los horarios de apertura');
    }
  });

  function initializeCalendar(businessHours, minStartTime, maxEndTime) {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      locale: 'es',
      nowIndicator: true,
      businessHours: businessHours,
      slotMinTime: minStartTime + ":00:00",
      slotMaxTime: (maxEndTime + 1) + ":00:00",
      slotDuration: "00:15:00",
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      },
      slotLabelFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      },
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      selectable: true,
      events: {
        url: 'fetch_events.php',
        method: 'GET',
        extraParams: function() {
          return {
            negocio_id: 1
          };
        },
        success: function(data) {
          console.log('Eventos cargados:', data);
        },
        failure: function() {
          alert('Error al cargar las citas');
        }
      },
      eventClick: function(info) {
        console.log('Evento clicado:', info.event);
        $('#eventForm')[0].reset();
        $('#eventModal').show();
        $('#clienteNombre').val(info.event.extendedProps.cliente_nombre);
        $('#date').val(moment(info.event.start).format('YYYY-MM-DD'));
        $('#timeRange').val(moment(info.event.start).format('HH:mm') + ' - ' + moment(info.event.end).format('HH:mm'));

        var clienteId = info.event.extendedProps.cliente_id;
        $('#telefono').val(info.event.extendedProps.telefono);
        $('#email').val(info.event.extendedProps.email);

        var services = info.event.extendedProps.services || [];
        services.forEach(function(service) {
          $('input[name="service"][value="' + service.id + '"]').prop('checked', true);
        });

        $('#deleteButton').show();
        $('#eventForm').data('eventId', info.event.id);

        if (info.event.extendedProps.personal) {
          $('#personalEvent').prop('checked', true);
          $('#personalTitleLabel').show();
          $('#personalTitle').show().val(info.event.title);
          $('#clienteNombre').prop('disabled', true);
          $('#telefono').prop('disabled', true);
          $('#email').prop('disabled', true);
        } else {
          $('#personalEvent').prop('checked', false);
          $('#personalTitleLabel').hide();
          $('#personalTitle').hide().val('');
          $('#clienteNombre').prop('disabled', false);
          $('#telefono').prop('disabled', false);
          $('#email').prop('disabled', false);
        }
      },

      select: function(info) {
        console.log('Seleccionar rango:', info);
        $('#eventForm')[0].reset();
        $('#eventModal').show();

        // Asegúrate de habilitar los campos por si estuvieron deshabilitados antes
        $('#clienteNombre').prop('disabled', false);
        $('#telefono').prop('disabled', false);
        $('#email').prop('disabled', false);

        $('#date').val(moment(info.start).format('YYYY-MM-DD'));
        $('#timeRange').val(moment(info.start).format('HH:mm') + ' - ' + moment(info.end).format('HH:mm'));

        $('input[name="service"]').prop('checked', false);
        $('#deleteButton').hide();
      },
      editable: true,
      eventDidMount: function(info) {
        let startTime = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let endTime = info.event.end ? info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
        let title = info.event.title;

        let innerHtml = `
          <div class="fc-event-time">${startTime} - ${endTime}</div>
          <div class="fc-event-title">${title}</div>
        `;

        let arrayOfDomNodes = [];
        let div = document.createElement('div');
        div.innerHTML = innerHtml;
        arrayOfDomNodes.push(div);

        info.el.innerHTML = '';
        arrayOfDomNodes.forEach(function(node) {
          info.el.appendChild(node);
        });
      }
    });

    calendar.render();
  }

  function closeModal() {
    $('#eventModal').hide();
    $('#eventForm')[0].reset();
    $('#eventForm').removeData('eventId');

    // Asegúrate de habilitar los campos al cerrar el modal
    $('#clienteNombre').prop('disabled', false);
    $('#telefono').prop('disabled', false);
    $('#email').prop('disabled', false);
  }

  $('.close').on('click', function(e) {
    e.preventDefault();
    closeModal();
  });

  function loadServices() {
    $.ajax({
      url: 'fetch_services.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        $('#serviceContainer').empty();
        $.each(data, function(key, service) {
          var checkbox = $('<input>', {
            type: 'checkbox',
            id: 'service_' + service.id,
            name: 'service',
            value: service.id,
            'data-duracion': service.duracion
          });
          var label = $('<label>', { for: 'service_' + service.id }).text(service.nombre).prepend(checkbox);
          $('#serviceContainer').append(label);
        });
      }
    });
  }

  loadServices();

  $('#clienteNombre').on('input', function() {
    var query = $(this).val();
    if (query.length > 2) {
      $.ajax({
        url: 'buscar_cliente.php',
        method: 'GET',
        data: { query: query },
        success: function(data) {
          var clientes = JSON.parse(data);
          if (clientes.length > 0) {
            $('#clienteSugerencias').empty().show();
            clientes.forEach(function(cliente) {
              $('#clienteSugerencias').append('<div class="cliente-sugerencia" data-id="' + cliente.id + '" data-telefono="' + cliente.telefono + '" data-email="' + cliente.email + '">' + cliente.nombre + '</div>');
            });
          } else {
            $('#clienteSugerencias').hide();
          }
        }
      });
    } else {
      $('#clienteSugerencias').hide();
    }
  });

  $('#clienteSugerencias').on('click', '.cliente-sugerencia', function() {
    var clienteNombre = $(this).text();
    var clienteTelefono = $(this).data('telefono');
    var clienteEmail = $(this).data('email');

    $('#clienteNombre').val(clienteNombre);
    $('#telefono').val(clienteTelefono);
    $('#email').val(clienteEmail);

    $('#clienteSugerencias').hide();
  });

  $(document).on('click', function(e) {
    if (!$(e.target).closest('#clienteSugerencias, #clienteNombre').length) {
      $('#clienteSugerencias').hide();
    }
  });

  $('#serviceContainer').on('change', 'input[name="service"]', function() {
    var date = $('#date').val();
    var timeRange = $('#timeRange').val();
    if (date && timeRange) {
      var totalDuration = 0;
      $('input[name="service"]:checked').each(function() {
        totalDuration += parseInt($(this).data('duracion'), 10);
      });

      var [startTime, endTime] = timeRange.split(' - ');
      var startDateTime = moment(date + ' ' + startTime, 'YYYY-MM-DD HH:mm');
      var endDateTime = startDateTime.clone().add(totalDuration, 'minutes');
      var formattedEndDateTime = endDateTime.format('HH:mm');
      $('#timeRange').val(startTime + ' - ' + formattedEndDateTime);
    }
  });

  $('#personalEvent').on('change', function() {
    if ($(this).is(':checked')) {
      $('#personalTitleLabel').show();
      $('#personalTitle').show();
      $('#clienteNombre').prop('disabled', true);
      $('#telefono').prop('disabled', true);
      $('#email').prop('disabled', true);
      $('#clienteNombre').val('');
      $('#telefono').val('');
      $('#email').val('');
    } else {
      $('#personalTitleLabel').hide();
      $('#personalTitle').hide().val('');
      $('#clienteNombre').prop('disabled', false);
      $('#telefono').prop('disabled', false);
      $('#email').prop('disabled', false);
    }
  });

  $('#eventForm').on('submit', function(e) {
    e.preventDefault();

    var clienteNombre = $('#clienteNombre').val();
    var telefono = $('#telefono').val();
    var email = $('#email').val();
    var isPersonalEvent = $('#personalEvent').is(':checked');
    var personalTitle = $('#personalTitle').val();

    if (!isPersonalEvent && (!clienteNombre || !telefono || !email)) {
      alert('Por favor, complete todos los campos del cliente.');
      return;
    }

    var clienteData = {
      nombre: clienteNombre,
      telefono: telefono,
      email: email
    };

    if (isPersonalEvent) {
      crearOActualizarCita(null);
    } else {
      $.ajax({
        url: 'buscar_o_crear_cliente.php',
        method: 'POST',
        data: clienteData,
        success: function(response) {
          var cliente = JSON.parse(response);
          if (cliente.id) {
            crearOActualizarCita(cliente.id);
          } else {
            alert('Error al procesar el cliente.');
          }
        }
      });
    }
  });

  function crearOActualizarCita(clienteId) {
    var date = $('#date').val();
    var timeRange = $('#timeRange').val();
    var [startTime, endTime] = timeRange.split(' - ');
    var start = moment(date + ' ' + startTime, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:mm:ss');
    var end = moment(date + ' ' + endTime, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:mm:ss');
    var personalEvent = $('#personalEvent').is(':checked');
    var personalTitle = $('#personalTitle').val();
    var services = $('input[name="service"]:checked').map(function() {
      return { id: $(this).val(), duracion: $(this).data('duracion') };
    }).get();
    var eventId = $('#eventForm').data('eventId');

    var eventData = {
      cliente_id: clienteId,
      start: start,
      end: end,
      personal: personalEvent,
      personalTitle: personalTitle,
      services: JSON.stringify(services)
    };

    if (personalEvent) {
      eventData.title = personalTitle;
      eventData.cliente_id = null;
    }

    if (eventId) {
      $.ajax({
        url: 'update_event.php',
        method: 'POST',
        data: { ...eventData, id: eventId },
        success: function() {
          calendar.refetchEvents();
          closeModal();
        }
      });
    } else {
      $.ajax({
        url: 'add_event.php',
        method: 'POST',
        data: eventData,
        success: function() {
          calendar.refetchEvents();
          closeModal();
        }
      });
    }
  }

  $('#deleteButton').on('click', function() {
    var eventId = $('#eventForm').data('eventId');
    if (eventId) {
      $.ajax({
        url: 'delete_event.php',
        method: 'POST',
        data: { id: eventId },
        success: function() {
          calendar.refetchEvents();
          closeModal();
        }
      });
    }
  });
});

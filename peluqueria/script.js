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

  // Obtener horarios de apertura desde la base de datos
  $.ajax({
    url: 'fetch_horarios.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      var businessHours = data.map(function(horario) {
        return {
          daysOfWeek: [dayMap[horario.dia]], // Días de la semana
          startTime: horario.hora_inicio, // Hora de apertura
          endTime: horario.hora_fin // Hora de cierre
        };
      });

      // Encontrar los límites de tiempo más temprano y más tarde para slotMinTime y slotMaxTime
      var minStartTime = Math.min(...data.map(h => parseInt(h.hora_inicio.split(":")[0])));
      var maxEndTime = Math.max(...data.map(h => parseInt(h.hora_fin.split(":")[0])));

      // Inicializar el calendario después de obtener los horarios
      initializeCalendar(businessHours, minStartTime, maxEndTime);
    },
    error: function() {
      alert('Error al cargar los horarios de apertura');
    }
  });

  function initializeCalendar(businessHours, minStartTime, maxEndTime) {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',  // Establecer la vista por defecto a "timeGridWeek"
      locale: 'es',                 // Configurar idioma español
      nowIndicator: true,           // Mostrar línea en la hora actual
      businessHours: businessHours, // Definir horarios de apertura
      slotMinTime: minStartTime + ":00:00",  // Hora de inicio visible
      slotMaxTime: (maxEndTime + 1) + ":00:00",  // Hora de fin visible
      slotDuration: "00:15:00",     // **Agregar esta línea** para intervalos de 15 minutos

      eventTimeFormat: {            // Configuración para el formato de tiempo en 24 horas
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      },
      slotLabelFormat: {            // Formato de las etiquetas de las filas de tiempo en 24 horas
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
            negocio_id: 1 // Ajusta esto según sea necesario
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
        // Limpiar todos los campos del formulario al abrir el modal
        $('#eventForm')[0].reset();
        $('#eventModal').css('display', 'block');
        $('#cliente').val(info.event.extendedProps.cliente_id); // Establecer el cliente en el select
        $('#date').val(moment(info.event.start).format('YYYY-MM-DD'));
        $('#timeRange').val(moment(info.event.start).format('HH:mm') + ' - ' + moment(info.event.end).format('HH:mm'));

        // Actualizar el enlace de "Detalle Cliente" con el ID del cliente
        var clienteId = info.event.extendedProps.cliente_id;
        $('#detalleClienteLink').attr('href', '../detalle-cliente.php?id=' + clienteId);

        // Marcar las casillas de verificación correspondientes a los servicios seleccionados
        var services = info.event.extendedProps.services || [];
        services.forEach(function(service) {
          $('input[name="service"][value="' + service.id + '"]').prop('checked', true);
        });

        $('#deleteButton').show();  // Mostrar botón de borrar al editar

        // Guardar el ID del evento para futuras referencias
        $('#eventForm').data('eventId', info.event.id);

        // Comprobar si es un evento personal y rellenar el motivo si aplica
        if (info.event.extendedProps.personal) {
          $('#personalEvent').prop('checked', true);
          $('#personalTitleLabel').show();
          $('#personalTitle').show().val(info.event.title);
          $('#cliente').prop('disabled', true); // Desactivar el select de cliente
        } else {
          $('#personalEvent').prop('checked', false);
          $('#personalTitleLabel').hide();
          $('#personalTitle').hide().val('');
          $('#cliente').prop('disabled', false); // Activar el select de cliente
        }
      },

      select: function(info) {
        console.log('Seleccionar rango:', info);
        // Limpiar todos los campos del formulario al abrir el modal
        $('#eventForm')[0].reset();
        $('#eventModal').css('display', 'block');

        // Establecer la fecha y hora inicial y final
        $('#date').val(moment(info.start).format('YYYY-MM-DD'));
        $('#timeRange').val(moment(info.start).format('HH:mm') + ' - ' + moment(info.end).format('HH:mm'));

        $('input[name="service"]').prop('checked', false);  // Desmarcar todas las casillas de verificación
        $('#deleteButton').hide();  // Ocultar botón de borrar en la creación

        // Limpiar el enlace de "Detalle Cliente"
        $('#detalleClienteLink').attr('href', '#');

        // Resetear campos de evento personal
        $('#personalEvent').prop('checked', false);
        $('#personalTitleLabel').hide();
        $('#personalTitle').hide().val('');
        $('#cliente').prop('disabled', false); // Activar el select de cliente
      },
      editable: true,
      eventDidMount: function(info) {
        let startTime = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let endTime = info.event.end ? info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
        let title = info.event.title; // Utilizar el título del evento directamente

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

  // Cerrar modal
  $('.close').on('click', function() {
    $('#eventModal').css('display', 'none');
  });

  // Cargar servicios desde la base de datos
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
            id: 'service_' + service.id, // Agregar un id único para cada checkbox
            name: 'service',
            value: service.id,
            'data-duracion': service.duracion
          });
          var label = $('<label>', { for: 'service_' + service.id }).text(service.nombre).prepend(checkbox); // Enlazar el label con el checkbox
          $('#serviceContainer').append(label);
        });
      }
    });
  }

  loadServices();

  // Cargar clientes desde la base de datos
  function loadClients() {
    $.ajax({
      url: 'fetch_clients.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        $('#cliente').empty();
        $('#cliente').append('<option value="">Seleccione un cliente</option>');
        $.each(data, function(key, client) {
          $('#cliente').append('<option value="' + client.id + '">' + client.nombre + '</option>');
        });
      },
      error: function() {
        alert('Error al cargar los clientes');
      }
    });
  }

  loadClients();

  // Calcular la hora de fin cuando se seleccionan servicios
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

  // Función para cerrar el modal y resetear el formulario
  function closeModal() {
    $('#eventModal').css('display', 'none');
    $('#eventForm')[0].reset();
    $('#eventForm').removeData('eventId'); // Limpiar el ID del evento para futuros usos
  }

  // Manejar el formulario de creación/edición de eventos
  $('#eventForm').on('submit', function(e) {
    e.preventDefault();
    
    var clienteId = $('#cliente').val();
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
    var eventId = $(this).data('eventId');

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
      eventData.cliente_id = null; // Remover cliente si es un evento personal
    }

    if (eventId) {
      // Actualizar evento existente
      $.ajax({
        url: 'update_event.php',
        method: 'POST',
        data: { ...eventData, id: eventId },
        success: function(response) {
          calendar.refetchEvents();
          closeModal();
        }
      });
    } else {
      // Crear nuevo evento
      $.ajax({
        url: 'add_event.php',
        method: 'POST',
        data: eventData,
        success: function(eventId) {
          calendar.refetchEvents();
          closeModal();
        }
      });
    }
  });

  // Manejar la eliminación de eventos
  $('#deleteButton').on('click', function() {
    var eventId = $('#eventForm').data('eventId');
    if (eventId) {
      $.ajax({
        url: 'delete_event.php',
        method: 'POST',
        data: { id: eventId },
        success: function(response) {
          calendar.refetchEvents();
          closeModal();
        }
      });
    }
  });

  // Actualizar el enlace de "Detalle Cliente" cuando se cambia el cliente en el select
  $('#cliente').on('change', function() {
    var clienteId = $(this).val();
    if (clienteId) {
      $('#detalleClienteLink').attr('href', '../detalle-cliente.php?id=' + clienteId);
    } else {
      $('#detalleClienteLink').attr('href', '#');
    }
  });

  // Manejar el cambio en el checkbox de evento personal
  $('#personalEvent').on('change', function() {
    if ($(this).is(':checked')) {
      $('#personalTitleLabel').show();
      $('#personalTitle').show();
      $('#cliente').prop('disabled', true); // Desactivar el select de cliente
    } else {
      $('#personalTitleLabel').hide();
      $('#personalTitle').hide().val('');
      $('#cliente').prop('disabled', false); // Activar el select de cliente
    }
  });
});

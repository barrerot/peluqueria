$(document).ready(function() {
  var calendar;

  // Obtener horarios de apertura desde la base de datos
  $.ajax({
    url: 'fetch_horarios.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      var businessHours = data.map(function(horario) {
        return {
          daysOfWeek: [horario.dia_semana], // Días de la semana (1: Lunes, ..., 7: Domingo)
          startTime: horario.hora_apertura, // Hora de apertura
          endTime: horario.hora_cierre // Hora de cierre
        };
      });

      // Inicializar el calendario después de obtener los horarios
      initializeCalendar(businessHours);
    },
    error: function() {
      alert('Error al cargar los horarios de apertura');
    }
  });

  function initializeCalendar(businessHours) {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',  // Establecer la vista por defecto a "timeGridWeek"
      locale: 'es',                 // Configurar idioma español
      nowIndicator: true,           // Mostrar línea en la hora actual
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
      businessHours: businessHours,
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
        $('#start').val(moment(info.event.start).format('YYYY-MM-DDTHH:mm'));
        if (info.event.end) {
          $('#end').val(moment(info.event.end).format('YYYY-MM-DDTHH:mm'));
        }

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
      },
      select: function(info) {
        console.log('Seleccionar rango:', info);
        // Limpiar todos los campos del formulario al abrir el modal
        $('#eventForm')[0].reset();
        $('#eventModal').css('display', 'block');

        // Formatear la fecha seleccionada para el campo datetime-local
        var startDate = info.start;
        var formattedDate = moment(startDate).format('YYYY-MM-DDTHH:mm');
        $('#start').val(formattedDate); // Establecer la fecha de inicio
        $('#end').val('');  // Limpiar el campo de fin
        $('input[name="service"]').prop('checked', false);  // Desmarcar todas las casillas de verificación
        $('#deleteButton').hide();  // Ocultar botón de borrar en la creación

        // Limpiar el enlace de "Detalle Cliente"
        $('#detalleClienteLink').attr('href', '#');
      },
      editable: true,
      eventDidMount: function(info) {
        let startTime = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let endTime = info.event.end ? info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
        let title = info.event.extendedProps.cliente_nombre; // Mostrar el nombre del cliente

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
    var start = $('#start').val();
    if (start) {
      var totalDuration = 0;
      $('input[name="service"]:checked').each(function() {
        totalDuration += parseInt($(this).data('duracion'), 10);
      });
      var startDate = moment(start);
      var endDate = startDate.clone().add(totalDuration, 'minutes');
      var formattedEndDate = endDate.format('YYYY-MM-DDTHH:mm');
      $('#end').val(formattedEndDate);
    } else {
      $('#end').val('');
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
    var start = $('#start').val();
    var end = $('#end').val();
    var services = $('input[name="service"]:checked').map(function() {
      return { id: $(this).val(), duracion: $(this).data('duracion') };
    }).get();
    var eventId = $(this).data('eventId');

    var eventData = {
      cliente_id: clienteId,
      start: start,
      end: end,
      services: JSON.stringify(services)
    };

    if (eventId) {
      $.ajax({
        url: 'update_event.php',
        method: 'POST',
        data: {
          id: eventId,
          cliente_id: clienteId,
          start: start,
          end: end,
          services: JSON.stringify(services)
        },
        success: function(response) {
          calendar.refetchEvents(); // Refrescar los eventos en el calendario
          closeModal();
        }
      });
    } else {
      $.ajax({
        url: 'add_event.php',
        method: 'POST',
        data: eventData,
        success: function(eventId) {
          calendar.refetchEvents(); // Refrescar los eventos en el calendario
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
          calendar.refetchEvents(); // Refrescar los eventos en el calendario
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
});

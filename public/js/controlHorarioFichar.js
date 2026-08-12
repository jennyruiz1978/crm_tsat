document.addEventListener('DOMContentLoaded', function() {
    var btnFichar = document.getElementById('btnFichar');
    var estadoIndicador = document.getElementById('estadoIndicador');
    var mensajeFichaje = document.getElementById('mensajeFichaje');
    var urlFichar = document.getElementById('urlFichar');
    var detalleJornada = document.getElementById('detalleJornada');

    var fichajeEnProgreso = false;
    var COOLDOWN_SEGUNDOS = 5;
    var cooldownTimer = null;
    var ultimoClickTimestamp = 0;

    var latitudCache = null;
    var longitudCache = null;
    var geoDisponible = false;

    function precargarGeolocalizacion() {
        if (!navigator.geolocation) {
            geoDisponible = false;
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(position) {
                latitudCache = position.coords.latitude;
                longitudCache = position.coords.longitude;
                geoDisponible = true;
            },
            function() {
                latitudCache = null;
                longitudCache = null;
                geoDisponible = false;
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
        );
    }

    function actualizarGeolocalizacion() {
        if (!navigator.geolocation) return;
        navigator.geolocation.getCurrentPosition(
            function(position) {
                latitudCache = position.coords.latitude;
                longitudCache = position.coords.longitude;
                geoDisponible = true;
            },
            function() {
                latitudCache = null;
                longitudCache = null;
                geoDisponible = false;
            },
            { enableHighAccuracy: true, timeout: 5000, maximumAge: 120000 }
        );
    }

    precargarGeolocalizacion();
    setInterval(actualizarGeolocalizacion, 60000);

    if (btnFichar) {
        btnFichar.addEventListener('click', function(e) {
            e.preventDefault();
            var ahora = Date.now();
            if (fichajeEnProgreso || (ahora - ultimoClickTimestamp) < 1000) return;
            ultimoClickTimestamp = ahora;
            fichajeEnProgreso = true;
            btnFichar.disabled = true;
            btnFichar.classList.add('opacity-50', 'cursor-not-allowed');

            var tipo = btnFichar.getAttribute('data-tipo');
            var datos = new FormData();
            datos.append('tipoFichaje', tipo);

            if (latitudCache !== null && longitudCache !== null) {
                datos.append('latitud', latitudCache);
                datos.append('longitud', longitudCache);
            }

            enviarFichaje(datos);
        });
    }

    function enviarFichaje(datos) {
        fetch(urlFichar.value, {
            method: 'POST',
            body: datos,
            credentials: 'same-origin'
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.error) {
                mostrarMensaje(data.mensaje, 'error');
                if (data.mensaje && (data.mensaje.indexOf('esperar') !== -1 || data.mensaje.indexOf('Acaba de registrar') !== -1)) {
                    iniciarCooldown(COOLDOWN_SEGUNDOS);
                } else {
                    fichajeEnProgreso = false;
                    if (btnFichar) {
                        btnFichar.disabled = false;
                        btnFichar.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
                return;
            }

            fichajeEnProgreso = false;
            mostrarMensaje(data.mensaje, 'success');
            actualizarEstado(data);
            actualizarUltimoFichaje(data);
            agregarFichajeALista(data);
            iniciarCooldown(COOLDOWN_SEGUNDOS);
        })
        .catch(function() {
            fichajeEnProgreso = false;
            if (btnFichar) {
                btnFichar.disabled = false;
                btnFichar.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            mostrarMensaje('Error de conexion. Intente de nuevo.', 'error');
        });
    }

    function mostrarMensaje(texto, tipo) {
        if (!mensajeFichaje) return;
        mensajeFichaje.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
        if (tipo === 'success') {
            mensajeFichaje.classList.add('bg-green-100', 'text-green-700');
        } else {
            mensajeFichaje.classList.add('bg-red-100', 'text-red-700');
        }
        mensajeFichaje.textContent = texto;
        mensajeFichaje.classList.remove('hidden');

        setTimeout(function() {
            mensajeFichaje.classList.add('hidden');
        }, 5000);
    }

    function actualizarEstado(data) {
        if (btnFichar) {
            btnFichar.setAttribute('data-tipo', data.tipoSiguiente);
            btnFichar.textContent = '';
            var icon = document.createElement('i');
            icon.className = 'fas fa-clock mr-2';
            btnFichar.appendChild(icon);
            btnFichar.appendChild(document.createTextNode('Fichar ' + ucfirst(data.tipoSiguiente)));

            btnFichar.classList.remove('bg-green-600', 'hover:bg-green-700', 'bg-red-600', 'hover:bg-red-700');
            if (data.tipoSiguiente === 'entrada') {
                btnFichar.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                btnFichar.classList.add('bg-red-600', 'hover:bg-red-700');
            }
        }

        if (estadoIndicador) {
            estadoIndicador.textContent = data.estadoTexto;
            estadoIndicador.classList.remove('bg-red-500', 'bg-green-500');
            estadoIndicador.classList.add(data.claseEstado);
        }
    }

    function actualizarUltimoFichaje(data) {
        var contenedor = document.getElementById('ultimoFichajeTexto');
        var tipoSpan = document.getElementById('ultimoFichajeTipo');
        var fechaSpan = document.getElementById('ultimoFichajeFecha');

        if (!contenedor) return;

        contenedor.classList.remove('hidden');

        if (tipoSpan) {
            tipoSpan.textContent = data.tipoFichaje;
        } else {
            contenedor.innerHTML = '\u00daltimo fichaje: <span id="ultimoFichajeTipo">' + data.tipoFichaje + '</span> - <span id="ultimoFichajeFecha">' + formatearFechaFichaje(data.fechahora) + '</span>';
        }

        if (fechaSpan) {
            fechaSpan.textContent = formatearFechaFichaje(data.fechahora);
        }
    }

    function formatearFechaFichaje(fechahora) {
        if (!fechahora) return '';
        var partes = fechahora.split(' ');
        var fecha = partes[0];
        var hora = partes.length > 1 ? partes[1].substring(0, 5) : '';
        var fechaPartes = fecha.split('-');
        if (fechaPartes.length === 3) {
            return fechaPartes[2] + '/' + fechaPartes[1] + '/' + fechaPartes[0] + ' ' + hora;
        }
        return fechahora;
    }

    function agregarFichajeALista(data) {
        if (!detalleJornada) return;

        var placeholder = detalleJornada.querySelector('.text-gray-500');
        if (placeholder && placeholder.textContent.indexOf('no ha iniciado') !== -1) {
            detalleJornada.innerHTML = '';
        }
        if (placeholder && placeholder.textContent.indexOf('Sin fichajes') !== -1) {
            detalleJornada.innerHTML = '';
        }

        var esEntrada = data.tipoFichaje === 'entrada';
        var hora = data.fechahora ? data.fechahora.split(' ')[1] || data.fechahora.substring(11, 16) : new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'});

        if (hora && hora.length > 5) {
            hora = hora.substring(0, 5);
        }

        var div = document.createElement('div');
        div.className = 'flex justify-between items-center py-2 border-b border-gray-100 fichaje-row';
        div.setAttribute('data-tipo', data.tipoFichaje);
        div.setAttribute('data-fechahora', data.fechahora);

        div.innerHTML = '<span class="font-medium ' + (esEntrada ? 'text-green-600' : 'text-red-600') + '">' +
            '<i class="fas ' + (esEntrada ? 'fa-sign-in-alt' : 'fa-sign-out-alt') + ' mr-2"></i>' +
            ucfirst(data.tipoFichaje) +
            '</span>' +
            '<span class="text-gray-600">' + hora + '</span>';

        var totalHorasDiv = detalleJornada.querySelector('#totalHoras');
        if (totalHorasDiv) {
            detalleJornada.insertBefore(div, totalHorasDiv);
        } else {
            detalleJornada.appendChild(div);
        }

        if (esEntrada) {
            if (totalHorasDiv) {
                totalHorasDiv.remove();
            }
        } else {
            if (data.horastotales !== null && data.horastotales !== undefined) {
                if (totalHorasDiv) {
                    totalHorasDiv.querySelector('span:last-child').textContent = data.horastotales + 'h';
                } else {
                    var nuevoTotal = document.createElement('div');
                    nuevoTotal.id = 'totalHoras';
                    nuevoTotal.className = 'flex justify-between items-center py-2 mt-2 font-bold text-gray-700 border-t border-gray-200 pt-3';
                    nuevoTotal.innerHTML = '<span>Total horas:</span><span>' + data.horastotales + 'h</span>';
                    detalleJornada.appendChild(nuevoTotal);
                }
            }
        }
    }

    function iniciarCooldown(segundos) {
        if (!btnFichar) return;
        btnFichar.disabled = true;
        btnFichar.classList.add('opacity-50', 'cursor-not-allowed');

        var segundosRestantes = segundos;
        var textoOriginal = 'Fichar ' + ucfirst(btnFichar.getAttribute('data-tipo'));

        btnFichar.textContent = segundosRestantes + 's...';

        if (cooldownTimer) clearInterval(cooldownTimer);
        cooldownTimer = setInterval(function() {
            segundosRestantes--;
            if (segundosRestantes <= 0) {
                clearInterval(cooldownTimer);
                cooldownTimer = null;
                btnFichar.disabled = false;
                btnFichar.classList.remove('opacity-50', 'cursor-not-allowed');
                btnFichar.textContent = '';
                var icon = document.createElement('i');
                icon.className = 'fas fa-clock mr-2';
                btnFichar.appendChild(icon);
                btnFichar.appendChild(document.createTextNode(textoOriginal));
            } else {
                btnFichar.textContent = segundosRestantes + 's...';
            }
        }, 1000);
    }

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});
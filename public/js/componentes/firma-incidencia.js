class FirmaIncidencia extends HTMLElement {
    constructor() {
        super();
        this.urlGuardarFirma = this.getAttribute("urlGuardarFirma") || '';;
        this.idIncidencia = this.getAttribute("idIncidencia") || '';;
        this.firmaGuardada = this.getAttribute("firmaGuardada") || '0';
        this.urlFirma = this.getAttribute("urlFirma") || '';
        this.mostrarBotonLimpiar = this.getAttribute("mostrarBotonLimpiar") === 'true';
        this.mostrarBotonGuardar = this.getAttribute("mostrarBotonGuardar") === 'true';
    }

    connectedCallback() {
        this.innerHTML = `
            <div class="grid grid-cols-1">
                <div class="mr-2">
                    <div class="inline-flex">   
                        <label class="uppercase text-sm xl:text-base text-gray-500 text-light font-semibold lg:mt-1 mr-3">Firma</label>
                    </div>
                </div>
                <div class="container rounded-lg" id="firma_container">
                   ${this.firmaGuardada === '1' ? this.mostrarFirmaGuardada() : this.mostrarCanvas()}
                </div>
            </div>
        `;

        // Solo configurar el canvas si no hay firma guardada
        if (this.firmaGuardada === '0') {
            this.setupCanvas();
        }
    }
    mostrarFirmaGuardada() {
        return `
            <div class="text-gray-500" id="contenedorFirma1">                                      	
                <img id="imagenFirma" class="border-2 border-coolGray-300" src="${this.urlFirma}" />
            </div>
        `;
    }

   
    mostrarCanvas() {
        return `
            <div class="text-gray-500" id="contenedorFirma1">                                                   	
                <canvas id="canvas" class="border-2 border-coolGray-300"></canvas>
            </div>            
            ${this.mostrarBotonesHtml()}
        `;
    }

    mostrarBotonesHtml() {
        return `
            <div class="text-gray-500 mt-2" id="contenedorFirma2">                                            	
                ${this.mostrarBotonLimpiar ? `<a id="btnLimpiar" class="cursor-pointer w-auto bg-gray-400 hover:bg-gray-600 rounded-lg shadow-xl text-sm 2xl:text-base text-white px-2 py-1 mr-3">Limpiar</a>` : ''}
                ${this.mostrarBotonGuardar ? `<a id="btnCapturarImagen" class="cursor-pointer w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-sm 2xl:text-base text-white px-2 py-1 mr-3">Guardar firma</a>` : ''}
            </div>
        `;
    }

    setupCanvas() {
        const $canvas = this.querySelector("#canvas");
        const $btnLimpiar = this.querySelector("#btnLimpiar");
        const $btnCapturarImagen = this.querySelector("#btnCapturarImagen");

        // Verificar que el canvas existe
        if (!$canvas) return;

        const contexto = $canvas.getContext("2d");
        const COLOR_PINCEL = "black";
        const COLOR_FONDO = "white";
        const GROSOR = 2;

        let xAnterior = 0, yAnterior = 0, xActual = 0, yActual = 0;
        let haComenzadoDibujo = false;

        const obtenerXReal = (clientX) => clientX - $canvas.getBoundingClientRect().left;
        const obtenerYReal = (clientY) => clientY - $canvas.getBoundingClientRect().top;

        const limpiarCanvas = () => {
            contexto.fillStyle = COLOR_FONDO;
            contexto.fillRect(0, 0, $canvas.width, $canvas.height);
        };
        limpiarCanvas();

        // Solo añadir eventos si los botones existen
        if ($btnLimpiar) {
            $btnLimpiar.onclick = limpiarCanvas;
        }

        const onClicOToqueIniciado = (evento) => {
            xAnterior = xActual;
            yAnterior = yActual;
            xActual = obtenerXReal(evento.clientX);
            yActual = obtenerYReal(evento.clientY);
            contexto.beginPath();
            contexto.fillStyle = COLOR_PINCEL;
            contexto.fillRect(xActual, yActual, GROSOR, GROSOR);
            contexto.closePath();
            haComenzadoDibujo = true;
        };

        const onMouseODedoMovido = (evento) => {
            evento.preventDefault();
            if (!haComenzadoDibujo) return;

            let target = evento.type.includes("touch") ? evento.touches[0] : evento;
            xAnterior = xActual;
            yAnterior = yActual;
            xActual = obtenerXReal(target.clientX);
            yActual = obtenerYReal(target.clientY);
            contexto.beginPath();
            contexto.moveTo(xAnterior, yAnterior);
            contexto.lineTo(xActual, yActual);
            contexto.strokeStyle = COLOR_PINCEL;
            contexto.lineWidth = GROSOR;
            contexto.stroke();
            contexto.closePath();
        };

        const onMouseODedoLevantado = () => {
            haComenzadoDibujo = false;
        };

        // Asegúrate de que el canvas tenga los eventos correctamente
        ["mousedown", "touchstart"].forEach(evento => {
            $canvas.addEventListener(evento, onClicOToqueIniciado);
        });

        ["mousemove", "touchmove"].forEach(evento => {
            $canvas.addEventListener(evento, onMouseODedoMovido);
        });

        ["mouseup", "touchend"].forEach(evento => {
            $canvas.addEventListener(evento, onMouseODedoLevantado);
        });

        if ($btnCapturarImagen) {
            $btnCapturarImagen.addEventListener("click", () => {
                const imagenBase64 = $canvas.toDataURL("image/png");
                this.guardarFirma(imagenBase64);
            });
        }
    }

    guardarFirma(imagenBase64) {
        fetch(this.urlGuardarFirma, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                imagen: imagenBase64,
                idIncidencia: this.idIncidencia
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.respuesta === 1) {
                this.querySelector('#firma_container').innerHTML = data.html;
                Swal.fire({
                    title: 'Firma guardada',
                    text: 'Se ha guardado la firma correctamente',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Ha ocurrido un error y no se ha guardado la firma.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        })
        .catch(error => {
            console.error("Error al enviar la firma:", error);
        });
    }

    obtenerFirmaBase64() {
        const $canvas = this.querySelector("#canvas");
        if ($canvas) {
            return $canvas.toDataURL("image/png");
        }
        return null;
    }
}

window.customElements.define("firma-incidencia", FirmaIncidencia);

class visible extends HTMLElement {

    observedAttributes() {
        return ['color', 'nombre'];
    }

    constructor() {
        super();
        this.ver = "Hola";

    }

    connectedCallback() {

        this.innerHTML = `<p>${this.cambiar()}</p>`;


    }

    attributeChangedCallback(attrName, oldVal, newVal) {
        // la logoca que crea necesaria para acyivar en la pagina
        this.innerHTML = `<p>Algo ha cambiado el valor ${newval}</p>`;
    }

    cambiar() {
        if (true) {
            this.ver = "Adios";
        }
        return this.ver;
    }

    consultaControlador() {
        // Aqui llamo con ajax al controlador y al modelo y me devuelve un json
    }



}

window.customElements.define("dlm-visible", visible);
class pedroBoton extends HTMLElement {

    constructor() {
        super();
        this.nombre = this.getAttribute("nombre");

    }

    connectedCallback() {
        this.innerHTML = `<h3 class="text-2xl font-semibold leading-tight flex-1 mr-2">${this.nombre}</h3>`;
    }
}

window.customElements.define("ps-boton", pedroBoton);
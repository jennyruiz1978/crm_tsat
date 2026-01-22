class hoy extends HTMLElement {

    constructor() {
        super();
        this.nombre = this.getAttribute("nombre");
        this.apellido = this.getAttribute("apellido");
        this.contenido = `<button class='w-auto bg-pink-700 hover:bg-pink-500 rounded-lg shadow-xl font-medium text-white px-4 py-2'>${this.nombre}</button>`;

    }



    connectedCallback() {
        this.innerHTML = `<dlm-comer plato="${this.nombre}"></dlm-comer>`;

    }





}

window.customElements.define("dlm-hoy", hoy);
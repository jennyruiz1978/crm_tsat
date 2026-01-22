class boton2 extends HTMLElement {

    constructor() {
        super();
        this.nombre = this.getAttribute("nombre");

    }

    accion() {
        const crear = document.getElementById("crear");
        crear.addEventListener("click", () => {
            console.log(`Has pulsado sobre el boton ${this.nombre}`);
        })
    }

    connectedCallback() {
        this.innerHTML = `<button id="crear" class='w-auto bg-green-700 hover:bg-green-500 rounded-lg shadow-xl font-medium text-white px-4 py-2'>${this.nombre}</button>`;
        this.accion();


    }
}

window.customElements.define("ps-boton-alta", boton2);
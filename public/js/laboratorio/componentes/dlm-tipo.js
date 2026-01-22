class tipo extends HTMLElement {

    // pagina ineteresante para mirar un poco: https://lenguajejs.com/webcomponents/nativos/partes-webcomponent/

    constructor() {
        super();
        this.nombre = "Insertar nombre";
    }

    connectedCallback() {
        //El custom element se ha conectado al DOM del documento HTML.
        //this.innerHTML = `<input type="text" value="${this.nombre}" />`;
        this.innerHTML = this.render();


    }

    disconnectedCallback() {
        // El custom element se ha desconectado del DOM del documento HTML.
    }

    adoptedCallback() {
        // El custom element se mueve a un nuevo documento (Común en iframes).
    }

    attributeChangedCallback(name, old, now) {
        // Se ha modificado un atributo observado del custom element.
        // console.log(`El atributo ${name} ha sido modificado de ${old} a ${now}.`);
    }

    static get observedAttributes() {
        // atributos observados para detectar cambios
        return ["value1", "value2"];
    }

    render() {
        return `<div class="flex">
        <span class="text-sm border border-2 rounded-l px-4 py-2 bg-gray-300 whitespace-no-wrap">Label:</span>
        <input name="field_name" class="border border-2 rounded-r px-4 py-2 w-full" type="text" placeholder="Write something here..." />
    </div> `;
    }

}

window.customElements.define("dlm-tipo", tipo);
class primero extends HTMLElement {

    constructor() {
        super();
        this.button = '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Button</button>';
    }

    connectedCallback() {
        this.innerHTML = this.button;
        let tabla = `<table>${name[0]}`;
    }

    disconnectedCallback() {
        
    }
    
}

window.customElements.define("primero-uno", primero);
class tabla extends HTMLElement {
    constructor() {
        super();
        this.titulos = this.getAttribute("titulos").split(",");
        this.url = this.getAttribute("url");
        this.nombre = "Andres"

    }

    connectedCallback() {
        this.traerDatos();
    }

    cabecera() {
        let salida = "";
        for (let i = 0; i < this.titulos.length; i++) {
            salida += `<td class="p-2 border-r cursor-pointer text-sm font-thin text-gray-500">${this.titulos[i]}</td>`;
        }
        return salida;
    }

    traerDatos() {
        let data = { nombre: this.nombre, apellidos: "Ramirez" };
        //console.log(JSON.stringify(data))

        fetch("http://localhost/infomalaga/Pruebas/traer", {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json",
                },
            })
            .then((res) => res.json())
            .then(
                (data) => (this.innerHTML += `
            <table class="w-full border">
            <tr class="bg-gray-50 border-b text-center">
            ${this.cabecera()}    
            </tr>
            <tr class="bg-gray-50 text-center">
            <td class="p-2 border-r">${data.nombre}</td>
            <td class="p-2 border-r">${data.apellidos}</td>
            </tr>
            </table>`)
            );
    }
}

window.customElements.define("dlm-tabla", tabla);
class tabla extends HTMLElement {

    constructor() {
        super();
        this.titulos = this.getAttribute('titulos').split(',');
        this.url = this.getAttribute('url');

    }

    connectedCallback() {

        this.cargardatos();
        this.traerDatos();
        this.addEventListener("click", function(e) {
            let destino = e.target.textContent;
            console.log(destino);
        })

    }

    cargardatos() {
        let tabla = document.createElement("table");
        let encabezados = document.createElement("tr");
        encabezados.innerHTML += `<thead>`;
        for (let i = 0; i < this.titulos.length; i++) {
            encabezados.innerHTML += `<th>${this.titulos[i]}</th>`;
        }
        encabezados.innerHTML += `</thead>`;
        tabla.appendChild(encabezados);
        fetch(this.url)
            .then(response => response.json())
            .then(usuarios => usuarios.forEach(usuarios => {
                let row = document.createElement("tr");
                row.innerHTML +=
                    `
                    <td>${usuarios.nombre}</td>
                    <td>${usuarios.apellidos}</td>
                    <td>${usuarios.edad}</td>
                    `;
                tabla.appendChild(row);


            }));
        this.appendChild(tabla);
    }

    traerDatos() {
        let data = { nombre: "Pedro Luis", apellidos: "Silva Elipe" };
        //console.log(JSON.stringify(data));

        fetch('traer.php', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => this.innerHTML += `<p>Nombre: ${data.nombre}</p><p>Apellidos: ${data.apellidos}</p>`);


    }
}

window.customElements.define("ps-tabla", tabla);
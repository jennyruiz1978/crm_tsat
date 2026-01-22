class grafico extends HTMLElement {

    constructor() {
        super();

    }

    barras() {
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            data: {
                datasets: [{
                    type: 'line',
                    label: 'Clientes',
                    data: [60, 10, 20, 10],
                    backgroundColor: '#cc65fe',
                    borderColor: '#cc65fe'
                }, {
                    type: 'bar',
                    label: 'Incidencias',
                    data: [4, 20, 15, 40],
                    backgroundColor: '#2271b3'
                }],
                labels: ['ENE', 'FEB', 'MAR', 'ABR']
            }
        });
    }

    connectedCallback() {
        this.innerHTML = `
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl mt-3">
        <canvas id="myChart" width="400" height="200"></canvas>
      </div>`;
        this.barras();


    }
}

window.customElements.define("grafico-prueba", grafico);
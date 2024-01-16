document.addEventListener('DOMContentLoaded', function () {

    /////////////////////////////// RÉCUPÉRATION DES DONNÉES ///////////////////////////////
    
    const labels = formattedData.map(function (data) {
        const date = new Date(data.date);
        const day = ('0' + date.getDate()).slice(-2); // Si le jour est inférieur à 10, on ajoute un zéro devant
        const month = ('0' + (date.getMonth() + 1)).slice(-2); // Pareil ici avec le mois
        let formattedLabel;

        switch (period) { // On formate le label en fonction de la période
            case 'week':
                const week = data.date;
                formattedLabel = 'Semaine ' + week;
                break;
            case 'hour':
                const hours = date.getHours();
                const minutes = ('0' + date.getMinutes()).slice(-2);
                formattedLabel = day + '-' + month + ' ' + hours + 'h' + minutes;
                break;
            default:
                const year = date.getFullYear();
                formattedLabel = day + '-' + month + '-' + year;
        }

        return formattedLabel;
    });


    const values = formattedData.map(function (data) {
        return data.value;
    });

    const periodTitles = {
        'hour': 'Valeur par heure',
        'day': 'Valeur par jour',
        'week': 'Valeur par semaine',
        'month': 'Valeur par mois',
        'year': 'Valeur par année'
    };

    const sensorTitle = {
        'temp': 'de température',
        'hum': 'd\'humidité',
        'co2': 'de CO2',
    }

    /////////////////////////////// GRAPHIQUE ///////////////////////////////

    const ctx = document.getElementById('myChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, ctx.canvas.height, 0, 0);
    gradient.addColorStop(0, 'rgba(75, 192, 192, 0.2)');
    gradient.addColorStop(1, 'rgba(75, 192, 192, 0)');

// Configuration du graphique
const chartConfig = {
    type: 'line',
    data: {
        labels: [], // Les labels seront configurés plus tard
        datasets: [{
            label: '',
            data: [],
            borderWidth: 3,
            borderColor:  '',
            backgroundColor: '',
            fill: true,
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: false
            },
            x: {
                display: true,
            }
        },
        plugins: {
            title: {
                display: true,
                text: '',
                font: {
                    size: 16
                }
            },
            elements: {
                line: {
                    tension: 0.4,
                }
            }
        }
    }
};

const initializeChart = (ctx, labels, values, period, roomName, sensor) => {
    const gradient = ctx.createLinearGradient(0, ctx.canvas.height, 0, 0);

    if(sensor === 'co2') {
        gradient.addColorStop(1, 'rgba(167, 78, 121, 0.8)');
        gradient.addColorStop(0, 'rgba(167, 78, 121, 0)');
        chartConfig.data.datasets[0].borderColor = 'rgba(167, 78, 121, 1)';
    } else if (sensor === 'hum') {
        gradient.addColorStop(1, 'rgba(78, 121, 167, 0.8)');
        gradient.addColorStop(0, 'rgba(78, 121, 167, 0)');  
        chartConfig.data.datasets[0].borderColor = 'rgba(78, 121, 167, 1)';
    } else {
        gradient.addColorStop(1, 'rgba(78, 167, 79, 0.8)');
        gradient.addColorStop(0, 'rgba(78, 167, 79, 0)');
        chartConfig.data.datasets[0].borderColor = 'rgba(78, 167, 79, 1)';
    }

    chartConfig.data.labels = labels;
    chartConfig.data.datasets[0].data = values;
    chartConfig.data.datasets[0].label = periodTitles[period] || 'Période non définie';
    chartConfig.data.datasets[0].backgroundColor = gradient;
    chartConfig.options.plugins.title.text = `Valeurs dans ${roomName} du capteur ${sensorTitle[sensor] || 'Capteur non défini'}`;

    // Initialisation du graphique avec la configuration
    new Chart(ctx, chartConfig);
};

initializeChart(ctx, labels, values, period, roomName, sensor);

});

// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
Chart.defaults.global.title.display = true;
Chart.defaults.global.title.text = "Evolution de mes Rendez-vous";
Chart.defaults.global.elements.point.radius = 5 ;
// -- Area Chart Example
var ctx = document.getElementById('myAreaChart').getContext('2d');

let root = document.querySelector("#datas");
let jsondata = root.dataset.items
jsondata = JSON.parse(jsondata)
 
tabMedecins =[]
tabMois =[]
jsondata.forEach( val =>{
    tabMedecins.push(val.nbr_rdv)
    tabMois.push(val.mois)
})

console.log("Nb de RDVs="+ tabMedecins);
console.log("Mois="+ tabMois);

var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: tabMedecins, 
        datasets: [{
            label: "Le nombre de RDVs/mois",
            lineTension: 0.3,
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(2,117,216,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(2,117,216,1)",
            pointHitRadius: 20,
            pointBorderWidth: 2,
            data: tabMois,
        }],
    },
    options: {
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false
                },
                ticks: {
                    maxTicksLimit: 5
                }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 12,
                    maxTicksLimit: 5
                },
                gridLines: {
                    color: "rgba(0, 0, 0, .125)",
                }
            }],
        },
        legend: {
            display: true
        }
    }
});

// pie chart

var canvas = document.getElementById("myPieChart").getContext('2d');

//let root1 = document.querySelector('#datas');
//let  jsondata = root1.dataset.items
jsondata = JSON.parse(jsondata)
//nombre de RDVs par mois
console.log("Mes Stats:"+jsondata);
 
nbrdoc =[]
nbrbooking =[]
nbbookhonored = []
jsondata.forEach( val =>{
    nbrdoc.push(val.nbr_doc)
    nbrbooking.push(val.nbr_booking)
    nbbookhonored.push(val. nbr_booking_honored)
})

console.log("Nb de docs="+ nbrdoc);
console.log("Nb de bookings="+ nbrbooking);

var myPieChart = new Chart(canvas, {
    type: 'pie',
    data: {
        labels: ["Nombre de médecins inscrits", "Nombre de rendez-vous", "Nombre de rendez-vous honorés"],
        datasets: [{
            data: [nbrdoc, nbrbooking, nbbookhonored],
            backgroundColor: ['#007bff', '#dc3545', '#ffc107'],
        }],
    },
});
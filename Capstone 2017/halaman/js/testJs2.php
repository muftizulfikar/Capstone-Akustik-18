<script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.7.0/firebase-database.js"></script>
<script src="assets/js/moment/moment.js"></script>
<script src="assets/js/plotly/plotly.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $db->where("id_device", $id);
    $data = $db->ObjectBuilder()->get("device", null, 'lokasi');
}
?>
<script type="text/javascript">
    var id = <?= $id ?>;
    var data = <?= json_encode($data, JSON_PRETTY_PRINT) ?>;
    var lokasi = data[0]['lokasi']
    const config = {
        apiKey: "AIzaSyDnkz_iUnkw51DusI2PPaNQ1SACEhuPkBA",
        authDomain: "padisel-8330f.firebaseapp.com",
        databaseURL: "https://padisel-8330f.firebaseio.com",
        projectId: "padisel-8330f",
        storageBucket: "padisel-8330f.appspot.com",
        messagingSenderId: "699978444375",
        appId: "1:699978444375:web:306b4483f52aab2b7abd12",
        measurementId: "G-VJ7MQS47PC"

    };

    function stateBox(color) {
        document.getElementById("state").style.backgroundColor = color;
    }

    function add_list(newtext) {


        $('#recentHistory').prepend("<li>" + newtext + "</li>");

    }

    firebase.initializeApp(config);

    firebase.database().ref('PD/' + id).on('value', ts_measures => {
        let timestamps = [];
        let values = [];
        let states = []
        let statesDevice = []
        ts_measures.forEach(ts_measure => {

            let dBA = ts_measure.val().dBA
            let ts = ts_measure.val().timestamp
            let arc_counter = ts_measure.val().arc_counter
            let stateDevice = ts_measure.val().state
            let dB_one_minute = ts_measure.val().average_dB_one_minute
            let dB_min = ts_measure.val().dB_min
            let dB_max = ts_measure.val().dB_max
            timestamps.push(moment((ts)).format('YYYY-MM-DD HH:mm:ss'));
            values.push(arc_counter);

            if (stateDevice == 'high') {
                color = '#f00'
                message = "Critical";
                states.push(2)
                statesDevice.push(message)
            } else if (stateDevice == 'medium') {

                message = "Moderate";
                color = '#FFFF00'
                states.push(1)
                statesDevice.push(message)

            } else {
                message = "Normal";
                color = '#0f0'
                states.push(0)
                statesDevice.push(message)

            }
            document.getElementById("message").innerHTML = `Kondisi saat ini: ${message}`;
            if (statesDevice[statesDevice.length - 1] != statesDevice[statesDevice.length - 2]) {
                add_list(`Terjadi perubahan state menjadi ${message} (${moment((ts)).format('YYYY-MM-DD HH:mm:ss')})`)
            }

        });




        var time = new Date(timestamps[timestamps.length - 1]);
        var olderTime = time.setMinutes(time.getMinutes() - 5);
        var futureTime = time.setMinutes(time.getMinutes() + 5);
        myPlotDiv = document.getElementById('myPlot');


        const data = [{
            x: timestamps,
            y: states,
            type: 'line',
        }];
        const layout = {
            title: `<b>Perangkat ${lokasi}</b> (${message})`,
            titlefont: {
                family: 'Courier New, monospace',
                size: 16,
                color: '#000'
            },

            xaxis: {
                title: '<b>Time</b>',
                linecolor: 'black',
                linewidth: 2,
                type: "date",
                range: [olderTime, futureTime]
            },
            yaxis: {
                title: '<b>Severity Level</b>',
                titlefont: {
                    family: 'Courier New, monospace',
                    size: 14,
                    color: '#000'
                },
                linecolor: 'black',
                linewidth: 2,
            },
            margin: {
                r: 50,
                pad: 0
            },
            annotations: [{
                text: '0: Normal<br>1: Moderate<br>2:Critical',
                xref: 'paper',
                yref: 'paper',
                align: 'left',
                x: 0,
                xanchor: 'right',
                y: 1,
                yanchor: 'bottom',
                showarrow: false
            }]
        }
        Plotly.newPlot(myPlotDiv, data, layout, {
            responsive: true
        });
        stateBox(color)
    });
</script>
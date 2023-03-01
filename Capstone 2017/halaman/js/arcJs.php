<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>


<script src="assets/js/firebase/firebase-app.js"></script>
<script src="assets/js/firebase/firebase-database.js"></script>
<script src="assets/js/moment/moment.js"></script>
<script src="assets/js/plotly/plotly.js"></script>
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
    console.log(lokasi, id)
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
    firebase.initializeApp(config);


    firebase.database().ref('PD/' + id).on('value', ts_measures => {


        let timestamps = [];
        let values = [];


        ts_measures.forEach(ts_measure => {
            i = 10
            if (i % 10 == 0) {
                timestamps.push(moment((ts_measure.val().timestamp)).format('YYYY-MM-DD HH:mm:ss'));
                values.push(ts_measure.val().arc_counter);
                state = ts_measure.val().state
                dB_one_minute = ts_measure.val().average_dB_one_minute
                arc_counter_minute = ts_measure.val().arc_counter_one_minute

            }
            i = i + 1

        });

        if (state == 'high') {
            state = 'Critical'
            color = '#f00'
        } else if (state == 'medium') {
            state = 'Moderate'
            color = '#FFFF00'

        } else {
            state = 'Normal'
            color = '#0f0'

        }
        document.getElementById("message").innerHTML = state;

        var time = new Date(timestamps[timestamps.length - 1]);
        var olderTime = time.setMinutes(time.getMinutes() - 5);
        var futureTime = time.setMinutes(time.getMinutes() + 5);
        myPlotDiv = document.getElementById('myPlot');


        const data = [{
            x: timestamps,
            y: values,
            type: 'line',
        }];
        const layout = {
            title: `<b>Perangkat ${lokasi}</b> (${state})`,
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
                title: '<b>Counter</b>',
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
            }
        }
        // At last we plot data :-)
        Plotly.newPlot(myPlotDiv, data, layout, {
            responsive: true
        });


        var datatest = [{
                type: "indicator",
                mode: "number+delta",
                value: arc_counter_minute[1],
                domain: {
                    row: 0,
                    column: 1
                },
                delta: {
                    reference: arc_counter_minute[0],
                    // relative: true,
                    position: "top"
                },
                title: {
                    text: "Arc Counter"
                },
            },

        ];

        var layouttest = {
            title: `<b>Data 1 menit terakhir</b>`,
            titlefont: {
                family: 'Courier New, monospace',
                size: 16,
                color: '#000'
            },
            grid: {
                rows: 1,
                pattern: "independent"
            },

            margin: {
                t: 40,
                r: 10,
                l: 10,
                b: 10
            }
        };

        Plotly.newPlot('myPlot2', datatest, layouttest, {
            displayModeBar: false
        });

        stateBox(color)
    });
</script>
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>

<script src="assets/js/leaflet-panel-layers-master/src/leaflet-panel-layers.js"></script>
<script src="assets/js/leaflet.ajax.js"></script>
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
    // REPLACE <...> BY YOUR FIREBASE PROJECT CONFIGURATION:
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

    // Number of last elements to work with, in the 'timestamped_measures' node of the database: 
    const nbOfElts = 100;

    // The big picture: EACH TIME A VALUE CHANGES in the 'timestamped_measures' node, e.g.
    // when a new timestamped measure has been pushed to that node,
    // we make an array of the last 'nbOfElts' timestamps
    // and another array of the last 'nbOfElts' luminosity values.
    // This is because plotly.js, our plotting library, requires arrays of data, one for x and one for y.
    // Those sliding arrays produce a live data effect.
    // -----
    // See https://firebase.google.com/docs/database/web/lists-of-data for trigger syntax:
    // firebase.database().ref('PartialDischarge').limitToLast(nbOfElts).on('value', ts_measures => {
    firebase.database().ref('PD/' + id).on('value', ts_measures => {

        // If you want to get into details, read the following comments :-)
        // 'ts_measures' is a snapshot raw Object, obtained on changed value of 'timestamped_measures' node
        // e.g. a new push to that node, but is not exploitable yet.
        // If we apply the val() method to it, we get something to start work with,
        // i.e. an Object with the 'nbOfElts' last nodes in 'timestamped_measures' node.
        // console.log(ts_measures.val());
        // => {-LIQgqG3c4MjNhJzlgsZ: {timestamp: 1532694324305, value: 714}, -LIQgrs_ejvxcF0MqFre: {…}, … }

        // We prepare empty arrays to welcome timestamps and luminosity values:
        let timestamps = [];
        let values = [];
        let states = []

        // Next, we iterate on each element of the 'ts_measures' raw Object
        // in order to fill the arrays.
        // Let's call 'ts_measure' ONE element of the ts_measures raw Object
        // A handler function written here as an anonymous function with fat arrow syntax
        // tells what to do with each element:
        // * apply the val() method to it to gain access to values of 'timestamp' and 'value',
        // * push those latter to the appropriate arrays.
        // Note: The luminosity value is directly pushed to 'values' array but the timestamp,
        // which is an Epoch time in milliseconds, is converted to human date
        // thanks to the moment().format() function coming from the moment.js library.    
        ts_measures.forEach(ts_measure => {
            i = 10
            if (i % 10 == 0) {
                timestamps.push(moment((ts_measure.val().timestamp)).format('YYYY-MM-DD HH:mm:ss'));
                values.push(ts_measure.val().dBA);
                state = ts_measure.val().state
                if (state == 'high') {
                    states.push(2)
                } else if (state == 'medium') {
                    states.push(1)
                } else {
                    states.push(0)
                }
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

        // Get a reference to the DOM node that welcomes the plot drawn by Plotly.js:
        // var time = new Date(); // Refresh time
        var time = new Date(timestamps[timestamps.length - 1]);
        var olderTime = time.setMinutes(time.getMinutes() - 5);
        var futureTime = time.setMinutes(time.getMinutes() + 5);
        myPlotDiv = document.getElementById('myPlot');
        statePlot = document.getElementById('statePlot');



        // We generate x and y data necessited by Plotly.js to draw the plot
        // and its layout information as well:
        // See https://plot.ly/javascript/getting-started/
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
                title: '<b>dB</b>',
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
        const dataState = [{
            x: timestamps,
            y: states,
            type: 'line',
        }];
        const layoutState = {
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
                title: '<b>dB</b>',
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
        Plotly.newPlot(statePlot, dataState, layoutState, {
            responsive: true
        });


        var datatest = [{
                type: "indicator",
                mode: "number+delta",
                value: dB_one_minute[1],
                domain: {
                    row: 0,
                    column: 0
                },
                delta: {
                    reference: dB_one_minute[0],
                    // relative: true,
                    position: "top"
                },
                title: {
                    text: "Average dB"
                },
            }, {
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
                columns: 2,
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

    // annotations: [{
    //             x: first_data,
    //             y: -0.2,
    //             showarrow: false,
    //             text: "A very clear explanation",
    //             textangle: 0,
    //             xanchor: 'left',
    //             xref: "paper",
    //             yref: "paper"
    //         }],
</script>
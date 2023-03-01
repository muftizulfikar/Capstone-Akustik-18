<script src="assets/js/firebase/firebase-app.js"></script>
<script src="assets/js/firebase/firebase-database.js"></script>
<script src="assets/js/moment/moment.js"></script>
<script src="assets/js/plotly/plotly.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<?php
if (isset($_GET['id'])) {
	$id = $_GET['id'];
}
?>
<script type="text/javascript">
	var id = <?= $id ?>;

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


	function hijau() {
		document.getElementById("box1").style.backgroundColor = '#0f0';
		document.getElementById("box2").style.backgroundColor = 'white';
		document.getElementById("box3").style.backgroundColor = 'white';
	}

	function kuning() {
		document.getElementById("box1").style.backgroundColor = 'white';
		document.getElementById("box2").style.backgroundColor = '#FFFF00'; //yellow
		document.getElementById("box3").style.backgroundColor = 'white';
	}

	function merah() {
		document.getElementById("box1").style.backgroundColor = 'white';
		document.getElementById("box2").style.backgroundColor = 'white';
		document.getElementById("box3").style.backgroundColor = '#f00'; //red
	}


	firebase.initializeApp(config);

	firebase.database().ref('PD/' + id).on('value', ts_measures => {


		let timestamps = [];
		let values = [];

		ts_measures.forEach(ts_measure => {
			//console.log(ts_measure.val().timestamp, ts_measure.val().value);
			// console.log("push")
			let dBA = ts_measure.val().dBA
			let ts = ts_measure.val().timestamp
			let arc_counter = ts_measure.val().arc_counter
			let medium = ts_measure.val().medium
			let high = ts_measure.val().high
			let dB_one_minute = ts_measure.val().average_dB_one_minute
			let dB_min = ts_measure.val().dB_min
			let dB_max = ts_measure.val().dB_max

			timestamps.push(moment((ts)).format('YYYY-MM-DD HH:mm:ss'));
			values.push(arc_counter);
			document.getElementById("dB_min").innerHTML = dB_min;
			document.getElementById("dB_max").innerHTML = dB_max;
			document.getElementById("average_dB").innerHTML = dB_one_minute[1];

			document.getElementById("durasi").innerHTML = moment((ts)).format('YYYY-MM-DD HH:mm:ss');
			document.getElementById("countVolt").innerHTML = arc_counter
			if (medium && high) {
				// console.log('high')
				message = "Critical";
				merah();
			} else if (medium && !high) {
				// console.log('med')

				message = "Moderate";
				kuning();
			} else {
				// console.log('low')
				message = "Normal";
				hijau();
			}
			document.getElementById("message").innerHTML = message;


		});

	});
</script>
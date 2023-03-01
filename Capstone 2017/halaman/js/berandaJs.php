<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>

<script src="assets/js/leaflet-panel-layers-master/src/leaflet-panel-layers.js"></script>
<script src="assets/js/leaflet.ajax.js"></script>
<script src="assets/js/firebase/firebase-app.js"></script>
<script src="assets/js/firebase/firebase-database.js"></script>
<script src="assets/js/moment/moment.js"></script>

<script type="text/javascript">
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

	firebase.initializeApp(config);

	// <?php
		$db->where("id_device", 2);
		$data = $db->get("device", null, 'lokasi');
		?>

	var all_data = <?= json_encode($all_data, JSON_PRETTY_PRINT) ?>;
	var test = <?= json_encode($data, JSON_PRETTY_PRINT) ?>;

	var lokasi = test[0]['lokasi']

	var map = L.map('mapid').setView([-7.797068, 110.370529], 12);
	var mainLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: 'mapbox/streets-v11',
		accessToken: 'pk.eyJ1IjoiYWZyaXphbG9reSIsImEiOiJja2tnZDdqYW8wZDVqMm9sYWk5eHI3ODZlIn0.mzPjVy5zJUnJgrwuIQn89g'
	});
	map.addLayer(mainLayer);

	L.geoJSON(all_data, {
		pointToLayer: function(feature, latlng) {
			state = feature.properties.state
			console.log(feature, state)
			return L.marker(latlng, {
				icon: new L.Icon({
					iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${state}.png`,
					shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
					iconSize: [25, 41],
					iconAnchor: [12, 41],
					popupAnchor: [1, -34],
					shadowSize: [41, 41]
				})
			});
		},
		onEachFeature: function(feature, layer) {
			if (feature.properties && feature.properties.name) {
				layer.bindPopup(feature.properties.popUp);
			}
		}
	}).addTo(map);
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- HERE JavaScript Libs & Style Sheets-->
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <!-- HERE JavaScript Libs & Style Sheets end-->
	
    <title>Bálint Dániel HL6ENQ</title>
</head>

<body>
    <!--In the div HERE Map will be rendered-->
    <div style="width: 100%;height: 400px; border: 2px solid black;" id="mapContainer"></div>
    <script>
        //Step 1: initialize communication with the platform
        var platform = new H.service.Platform({
            apikey: 'wAgV_WKZwm-BGWBiJpml9EJDtoWqVIPB4qHDAHAj4Vs'
        });
        var defaultLayers = platform.createDefaultLayers();
        //Step 2: initialize a map - this map is centered over Europe
        var map = new H.Map(document.getElementById('mapContainer'),
            defaultLayers.vector.normal.map,
            {
                center: { lat: 47.502618, lng: 19.042876 },
                zoom: 6,
                pixelRatio: window.devicePixelRatio || 1
            }
        );
        // This adds a resize listener to make sure that the map occupies the whole container
        window.addEventListener('resize', () => map.getViewPort().resize());
        //Step 3: make the map interactive
        // MapEvents enables the event system
        // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
        var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

        // Create the default UI components
        var ui = H.ui.UI.createDefault(map, defaultLayers);
		

        // Ikon definiálása
        var icon = new H.map.Icon('https://cdn3.iconfinder.com/data/icons/tourism/eiffel200.png');

		<?php
			$file = "helyek.txt";
			$contents = file_get_contents($file);
			$lines = explode("\n", $contents);
				
			foreach($lines as $word) {
				$data = explode(";", $word);
				//echo $word;
				//echo $data[0]." és ".$data[1]."\n";
				// Itt egyben létrehoz egy markert amit felrak a térképre
				echo "map.addObject(new H.map.Marker({ lat: ".$data[0].", lng: ".$data[1]." }, { icon: icon }));\n";
			}
		?>

    </script>
	
	<script>
		window.onload = function () {

			var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				title:{
					text: "Egyes napokon hány helyen voltam"
				},
				axisX:{
					title:"Melyik napon",
					valueFormatString: "DD MMM"
				},
				axisY: {
					title: "Helyek",
					scaleBreaks: {
						autoCalculate: true
					}
				},
				data: [{
					type: "line",
					xValueFormatString: "DD MMM",
					color: "#F08080",
					dataPoints: [
						<?php
							$file = "helyek.txt";
							$contents = file_get_contents($file);
							$lines = explode("\n", $contents);							
							$dates = [];
							$numbers = [];
								
							foreach($lines as $word) {
								$data = explode(";", $word);
								$count = 0;
								
								foreach($lines as $word2){
									$data2 = explode(";", $word2);
									if(date("Y/m/d", (int)($data2[2])/1000) == date("Y/m/d", (int)($data[2])/1000)){
										//echo date("d/m/Y", (int)($data2[2])/1000)." és ".date("d/m/Y", (int)($data[2])/1000)."\n";
										$count = $count+1;
										if(!in_array(date("Y/m/d", (int)($data2[2])/1000), $dates)){
											array_push($dates, date("Y/m/d", (int)($data2[2])/1000));
										}
									}
								}	
								array_push($numbers, $count);
							}
							
						
							sort($dates);
							for ($x = 0; $x < count($dates); $x++) {		
								$dateNew = DateTime::createFromFormat('Y-m-d', $dates[$x]);															
								$bla = explode('/', $dates[$x]);
								$k = $bla[0].", ".$bla[1].", ".$bla[2];
								echo "{ x: new Date(".$k."), y: ".$numbers[$x]." },";
							}
							
							// k
							
						?>	
					]
				}]
			});
			chart.render();

			}
			
			
			
		</script>
		<div id="chartContainer" style="height: 300px; width: 100%;"></div>		
</body>

</html>
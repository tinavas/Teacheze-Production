  <script type='text/javascript' src='http://www.google.com/jsapi'></script>
  <script type='text/javascript'>
   google.load('visualization', '1', {'packages': ['geomap']});
   google.setOnLoadCallback(drawMap);

    function drawMap() {
      var data = new google.visualization.DataTable();
      data.addRows(<?php echo mysql_num_rows($result);?>);
      data.addColumn('string', 'State');
      data.addColumn('number', 'Bribes Reported');
	  <?php
	  $cnt_a = 0;
	  while ($row = db_fetch_object($result)) 
	  {
		  ?>
		  data.setValue(<?php echo $cnt_a;?>, 0, '<?php echo $row->state_name;?>');
      	  data.setValue(<?php echo $cnt_a;?>, 1, <?php echo $row->bribes;?>);
		  <?php
		  $cnt_a++;
	  }	  
	  ?>
	  
	  /*
      data.setValue(0, 0, 'Mumbai');
      data.setValue(0, 1, 200);
      data.setValue(1, 0, 'Delhi');
      data.setValue(1, 1, 300);
      data.setValue(2, 0, 'Chennai');
      data.setValue(2, 1, 400);
      data.setValue(3, 0, 'Andhra Pradesh');
      data.setValue(3, 1, 10);
      data.setValue(4, 0, 'Orissa');
      data.setValue(4, 1, 600);
      data.setValue(5, 0, 'Jammu');
      data.setValue(5, 1, 700);
	  */
	  
      var options = {};
      options['region'] = 'IN';
	  options['width'] = '710';
	  options['height'] = '600';
      options['colors'] = [0xFF8747, 0xFFB581, 0xc06000]; //orange colors
      options['dataMode'] = 'markers';

      var container = document.getElementById('map_canvas');
      var geomap = new google.visualization.GeoMap(container);
      geomap.draw(data, options);
	  
	  /*
	  test more
	  	  	 
	  google.visualization.events.addListener(geomap, 'regionClick', regionHandler);

	 function regionHandler(e) {
			  alert('A table row was selected');
		}
		*/
    };


  </script>

  <div id='map_canvas'><img src="<?php echo url('sites/all/themes/ipab_client/');?>images/loading.gif" /></div>

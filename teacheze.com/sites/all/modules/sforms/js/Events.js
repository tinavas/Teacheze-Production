dojo.require("esri.map");
dojo.require("esri.tasks.identify");
dojo.require("dijit.layout.ContentPane");

var map, identifyTask, identifyParams, symbol;
var layer2results, layer3results, layer4results;
var toolBar = null;
var zoomExtent = null;


function init() {
    // debugger
   
     map = new esri.Map("mapDiv", {});

     // var imageryPrime = new esri.layers.ArcGISTiledMapServiceLayer("http://10.10.10.133/ArcGIS/rest/services/Ij_Final/MapServer");
     // var imageryPrime = new esri.layers.ArcGISDynamicMapServiceLayer("http://10.10.10.133/ArcGIS/rest/services/Ij_Final/MapServer/");
    var imageryPrime = new esri.layers.ArcGISDynamicMapServiceLayer("http://124.153.103.146/ArcGIS/rest/services/India_IPAD/MapServer/");
    //var imageryPrime = new esri.layers.ArcGISDynamicMapServiceLayer("http://server.arcgisonline.com/ArcGIS/rest/services/ESRI_StreetMap_World_2D/MapServer");
    map.addLayer(imageryPrime);
    esriConfig.defaults.map.slider = { left: "10px", top: "0px", width: null, height: "30px" };
    dojo.connect(map, "onLoad", initFunctionality);
}

function ReadEvents(fname) {
    //debugger
    var xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
    xmlDoc.async = "false"
    xmlDoc.load(fname)
    nodes = xmlDoc.documentElement.childNodes
//    var markerSymbol = new esri.symbol.PictureMarkerSymbol('button-06.gif', 10, 10);
    var markerSymbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE, 10,
               new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, 
               new dojo.Color([255,0,0]), 1),
               new dojo.Color([0,255,0,0.25]));

    for (var i = 0; i < nodes.length; i++) {
        var attributes = { Name: nodes[i].childNodes[0].text, lat: nodes[i].childNodes[1].text, lon: nodes[i].childNodes[2].text, Description: nodes[i].childNodes[3].text };
        if ((nodes[i].childNodes[1].text != null) && (nodes[i].childNodes[2].text != null) && (nodes[i].childNodes[3].text != null)) {
            var size = nodes[i].childNodes[3].text;
            var markerSymbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE, size,
               new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, 
               new dojo.Color([255,0,0]), 1),
               new dojo.Color([0,255,0,0.25]));
            // CREATE MAP LOCATION
            var location = new esri.geometry.Point(nodes[i].childNodes[2].text, nodes[i].childNodes[1].text);
            // CREATE GRAPHIC
            var graphic = new esri.Graphic(location, markerSymbol, attributes);
            graphic.titleField = "Event";
            
            // ADD GRAPHIC TO MAP
            map.graphics.add(graphic);
            var extentGeom = new esri.geometry.Multipoint(map.spatialReference);
            extentGeom.addPoint(location);
            if (zoomExtent == null) {
                zoomExtent = extentGeom.getExtent();
            }
            else {
                zoomExtent = zoomExtent.union(extentGeom.getExtent());
            }
        }
    }
        //we've done all the queries and zoomExtent should equal the extent of our data
        map.setExtent(zoomExtent);
}

function initFunctionality(map) {
    dojo.connect(map.graphics, "onClick", graphicsOnClick);
    xmlLoad('http://ipaidabribe.com/Event.xml'); //Change The XML File Path
    //xmlLoad('http://10.10.10.10/Event/Event.xml');
}

            // GRAPHIC ON CLICK
            function graphicsOnClick(evt){
         //  debugger
                // GRAPHIC
                var graphic = evt.graphic;
                
                // SET INFOWINDOW SIZE
                map.infoWindow.resize(160, 125);
                // SET INFOWINDOW TITLE
                var titleTemplate = graphic.titleField + ': ${' + graphic.titleField + '}';
                map.infoWindow.setTitle("Event");
                // ATTRIBUTE GRID NODE
                var gridNode = dojo.doc.createElement('div');
                var content = "<b>X:</b>" + 
                    graphic.geometry.x + "<br/><b>Y:</b>" + 
                    graphic.geometry.y + "<br/><b> Name:</b>" + 
                    graphic.attributes.Name + "<br/><b> Description:</b>" + 
                    graphic.attributes.Description + "<br/>";
                map.infoWindow.setContent(content);
                
                // SET INFOWINDOW LOCAITON
                var mapPnt = (graphic.geometry.type == 'point') ? graphic.geometry : graphic.geometry.getExtent().getCenter();
                var scrPnt = map.toScreen(mapPnt);
                map.infoWindow.show(scrPnt, map.getInfoWindowAnchor(scrPnt));
               
            }


            function Hide() {
                if (map == null) {
                    document.getElementById('mapDiv').style.visibility = 'visible';
                    dojo.addOnLoad(init);
                }
            
            }

            function xmlProcessor(xmlDoc) {
           
              //debugger
                //Do whatever you want with the XML data...
                //nodes = xmlDoc.documentElement.childNodes;
                nodes =xmlDoc.getElementsByTagName('Marker'); //Get all the 'item's
                //alert(nodes.length);
                if (nodes != null) {
                    for (var i = 0, l = nodes.length; i < l; i++) {
                     
                        var child = nodes[i];
                        
                        if (child.nodeType === 1) {
                        //alert(child.nodeName);
                            var attributes = { Name: child.childNodes[1].textContent, lat: child.childNodes[3].textContent, lon: child.childNodes[5].textContent, Description: child.childNodes[7].textContent };

                            var location = new esri.geometry.Point(child.childNodes[5].textContent, child.childNodes[3].textContent);
                            //var markerSymbol = new esri.symbol.PictureMarkerSymbol('button-06.gif', 10, 10);
                                var markerSymbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE, 10,
                                       new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, 
                                       new dojo.Color([255,0,0]), 1),
                                       new dojo.Color([0,255,0,0.25]));

                            var graphic = new esri.Graphic(location, markerSymbol, attributes);
                            graphic.titleField = "Event";
                            map.graphics.add(graphic);
                            var extentGeom = new esri.geometry.Multipoint(map.spatialReference);
                            extentGeom.addPoint(location);
                            if (zoomExtent == null) {
                                zoomExtent = extentGeom.getExtent();
                            }
                            else {
                                zoomExtent = zoomExtent.union(extentGeom.getExtent());
                            }
                        }
                    }
                }

                map.setExtent(zoomExtent);
            }

            function xmlLoad(xml_file) {
                var xmlDocument = "";
                feed_file = xml_file;
                if (document.implementation.createDocument) {//Firefox
//                    xmlDocument = document.implementation.createDocument('', 'doc', null);
//                    xmlDocument.load(xml_file);
//                    xmlProcessor(xmlDocument);
                      var xmlhttp = new window.XMLHttpRequest();
                      xmlhttp.open("GET",xml_file,false);
                      xmlhttp.send(null);
                      xmlDocument = xmlhttp.responseXML.documentElement;
                      xmlProcessor(xmlDocument);
                }

                else { 
               // alert('hi');
                    ReadEvents(xml_file);
                }
              
            }
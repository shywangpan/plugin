var xmlDoc;
var TopnodeList;
var citys;
var countyNodes;
var nodeindex = 0;
var childnodeindex = 0;

function cascdeInit(v1,v2,v3) {

    xmlDoc = loadXmlFile('source/plugin/fightgroups/template/amyarea.xml');
    var dropElement1 = document.getElementById("sel-provance");
    var dropElement2 = document.getElementById("sel-city");
    var dropElement3 = document.getElementById("sel-area");
    RemoveDropDownList(dropElement1);
    RemoveDropDownList(dropElement2);
    RemoveDropDownList(dropElement3);
    if (window.ActiveXObject) {
        TopnodeList = xmlDoc.selectSingleNode("address").childNodes;
    }
    else {
        TopnodeList = xmlDoc.childNodes[0].getElementsByTagName("province");      
    }
    if (TopnodeList.length > 0) {
 
        var county;
        var province;
        var city;
        for (var i = 0; i < TopnodeList.length; i++) {
      
            county = TopnodeList[i];          
            var option = document.createElement("option");
            option.value = county.getAttribute("name");
            option.text = county.getAttribute("name");
            if (v1 == option.value) {
                option.selected = true;
                nodeindex = i;
            }
            dropElement1.add(option);
        }
        if (TopnodeList.length > 0) {

            citys = TopnodeList[nodeindex].getElementsByTagName("city")
            for (var i = 0; i < citys.length; i++) {
                var id = dropElement1.options[nodeindex].value;

                province = TopnodeList[nodeindex].getElementsByTagName("city");
                var option = document.createElement("option");
                option.value = province[i] .getAttribute("name");
                option.text = province[i].getAttribute("name");
                if (v2 == option.value) {
                    option.selected = true;
                    childnodeindex = i;
                }
                dropElement2.add(option);
            }
			selectcounty(v3);
        }
    }
}


function selectCity() {
    var dropElement1 = document.getElementById("sel-provance");
    var name = dropElement1.options[dropElement1.selectedIndex].value;     
    countyNodes = TopnodeList[dropElement1.selectedIndex];      
    var province = document.getElementById("sel-city");
    var city = document.getElementById("sel-area");
    RemoveDropDownList(province);
    RemoveDropDownList(city);
    var citynodes;
    var countycodes;
    if (window.ActiveXObject) {
        citynodes = xmlDoc.selectSingleNode('//address/province [@name="' + name + '"]').childNodes;
    } else {
        citynodes = countyNodes.getElementsByTagName("city")
    }
    if (window.ActiveXObject) {
        countycodes = citynodes[0].childNodes;
    } else {
        countycodes = citynodes[0].getElementsByTagName("county")
    }
  
    if (citynodes.length > 0) {
       
        for (var i = 0; i < citynodes.length; i++) {
            var provinceNode = citynodes[i];
            var option = document.createElement("option");
            option.value = provinceNode.getAttribute("name");
            option.text = provinceNode.getAttribute("name");
            province.add(option);
        }
        if (countycodes.length > 0) {
        
            for (var i = 0; i < countycodes.length; i++) {
                var dropElement2 = document.getElementById("sel-city");
                var dropElement3 = document.getElementById("sel-area");
              
                
                //alert(cityNode.childNodes.length); 
                var option = document.createElement("option");
                option.value = countycodes[i].getAttribute("name");
                option.text = countycodes[i].getAttribute("name");
                dropElement3.add(option);
            }
        }
    }
}

function selectcounty(v3) {
    var dropElement1 = document.getElementById("sel-provance");
    var dropElement2 = document.getElementById("sel-city");
    var name = dropElement2.options[dropElement2.selectedIndex].value;
    var city = document.getElementById("sel-area");  
    var countys = TopnodeList[dropElement1.selectedIndex].getElementsByTagName("city")[dropElement2.selectedIndex].getElementsByTagName("county")
    RemoveDropDownList(city);
    for (var i = 0; i < countys.length; i++) {
        var countyNode = countys[i];
        var option = document.createElement("option");
        option.value = countyNode.getAttribute("name");
        option.text = countyNode.getAttribute("name");
        if(v3==option.value){
        	option.selected=true;
        }
        city.add(option);
    }
}
function RemoveDropDownList(obj) {
    if (obj) {
        var len = obj.options.length;
        if (len > 0) {  
            for (var i = len; i >= 0; i--) {
                obj.remove(i);
            }
        }
    }
}

function loadXmlFile(xmlFile) {
    var xmlDom = null;
    if (window.ActiveXObject) {
        xmlDom = new ActiveXObject("Microsoft.XMLDOM");
        xmlDom.async = false;
        xmlDom.load(xmlFile) || xmlDom.loadXML(xmlFile);=
    } else if (document.implementation && document.implementation.createDocument) {
        var xmlhttp = new window.XMLHttpRequest();
        xmlhttp.open("GET", xmlFile, false);
        xmlhttp.send(null);
        xmlDom = xmlhttp.responseXML;
    } else {
        xmlDom = null;
    }
    return xmlDom;
}
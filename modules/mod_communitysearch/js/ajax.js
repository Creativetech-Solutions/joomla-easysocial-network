//++++++++++++++++++++++++++ Mail Section +++++++++++++++++++++++++++

var xmlHttp
//******************************** Check Browser Compability******************
function GetXmlHttpObject()
{
	
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
//******************************** Mail Section Select ******************

function getcountrycode(pcountry_id)
{

	xmlHttp = GetXmlHttpObject();
	xmlHttp.onreadystatechange =function() 
	{		
		if (xmlHttp.readyState == 4) 
		{
			var strr = xmlHttp.responseText;
			document.getElementById('countryn').value=strr.trim();
			cntr = strr.trim();
			initialize();
		}
	}
	var modulelive_url=document.getElementById("modulelive_url").value;

	var url = modulelive_url+"index.php?option=com_joomproject&view=registration&task=getCountrycode&country_id="+pcountry_id;
 	xmlHttp.open("GET", url, true)
	xmlHttp.send(null);	
}

function getCountryText(){
	
		var countrySelect = document.getElementById("country_id");
		var selectedTextcountry = countrySelect.options[countrySelect.selectedIndex].text;
		document.getElementById('countrytext').value = selectedTextcountry;
		
}



 
  	
 





 
//========================================EOF Package Detail View==============================//

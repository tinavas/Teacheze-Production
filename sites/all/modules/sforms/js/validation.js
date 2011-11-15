/*
Custom JS - validation functions.
Author :: Dharmendra Patri
*/

var error_container_id = 'validation_errors';//not required if you want to Alert.

var show_alert = false;

var show_all_error = true;

var msg = '';

var phone_regex = /^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/;

/*
should be checked upon dom ready so do it later.
if(show_alert==false)
{
	if(!checkElement(error_container_id))
	{
		alert("Please define the error container id :: "+error_container_id);
	}
	else
	{
		document.getElementById(validation_errors).style.display = 'none';
	}
}
*/

//as by default we check for alphanumeric characters - this is autoincluded in this.
function checkEmpty(id_d,message)
{
	var data_d = getValue(id_d);
	
	if(checkEmptyAll(id_d,message))
	{
		if(!alphaNumericCheck(id_d))
		{
			message += ' Enter alpha-numeric characters only';
			takeCareOfMsg(message);		
			return false;
		}
	}
	else
	{
		//not required here
	}
}

//this function will check weather a number is empty or not, means 0 values are not allowed here.
function checkEmptyNumber(id_d,message)
{
	var data_d = getValue(id_d);
	
	if(checkEmptyAll(id_d,message))
	{
		if(parseFloat(data_d)<=0)
		{
			takeCareOfMsg(message);
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}

//dont check for alphanumeric validation here.
function checkEmptyAll(id_d,message)
{
	var data_d = getValue(id_d);
	
	if(data_d=='')
	{
		takeCareOfMsg(message);
		return false;
	}
	else
	{
		return true;
	}
}

//allow akoha numberic and few more characters like _ -
function alphaNumericCheck(id_d)
{
	var data_d = getValue(id_d);
	var regex=/^[0-9A-Za-z_, -]+$/; //^[a-zA-z]+$/
	if(regex.test(data_d))
	{
		return true;
	} 
	else 
	{
		return false;
	}
}

function validatePhone(id_d,message)
{
	var number = getValue(id_d);
	
	if(number.match(phone_regex))
	{
		return true;
	}
	else
	{
		takeCareOfMsg(message);
		return false;
	}
}

function checkNumber(id_d,message)
{
	var number = getValue(id_d);
	
	if(parseInt(number)==number)
	{
		return true;
	}
	else
	{
		takeCareOfMsg(message);
		return false;
	}
}

function checkFloat(id_d,message)
{
	var number = getValue(id_d);
	
	if(parseFloat(number)==number)
	{
		return true;
	}
	else
	{
		takeCareOfMsg(message);
		return false;
	}
}

function checkEmail(id_d,message)
{
	var m_regex = '^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$';
	var email_d = getValue(id_d);
	if(email_d.match(m_regex))
	{
		return true;
	}
	else
	{		
		takeCareOfMsg(message);
		return false;
	}
}

function checkURL(id_d,message)
{
	if(isURL(id_d))
	{
		return true;
	}
	else
	{
		takeCareOfMsg(message);
		return false;
	}
}


//chck if a given checkbox is checked
function checkCheckbox(id_d,message)
{
	if(document.getElementById(id_d).checked)
	{
		return true;
	}
	else
	{
		takeCareOfMsg(message);
		return false;
	}
}

function checkElement(id_d)
{
	if(document.getElementById(id_d))
	return true;
	else
	return false;
}

function getValue(id_d)
{
	var value_d = trim(document.getElementById(id_d).value);
	
	return value_d;
	
}

// Removes leading whitespaces
function LTrim( value )
{
	
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
	
}

// Removes ending whitespaces
function RTrim( value ) 
{
	
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
	
}

// Removes leading and ending whitespaces
function trim( value )
{
	
	return LTrim(RTrim(value));
	
}

function takeCareOfMsg(message)
{
	if(show_alert==false || (show_alert==true && show_all_error==true))
	{
		if(show_alert==true)
		{			
			msg += message+'\n';
		}
		else
		{
			msg += '<p class="validation_error">'+message+'</p>';
		}
	}
	else if(show_alert==true && show_all_error==false)
	{
		alert(msg);
		document.getElementById(id_d).focus();
	}
}

function showMessage()
{
	if(msg!='')
	{
		if(show_alert==true)
		{
			alert(msg);
			return false;
		}
		else
		{
			msg = '<div class="hide"><a href="#" onclick="hideErrorBlock();">Hide</a></div>'+msg;
			document.getElementById(error_container_id).innerHTML = msg;
			document.getElementById(error_container_id).style.display = 'block';
			scroll(0,0);
			return false;
		}
	}
	
	return true;
}

function hideErrorBlock()
{
	document.getElementById(error_container_id).style.display = 'none';
}

function isURL(id_d) 
{
	var argvalue = getValue(id_d);
 	
	if (argvalue.indexOf(" ") != -1)
	return false;
	else if (argvalue.indexOf("http://") == -1)
	return false;
	else if (argvalue == "http://")
	return false;
	else if (argvalue.indexOf("http://") > 0)
	return false;
	
	argvalue = argvalue.substring(7, argvalue.length);
	if (argvalue.indexOf(".") == -1)
	return false;
	else if (argvalue.indexOf(".") == 0)
	return false;
	else if (argvalue.charAt(argvalue.length - 1) == ".")
	return false;
	
	if (argvalue.indexOf("/") != -1) {
	argvalue = argvalue.substring(0, argvalue.indexOf("/"));
	if (argvalue.charAt(argvalue.length - 1) == ".")
	return false;
	}
	
	if (argvalue.indexOf(":") != -1) {
	if (argvalue.indexOf(":") == (argvalue.length - 1))
	return false;
	else if (argvalue.charAt(argvalue.indexOf(":") + 1) == ".")
	return false;
	argvalue = argvalue.substring(0, argvalue.indexOf(":"));
	if (argvalue.charAt(argvalue.length - 1) == ".")
	return false;
	}
	
	return true;
}

function imposeMaxLength(Object, MaxLen)
{
  return (Object.value.length <= MaxLen);
}

function limitChars(textid, limit, infodiv)
{
	var text = $('#'+textid).val();	
	var textlength = text.length;
	if(textlength > limit)
	{
		$('#' + infodiv).html('You cannot write more then '+limit+' characters!');
		$('#'+textid).val(text.substr(0,limit));
		return false;
	}
	else
	{
		$('#' + infodiv).html('You have '+ (limit - textlength) +' characters left.');
		return true;
	}
}

/*
  id - ID of the element
  type - image / pdf / documents etc.. we can add more based upon our requirements.
  
*/
function checkFileExt(id_d,type,message)
{
    var filename = getValue(id_d); 
    var filelength = parseInt(filename.length) - 3; 
    var fileext = filename.substring(filelength,filelength + 3); 

    // Check file extenstion 
    
    if(type=='image')
    {
        if (fileext.toLowerCase() != "gif" && fileext.toLowerCase() != "png" && fileext.toLowerCase() != "jpg")
        { 
            takeCareOfMsg(message);
            return false;
        }
        else
        {
            return true;
        }
    } 
}
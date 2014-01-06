//One function
function dispositionChecked(button)
{
	if(document.getElementById(button).checked == true)
	{
		document.getElementById('none').checked = false;
	}
}

//Another function
function dispostionNoneChecked()
{	
	//if this checkbox is checked, no other checkboxes can be checked
	if(document.getElementById('none').checked == true)
	{
		document.getElementById('engChange').checked = false;
		document.getElementById('supply').checked = false;
		document.getElementById('other').checked = false;
	}
}
//A function that checks if the 'other' checkbox is checked or not
function otherChecked()
{
	var otherDesc;
	var fieldArea;
	//the option 'none' cannot be checked if this is
	document.getElementById('none').checked = false;
}

function changeVendor(vendorCode)
{
	document.getElementById(vendorCode).value = document.getElementById('supplier').value;
}

function verify(f)
{
	var errors = false;
	var rField = new Array(45);
	
	rField[0] = "VCAR No";
	rField[1] = "Supplier";
	rField[2] = "Supplier";
	rField[3] = "Fargo Part Number";
	rField[4] = "Revision";
	rField[5] = "Vendor Part Number";
	rField[6] = "Order Date";
	rField[7] = "Description";
	rField[8] = "Serial Number";
	rField[9] = "Price Per Unit";
	rField[10] = "P.O. Number";
	rField[11] = "Buyer";
	rField[12] = "Inspection Level";
	rField[13] = "Qty Received";
	rField[14] = "Qty Rejected";
	rField[15] = "Account Number";
	rField[16] = "Date";
	rField[17] = "Description of Nonconformance";
	rField[18] = "Root Cause";
	rField[19] = "Engineering Change Order";
	rField[20] = "Supplier Corrective Action";
	rField[21] = "Other";
	rField[22] = "None";
	rField[23] = "Repair";
	rField[24] = "Rework";
	rField[25] = "Use As Is";
	rField[26] = "Scrap";
	rField[27] = "Return to Vendor";
	rField[28] = "Replacement Required";
	rField[29] = "Actionee";
	rField[30] = "Due Date";
	rField[31] = "Project";
	rField[32] = "Project Date";
	rField[33] = "Purchasing";
	rField[34] = "Purchasing Date";
	rField[35] = "Quality";
	rField[36] = "Quality Date";
	rField[37] = "Operation";
	rField[38] = "Operation Date";
	rField[39] = "Technical";
	rField[40] = "Technical Date";
	rField[41] = "Production Control";
	rField[42] = "Production Control Date";
	rField[43] = "Prepared By";
	rField[44] = "Prepared Date";
	
	var incrNo;
	var supplier;
	var supplierHidden;
	var fPartNo;
	var rev;
	var vPartNo;
	var orderDate;
	var desc;
	var serial_no; 
	var pricePerUnit;
	var buyer;
	var inspectionLevel;
	var poNo;
	var qtyReceived;
	var qtyRejected;
	var account;
	var date;
	var nonConformance;
	var rootCause;
	var correctiveAction;
	var eng;
	var sCar;
	var other;
	var none;
	var repair;
	var rework;
	var use;
	var scrap;
	var returnToVendor;
	var replacementRequired;
	var actionee;
	var dueDate;
	var project;
	var projectDate;
	var purchase;
	var purchaseDate;
	var quality;
	var qcDate;
	var operation;
	var operationDate;
	var techical;
	var techDate;
	var prodControl;
	var prodDate;
	var preparedBy;
	var preparedDate;
	
	var firstError = false;
	var errorField;
	var blankCheck = false;
	var blankCount = 0;
		
	for(var i = 0; i < f.length; i++)
	{
		
		var e = f.elements[i];
		if ((e.value == null) || (e.value == "") || isblank(e))
		{
			blankCheck = true;
		}
		else
		{
			blankCheck = false;
		}
	//Tests the validity of required fields.
		if ((e.type == "text" || e.type == "textarea") && e.required)
		{
			if (blankCheck)
			{
				//alert(rField[i] + " 1");
				errors = true;
				if (!firstError)
				{
					errorField = e;
					alert(i + ". Please enter the " + rField[i]);
					errorField.focus();
					firstError = true;
				}
				continue;
			}
			else
			{
				if(e.dateCheck)
				{
					firstError = checkDateFormat(e, rField[i], firstError);
				}
			}					
			if(e.numberTest || e.numeric)
				firstError = numericTest(e, rField[i], firstError);
		}
		else
		{
			if(!blankCheck)
			{
				if(e.dateCheck)
				{
					firstError = checkDateFormat(e, rField[i], firstError);
				}
				
				if(e.numberTest || e.numeric)
					firstError = numericTest(e, rField[i], firstError);
			}
		}
	}	
	
	if (firstError)
	{
		errors = true;
		errorField = e;
	}
	if (!errors)
		return true;
	else
		return false;
}


function numericTest(e, fieldName, firstError)
{
	var v = e.value;
	if (isNaN(v))
	{
		if (!firstError)
		{
			alert("The " + fieldName + " must be numeric.");
			e.focus();
			firstError = true;
		}
	}
	
	if (v <= 0)
	{
		if (!firstError)
		{
			alert("The " + fieldName + " must be greater than 0.");
			e.focus();
			firstError = true;
		}		
	}
	return firstError;
}

function isblank(e)
{
	s = e.value;
	for (var b = 0; b < s.length; b++)
	{
		var c = s.charAt(b);
		if((c != " ") && (c != "\n") && (c != "\t")) 
			return false;
	}
	return true;
}

function checkDateFormat(e, fieldName, firstError)
{
	var v = e.value;
	var dateFormat = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/;
	if (!dateFormat.test(v))
	{
		alert("The " + fieldName + " must be in the following format: mm-dd-yyyy");
		e.focus();
		firstError = true;
	}
	return firstError;
}

function setBuyer(poNumber)
{

	var poStart = poNumber.substring(0,1);
	switch (poStart)
	{
		case("1"):
			document.getElementById("buyer").value = "Michelle Krasley";
			break;
		case("3"):
			document.getElementById("buyer").value = "Bob Sabre";
			break;
		case("4"):
			document.getElementById("buyer").value = "Gerri Romano";
			break;
	}
}

function openGuide()
{
	window.open("color_guide.html", "", "width = 400, height = 150, left = 100, top = 10");
}

/*function uploadCAR(car, carNum)
{
	if(car != '' && car != null)
	{
		var xmlhttp;
		if(window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	
		xmlhttp.onreadystatechange = function()
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
				document.getElementById("carFile").innerHTML = xmlhttp.responseText;
		}
		xmlhttp.open("GET", "upload_car.php?car=" + car + "&carNum=" + carNum, true);
		xmlhttp.send();
	}
	else
		alert("Please select a file before attempting to upload");

}*/
/*function uploadCAR(car)
{
	alert("<embed src = '" + car + "'>");
	if(car != '' && car != null)
	{
		document.getElementById("carDisplay").src = car;
		document.getElementById("carDisplay").style.display = "inline";
	}
}*/

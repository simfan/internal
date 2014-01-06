function verify(f)
{
	var errors = false;
	var rField = new Array(31);
	
	rField[0] = "ITR No";
	rField[1] = "Inspection Date";
	rField[2] = "Station";
	rField[3] = "Department Manager";
	rField[4] = "Inspector";
	rField[5] = "Part No";
	rField[6] = "Operator";
	rField[7] = "Inspection Summary";
	rField[8] = "Discrepancies";
	rField[9] = "Interim Containment";
	rField[10] = "Root Cause";
	rField[11] = "Corrective Action/Preventive Action";
	rField[12] = "Result of Preventive Action";
	rField[13] = "Part No";
	rField[14] = "Cost";
	rField[15] = "Qty";
	//rField[16] = "Total Cost";
	rField[16] = "Part No2";
	rField[17] = "Cost2";
	rField[18] = "Qty2";
	//rField[20] = "Total Cost2";		
	rField[19] = "Part No3";
	rField[20] = "Cost3";
	rField[21] = "Qty3";
	//rField[24] = "Total Cost4";		
	rField[22] = "Part No4";
	rField[23] = "Cost4";
	rField[24] = "Qty4";
	//rField[28] = "Total Cost4";		
	rField[25] = "Manufacturing Hours";
	rField[26] = "Rework Cost";
	rField[27] = "Department Manager";
	rField[28] = "Date";
	rField[29] = "QA Department Head";
	rField[30] = "Date";
	

	var firstError = false;
	var errorField;
	var blankCheck = false;
	var blankCount = 0;
	var costRow = 0;//Counts the field number in each row of the material costs sub table
	var costFields = 0;	//Counts the filled number of fields in each material costs row
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
		if(!e.optional)
		{
			alert("Required");
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
					alert("Date Check");
					firstError = checkDateFormat(e, rField[i], firstError);
				}
							
				if(e.numberTest || e.numeric)
					firstError = numericTest(e, rField[i], firstError);
			
				if(e.costCheck)
				{
					//costRow++;
					//firstError = materialCostTest(e, costRow, costFields, firstError);
					var v = e.value;
					costRow++;
					//alert("Field Value: " + v + ". Field #" + costRow);

					if(v != '')
					{
						costFields++;
					}
					if(costRow == 3)
					{
						if (costFields == 1 || costFields == 2)
						{
							alert("You must include a Part Name, a cost, and a quantity for material costs");
							e.focus();
							firstError = true;
						}
						else
						{
							costRow = 0;
							costFields = 0;
						}
					}
				}
				if(e.signTest)
				{
					var v = e.value;
					signRow++;
					alert("Field Value: " + v + ". Field #" + signRow);
					if(v != '')
					{
						signFields++;
					}
					if(signRow == 2)
					{
						if (signFields == 1)
						{
							alert("You must include both a signature and date");
							e.focus();
							firstError = true;
						}
						else
						{
							signRow = 0;
							signFields = 0;
						}
					}
				}											
			}
			if(firstError)
				alert("An error is in a required field");
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
			if(e.costCheck)
			{
				//firstError = materialCostTest(e, costRow, costFields, firstError);
				var v = e.value;
				costRow++;
				//alert("Field Value: " + v + ". Field #" + costRow);

				if(v != '')
				{
					costFields++;
				}
				if(costRow == 3)
				{
					if (costFields == 1 || costFields == 2)
					{
						alert("You must include a Part Name, a cost, and a quantity for material costs");
						e.focus();
						firstError = true;
					}
					else
					{
						costRow = 0;
						costFields = 0;
					}
				}							
			}
			
			if(e.signTest)
			{
				var v = e.value;
				signRow++;
				alert("Field Value: " + v + ". Field #" + signRow);

				if(v != '')
				{
					signFields++;
				}

				if(signRow == 2)
				{
					if (signFields == 1)
					{
						alert("You must include both a signature and date; ");
						e.focus();
						firstError = true;
					}
					else
					{
						signRow = 0;
						signFields = 0;
					}
				}
			}	
		}
	}	
	alert(firstError);
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
function signatureTest(e, signRow,signFields, firstError)
{
	var v = e.value;
	signRow++;
	alert("Field Value: " + v + ". Field #" + signRow);

	if(v != '')
	{
		signFields++;
	}
	if(signRow == 2)
	{
		if (signFields == 1)
		{
			alert("You must include both a signature and date; ");
			e.focus();
			firstError = true;
		}
		else
		{
			signRow = 0;
			signFields = 0;
		}
	}
	return firstError;
}	
function materialCostTest(e, costRow, costFields, firstError)
{
	var v = e.value;
	costRow++;
	alert("Field Value: " + v + ". Field #" + costRow);

	if(v != '')
	{
		costFields++;
	}
	if(costRow == 3)
	{
		if (costFields == 1 || costFields == 2)
		{
			alert("You must include a Part Name, a cost, and a quantity for material costs");
			e.focus();
			firstError = true;
		}
		else
		{
			costRow = 0;
			costFields = 0;
		}
	}
	return firstError;
}

function addRow(tableID)
{
	var table = tableID
	rowCount = table.rows.length;
	row = table.insertRow(rowCount);

	var cell1 = row.insertCell(0);
	var element1 = document.createElement("input");
	element1.type = "text";
	element1.name = "costPN" + rowCount;
	element1.setAttribute("id", "costPN" + rowCount);
	element1.setAttribute("size", 20);
	element1.setAttribute("maxlength", 20);
	cell1.appendChild(element1);
	
	var cell2 = row.insertCell(1);
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.name = "'cost" + rowCount + "'";
	element2.setAttribute("id", "cost" + rowCount);
	element2.setAttribute("size", 8);
	element2.setAttribute("maxlength", 8);
	//element2.setAttribute("onBlur", "calculateTotal(cost" + rowCount + ", costQty" + rowCount + ", totalCost" + rowCount + ")");
	element2.addEventListener('blur', function(e){calculateTotal(element2, element3, element4)}, false);
	cell2.appendChild(element2);
	
	cell2.appendChild(document.createTextNode('  '));
	
	var element3 = document.createElement("input");
	element3.type = "text";
	element3.name = "costQty" + rowCount;
	element3.setAttribute("id", "costQty" + rowCount);
	element3.setAttribute("size", 4);
	element3.setAttribute("maxlength", 4);
	//element3.setAttribute("onBlur", "calculateTotal(cost" + rowCount + ", costQty" + rowCount + ", totalCost" + rowCount + ")");
	//element3.addEventListener('blur', calculateTotal(element2, element3, element4));
	cell2.appendChild(element3);	
	
	var cell3 = row.insertCell(2);
	var element4 = document.createElement("p");
	element4.name = "totalCost" + rowCount;
	element4.setAttribute("id", "totalCost" + rowCount);
	cell3.appendChild(element4);
}
function calculateTotal(costField, qtyField, totalField, rowCount)
{	
	var cost;
	var qty; 
	var total;
	var nextName = "costPN" + rowCount;
	var nextCost = "cost" + rowCount;
	var nextQty = "costQty" + rowCount;
	
	cost = costField.value;
	qty = qtyField.value;
	if(cost != '' && qty != '')
	{
		total = cost * qty;
		total = Math.round(total*100)/100;
		total = total.toFixed(2);
		totalField.innerHTML = total;
		if(rowCount != 5)
		{
			document.getElementById(nextName).removeAttribute("disabled");
			document.getElementById(nextCost).removeAttribute("disabled");
			document.getElementById(nextQty).removeAttribute("disabled");
		}
		
	}
	else
	{
		totalField.innerHTML = '';
		if(rowCount != 5)
		{		
			document.getElementById(nextName).disabled = true;
			document.getElementById(nextCost).disabled = true;
			document.getElementById(nextQty).disabled = true;	
		}
	}
}

function calculateRework(mfgHours, reworkCost)
{
	var hours = mfgHours.value;
	var cost; 
	if(mfgHours != '')
	{
		var rate = .3;
		cost = hours * rate;
		cost = Math.round(cost * 100)/100;
		cost = cost.toFixed(2);
		reworkCost.innerHTML = cost;
	}
	else
		reworkCost.innerHTML = '';
}
		
	
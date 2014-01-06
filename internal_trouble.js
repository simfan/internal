function verify(f, rowCount)
{
	var errors = false;
	//alert(rowCount);
	//var numFields = 20 + (rowCount * 3);
	
	var rField = new Array(23 + (rowCount * 3));
	rField[0] = "Row Count";
	rField[1] = "ITR No";
	rField[2] = "Inspection Date";
	rField[3] = "Department";
	rField[4] = "Plant";
	rField[5] = "Inspector";
	rField[6] = "Part No";
	rField[7] = "Operator";
	rField[8] = "Defect Code";
	rField[9] = "Defect Code";
	rField[10] = "Defect Qty";
	rField[11] = "Inspection Summary";
	rField[12] = "Discrepancies";
	rField[13] = "Interim Containment";
	rField[14] = "Root Cause";
	rField[15] = "Corrective Action/Preventive Action";
	rField[16] = "Result of Preventive Action";
	for (i = 0; i < rowCount; i++)
	{
		rField[17 + (i * 3)] = "Part No" + i;
		rField[18 + (i * 3)] = "Cost" + i;
		rField[19 + (i * 3)] = "Qty" + i;
	}

	rField[17 + (rowCount * 3)] = "Manufacturing Hours";
	if(navigator.appName == "Microsoft Internet Explorer")
	{
		rField[18 + (rowCount * 3)] = "Department Manager";
		rField[19 + (rowCount * 3)] = "Date";
		rField[20 + (rowCount * 3)] = "QA Department Head";
		rField[21 + (rowCount * 3)] = "Date";
		rField[22 + (rowCount * 3)] = "";
	}
	else
	{
		rField[18 + (rowCount * 3)] = "";
		rField[19 + (rowCount * 3)] = "Department Manager";
		rField[20 + (rowCount * 3)] = "Date";
		rField[21 + (rowCount * 3)] = "QA Department Head";
		rField[22 + (rowCount * 3)] = "Date";
	}

	var firstError = false;
	var errorField;
	var blankCheck = false;
	var blankCount = 0;
	var costRow = 0;//Counts the field number in each row of the material costs sub table
	var costFields = 0;	//Counts the filled number of fields in each material costs row
	var signRow = 0;
	var signFields = 0;
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
							
				if(e.numberTest || e.numeric)
					firstError = numericTest(e, rField[i], firstError);
			
				if(e.costCheck)
				{
					var v = e.value;
					costRow++;

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
				var v = e.value;
				costRow++;

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

function materialCostTest(e, costRow, costFields, firstError)
{
	var v = e.value;
	costRow++;

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

function addRow(tableID, addButton)
{
	var table = tableID
	rowCount = table.rows.length;
	var newRow = rowCount - 1;	
	row = table.insertRow(rowCount);
	//alert("Row Count " + rowCount);
	var cell1 = row.insertCell(0);
	var cell2;
	var cell3;
	var element1 = document.createElement("input");
	var element2;
	var element3;
	var element4;
	element1.type = "text";
	element1.name = "costPN" + newRow;
	element1.setAttribute("id", "costPN" + newRow);
	element1.setAttribute("size", 20);
	element1.setAttribute("maxlength", 20);
	element1.setAttribute("costCheck", true);
	element1.setAttribute("optional", true);
	element1.setAttribute("class", "underline");
	cell1.appendChild(element1);
	
	cell2 = row.insertCell(1);
	element2 = document.createElement("input");
	element2.type = "text";
	element2.name = "cost" + newRow;
	element2.setAttribute("id", "cost" + newRow);
	element2.setAttribute("size", 8);
	element2.setAttribute("maxlength", 8);
	element1.setAttribute("costCheck", true);
	element1.setAttribute("optional", true);
	element2.setAttribute("numeric", true);
	element2.setAttribute("class", "underline right");
	element2.setAttribute("onBlur", "calculateTotal(cost" + newRow + ", costQty" + newRow + ", totalCost" + newRow + ", " + newRow + ")");
	cell2.appendChild(element2);
	
	/*cell2.appendChild(document.createTextNode("   "));
	cell2.appendChild(document.createTextNode("   "));*/
	
	cell3 = row.insertCell(2);
	element3 = document.createElement("input");
	element3.type = "text";
	element3.name = "costQty" + newRow;
	element3.setAttribute("id", "costQty" + newRow);
	element3.setAttribute("size", 4);
	element3.setAttribute("maxlength", 4);
	element1.setAttribute("costCheck", true);
	element1.setAttribute("optional", true);
	element3.setAttribute("numeric", true);
	element3.setAttribute("class", "underline right");
	element3.setAttribute("onBlur", "calculateTotal(cost" + newRow + ", costQty" + newRow + ", totalCost" + newRow + ", " + (rowCount+1) + ")");
	cell3.appendChild(element3);	
	
	cell4 = row.insertCell(3);
	element4 = document.createElement("p");
	element4.name = "totalCost" + newRow;
	element4.setAttribute("id", "totalCost" + newRow);
	element4.setAttribute("class", "right");
	cell4.appendChild(element4);
	
	document.getElementById('add').disabled = true;
	document.getElementById('rowCount').value = rowCount - 1;
}
function calculateTotal(costField, qtyField, totalField, rowCount, browser)
{	
	var cost;
	var qty; 
	var total;
	var nextName = "costPN" + rowCount;
	var nextCost = "cost" + rowCount;
	var nextQty = "costQty" + rowCount;
	cost = costField.value;
	qty = qtyField.value;
	var rowsCount = document.getElementById("rowCount").value;
	if(cost != '' && qty != '')
	{
		total = cost * qty;
		total = Math.round(total*100)/100;
		total = total.toFixed(2);
		totalField.innerHTML = total;
		if (browser == 'IE')
		{
			document.getElementById(nextName).removeAttribute("readonly");
			document.getElementById(nextCost).removeAttribute("readonly");
			document.getElementById(nextQty).removeAttribute("readonly");
		}
		else
		{
			if(rowsCount < 4)
				document.getElementById('add').removeAttribute("disabled");
		}	
	}
	else
		totalField.innerHTML = '';
	
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

/*function calculateGrandTotal()
{
	var rowsCount = document.getElementById("rowCount").value;
	var total;
	var totalField;
	var grandTotal;
	var i;
	var j;
	grandTotal = 0;
	for(i = 0; i < rowsCount; i++)
	{
		j = i + 1;
		totalField = "totalCost" + j;
		total = getElementById(totalField).innerHTML;
		if (total != '')
			total = parseInt(total);
		grandTotal = grandTotal + total;
	}
	document.getElementById("grandTotal").innerHTML = grandTotal;
}*/
		
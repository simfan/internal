function toggleDisplay(divID)
{
	
	if (document.getElementById(divID).style.display == "table")
		document.getElementById(divID).style.display = "none";
		
	else if (document.getElementById(divID).style.display == "none")
		document.getElementById(divID).style.display = "table";
}
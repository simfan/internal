	var input = document.getElementById("car"),  
	   	formdata = false;  

	if (window.FormData) 
	{  
		formdata = new FormData();  
		document.getElementById("uploadCar").style.display = "none";  
	}
	if (input.addEventListener) 
	{  
		input.addEventListener("change", function (evt) {  
			var i = 0, len = this.files.length, img, reader, file;  
			document.getElementById("carFileNotify").innerHTML = "Uploading . . ."  
			for ( ; i < len; i++ ) 
			{  
				file = this.files[i];  
				if ((!!file.type.match(/image.*/)) ||(!!file.type.match(/application\/pdf.*/))) 
				{  
					if ( window.FileReader ) 
					{  
						reader = new FileReader();  
						reader.onloadend = function (e) {
						showUploadedItem(e.target.result);  
						};  
    
						reader.readAsDataURL(file);  
					}  
    
					if (formdata) 
					{  
						formdata.append("car[]", file);  
						formdata.append("incrNo", incrNo.value);
					}  

					if (formdata) 
					{  
						$.ajax({  
				            url: "upload.php",  
				            type: "POST",  
				            data: formdata,
            				processData: false,  
			    	        contentType: false,  
            				success: function (res) {  
	            		   	    document.getElementById("carFileNotify").innerHTML = res;   
	            		   	    formdata = new FormData();
	            			}  
						});  
					}  
				}     
        	}  
                  
		}, false);  
	}    

function showUploadedItem (source) 
{  
	var documentDiv = document.createElement("div"),
		img  = document.createElement("img"),
        embed = document.createElement("embed");  
    var divDoc = document.getElementById("carFile");
//	if(!!source.match(/image.*/)) 
/*	{
        img.src = source;  
        documentDiv.appendChild(img);
    }
 */       
//	if(!!source.match(/application\/pdf.*/))         
//	{
		embed.src = source;
		embed.height = '700';
		embed.width = '700';
		documentDiv.appendChild(embed);
		divDoc.appendChild(documentDiv);
//	}
}
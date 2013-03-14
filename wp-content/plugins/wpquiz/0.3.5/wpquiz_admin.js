// wpquiz_admin.js
// Adds in new questions and adds answers to correct answer select box.


	
function addInputx(x) {
	
	var count = 0;
	
	var elem = document.getElementsByTagName('input'); 
	var myName = "";
	for(var i = 0; i < elem.length; i++)
	{		
		myName = elem[i].name;
		if (myName.indexOf("wpquiz_question") == 0) { count++;}		
	} 
	count++;

	
	var arr = new Array(3,1);	
					
	arr[0] =	'<label for="wpquiz_question' + count +'">Question ' + count + '<span> *</span>: </label>';
	arr[1] = '<input id="wpquiz_question' + count +'" maxlength="200" size="50" name="wpquiz_question' + count + '" value="" />';		
	arr[2] = '<label for="wpquiz_correctA' + count +'">Answers <span> *</span>: </label>';
	arr[3] = '<textarea name = "wpquiz_correctA' + count +  '" id = "correctA' + count +  '" rows="5" cols="50" onchange="changeCorrect(this.id)"></textarea>';		
	arr[4] = '<label for="wpquiz_correctSA' + count + '">Correct answer: <span> *</span>: </label>';
	arr[5] = '<select name = "wpquiz_correctSA' + count +  '" id = "correctSA' + count +  '"><option value=""""></option></select>';		
	arr[6] = "<hr />" + "\n";
	arr[7] = "";

	
	for(var i=0; i< 7; i=i+2)
	{
		var tbl = document.getElementById('wpquiz_tb1');
		var lastRow = tbl.rows.length-2;
		// if there's no header row in the table, then iteration = lastRow + 1
		var iteration = lastRow;
		var row = tbl.insertRow(lastRow);
		  
		// left cell
		var cellLeft = row.insertCell(0);
		var div1 = document.createElement('div');  
		div1.innerHTML = arr[i];
		cellLeft.appendChild(div1);
		  
		// right cell
		var cellRight = row.insertCell(1);
		var div2 = document.createElement('div');
		div2.innerHTML = arr[i+1];
		cellRight.appendChild(div2);				
	}

	//count++;	
}
function changeCorrect(x)
{
	var textarea = document.getElementById(x);
	
	idx =  textarea.id;
	//idy = idx.charAt(idx.length-1);	
	idy = idx.substring(8, 8 + idx.length - 8);	

	
	
	var Tx = document.getElementById ("correctA" + idy);
	
	var Sagee = Tx.value;
	
	var MyAns = Sagee.split("\n");
	
	SAc = document.getElementById ("correctSA" + idy);
	
	SAc.options.length = 0;
	
	for (Ans in MyAns)
	{
	
		SAc.options[Ans] = new Option (MyAns[Ans], Ans);
	
	}
	SAc.selected = "0"
	
	option1 = new Option(Sagee,1)
}
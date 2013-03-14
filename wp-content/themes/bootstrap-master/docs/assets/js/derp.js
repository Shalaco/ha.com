var fontTargetSizer = 
{
	original: Array(),
	originalLH: Array(),
	ctr: 0,
	ctrMax: 2,
	img: Array(),
	button: 'buttonFTS.png'
};

function initFTS(ftsOptions)
{
	optionsFTS(ftsOptions);
	selectChildren();
	var cookieFTS = getCookieFTS('FTS');
	
	if(cookieFTS)
	{
		fontTargetSizer.ctr = parseInt(cookieFTS);
		incFontFTS();
	}
}

function optionsFTS(ftsOptions)
{
	for(obj in ftsOptions)
	{
		// Check for valid options
		if(fontTargetSizer[obj])
		{
			fontTargetSizer[obj] = ftsOptions[obj];
		}
	}
}

function getCookieFTS(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	
	for (i=0;i<ARRcookies.length;i++)
	{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name)
		{
			return unescape(y);
		}
	}
}

function setCookieFTS(c_name, value, exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function selectChildren()
{
	var arr = $('.main-content').find('*').each(function(){
		if($(this).css('font-size'))
		{
			fontTargetSizer.original.push($(this).css('font-size'));
			fontTargetSizer.originalLH.push($(this).css('line-height'));
		}
	});
	
	console.log(fontTargetSizer.original, fontTargetSizer.originalLH);
}

function fontUp()
{
	if(fontTargetSizer.ctr < fontTargetSizer.ctrMax)
	{
		fontTargetSizer.ctr++;
	}
	else
	{
		fontTargetSizer.ctr = 0;
	}
	
	incFontFTS();	
	setCookieFTS('FTS', fontTargetSizer.ctr, 7);
}

function incFontFTS()
{
	if(fontTargetSizer.img.length)
	{
		$(fontTargetSizer.button).attr('src', fontTargetSizer.img[fontTargetSizer.ctr]);
	}
	
	var ctr = 0;
	$('.main-content').find('*').each(function(){
		
			if($(this).css('font-size'))
			{
				$(this).css('font-size', doMath(fontTargetSizer.original[ctr]));
				$(this).css('line-height', doMath(fontTargetSizer.originalLH[ctr]));
				ctr++;
			}
	});
}

function doMath(eleVal)
{
	eleNum = eleVal.substring(0, eleVal.length - 2);
	return parseFloat(eleNum * (1.0 + ((fontTargetSizer.ctr ) / 10.0))) + 'px';
}
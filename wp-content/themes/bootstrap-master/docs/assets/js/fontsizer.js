/*
*
*	FONTSIZER PLUGIN by Mark Udarbe
*
*	USAGE: Call init function then add click event listener to element
*		fontsizer();
*		$('element1').click(fontsizerUp());	// jquery
*		element2.addEventListener('click',fontsizerDn(),false)
*
*	Adds functionality for changing font sizes. This plugin goes through
*	all CSS rules defined in the stylesheets that are hosted on the same
*	domain as the script.
*
*	Fontsizer will only increase the font size from the original values
*	defined in the stylesheets.
*
*	Decreasing will only go down if the font had been increased through
*	an fontsizerUp() call. Font size will not decrease passed the
*	original value.
*
*	AVAILABLE OPTIONS
*	changeAmt - The amount to change font by in %	(int)
*	lineHeight - Flag whether or not to change the line height along with font. Default to true. (boolean)
*	exludes - All the CSS rules that are to be ignored by fontsizer, (an array of strings)
*	maxInc - The maximum number of times the font can increase. Default to 2.	(int)
*
*/

/*	**************************************************	*/
/*																											*/
/*	INIT FUNCTIONS																			*/
/*																											*/
/*	**************************************************	*/

var fontSizer;	// MAIN FONTCHANGER OBJECT

/*
*	Main init function. Creates new fontSizer obj and
*	calls all other init functions.
*
*	@param options - Object array of options
*/
function fontsizer(fsOptions)
{
	fontSizer =
	{
		changeAmt : 10,
		lineHeight: true,
		ctr : 0,				// Counter for how many times the font has increased
		domain : getDomain(),
		excludes : Array(),
		maxInc : 2,
		styleObjs : Array()		// Array that contains objects that point to style objects from CSS, original font size and original line height
	};
	
	fontsizerOptions(fsOptions);
	fontSizer.styleObjs = getStyleObjs(fontSizer.excludes);
//	fontsizerAttach();
	setFontSize(fontSizer.ctr, fontSizer.changeAmt, fontSizer.styleObjs);
}

function fontsizerAttach()
{
	var plusObj = document.getElementById("font-plus");
	var minusObj = document.getElementById("font-minus");
	
	if(plusObj.addEventListener)
	{
		plusObj.addEventListener('click', function() { fontsizerUp() }, false);
	}
	else
	{
		plusObj.attachEvent('onclick', function() { fontsizerUp() });
	}
	
	if(minusObj.addEventListener)
	{
		minusObj.addEventListener('click', function() { fontsizerDn() }, false);
	}
	else
	{
		minusObj.attachEvent('onclick', function() { fontsizerDn() });
	}
}

/*
*	Goes through each object in options and
*	overwrites default value in main fontchanger obj
*
*	@param options - Object array of options
*/
function fontsizerOptions(fsOptions)
{
	for(obj in fsOptions)
	{
		// Check for valid options
		if(fontSizer[obj])
		{
			fontSizer[obj] = fsOptions[obj];
		}
	}
}

/*
*	Get all the styles objects that have a font size,
*	store a pointer to the object, the original font size,
*	and original line height if there is one in an object.
*
*	Push each object into an array.
*
*	@return arr - Array of objects with structure:
*					{
*						ptr,
*						defaultFontSize,
*						defaultLineHeight 	//	optional
*					}
*/
function getStyleObjs(excludes)
{
	var styleObjArr = Array();

	for(var i=0; i<document.styleSheets.length; i++)
	{			
		if(document.styleSheets[i].href != null)
		{	
			if(getDomain(document.styleSheets[i].href) == fontSizer.domain)
			{	
				var styleSheet = document.styleSheets[i];
				//console.log(styleSheet);
				var rules = styleSheet.cssRules ? styleSheet.cssRules : styleSheet.rules;
				
				for(var j=0; j<rules.length; j++)
				{
					if(rules[j].selectorText && (!arrContainsSubStr(excludes, rules[j].selectorText.toLowerCase())))
					{
						var styleObj = {};
						
						if(hasNumberUnits(rules[j].style.fontSize))
						{
							styleObj.defaultFontSize = rules[j].style.fontSize;
							
							if(hasNumberUnits(rules[j].style.lineHeight))
							{
								styleObj.defaultLineHeight = rules[j].style.lineHeight;
							}
							
							styleObj.ptr = rules[j];
							styleObjArr.push(styleObj);
						}
					}
				}
			}
		}
	}
	
	//console.log(styleObjArr);
	return styleObjArr;
}

/*	**************************************************	*/
/*														*/
/*	MAIN FUNCTIONALITY FUNCTIONS						*/
/*														*/
/*	**************************************************	*/

function changeFontSize(styleObj, amount)
{		
	var size = parseFloat(getNumber(styleObj.defaultFontSize));			// Get the number
	var units = styleObj.defaultFontSize.match(/[A-Za-z]+/);			// Get the units
	
	size *= ((100 + amount) / 100);
	styleObj.ptr.style.fontSize = size + units;
}

function changeLineHeight(styleObj, amount)
{
	var lineHeight = parseFloat(getNumber(styleObj.defaultLineHeight));
	var units = styleObj.defaultLineHeight.match(/[A-Za-z]+/);
	
	lineHeight *= ((100 + amount) / 100);
	styleObj.ptr.style.lineHeight = lineHeight + units;
}

function fontsizerDn()
{
	//console.log("fontsizerDn");
	if(fontSizer.ctr > 0)
	{
		setFontSize(--fontSizer.ctr, fontSizer.changeAmt, fontSizer.styleObjs);
	}
}

function fontsizerUp()
{
	//console.log("fontsizerUp");
	if(fontSizer.ctr < fontSizer.maxInc)
	{
		setFontSize(++fontSizer.ctr, fontSizer.changeAmt, fontSizer.styleObjs);
	}
}

function setFontSize(ctr, amount, styleObjs)
{
	for(var i=0; i<styleObjs.length; i++)
	{
		changeFontSize(styleObjs[i], ctr * amount);
		//console.log(styleObjs[i]);
		
		if(fontSizer.lineHeight && styleObjs[i].defaultLineHeight)
		{
			changeLineHeight(styleObjs[i], ctr * amount);
		}

		/*
		*
		*	CUSTOM RULES TO PREVENT THINGS FROM BREAKING GO HERE
		*	Varies per project / page
		*
		*/
	
	}
}

/*	**************************************************	*/
/*														*/
/*	GENERAL HELPER FUNCTIONS							*/
/*														*/
/*	**************************************************	*/

// Check if any strings in an array of strings contains
// the given string.
function arrContainsSubStr(arr, str)
{
	var i = arr.length;
	
	while(i--)
	{
		if(str.indexOf(arr[i]) !== -1)
		{
			return true;
		}
	}
	
	return false;
}

function containsSubStr(str1, str2)
{	
	if(str1.indexOf(str2) !== -1)
	{
		return true;
	}
	
	return false;
}

function containsObj(a, obj)
{
	var i = a.length;
	while (i--)
	{
	   if (a[i] === obj)
	   {
		   return true;
	   }
	}
	return false;
}

function getDomain(url)
{
	if(url)
	{
		return url.split(/\/+/)[1] + '/ha.com/';
	}
	else
	{
		return document.location.href.split(/\/+/)[1] + '/ha.com/';
	}
}

function getStyleElement(name)
{
	for(var i=0; i<document.styleSheets.length; i++)
	{			
		if(document.styleSheets[i].href != null)
		{	
			if(document.styleSheets[i].href.split(/\/+/)[1] == getDomain())
			{	
				var styleSheet = document.styleSheets[i];
				var rules = styleSheet.cssRules ? styleSheet.cssRules : styleSheet.rules;
				
				for(var j=0; j<rules.length; j++)
				{
					if(rules[j].selectorText == name)
					{
						return rules[j];
					}
				}
			}
		}
	}
	
	return null;
}

function getNumber(str)
{
	if(str.match(/[0-9]+\.[0-9]+/))
	{
		return str.match(/[0-9]+\.[0-9]+/);
	}
	else
	{
		return str.match(/[0-9]+/)
	}
						   
}

function hasNumberUnits(str)
{
	if(str.match(/[0-9]+/) && str.match(/[A-Za-z]+/))
	{
		return true;
	}
	else
	{
		return false;
	}
}
var lastEvent = undefined;
function validateKeypress(e,type,length)
{
	var target = (e.target ? e.target:e.srcElement);
	var key = (e.which) ? e.which : ((e.charCode) ? e.charCode : ((e.keyCode) ? e.keyCode : 0));
	if ( key == 8 || key == 46 || key == 9 ) // Allow backspace, delete, and tab
		return true;

	switch(type){
		case 0:
		case "all":
			return target.value.length < length;
		case 1:
		case "text":
			matchExpression = /[A-z\-]/;
			break;
		case 2:
		case "num":
			matchExpression = /\d/;
			break;
	}
	if(key == 13)
		target.blur();
	return matchExpression.test(String.fromCharCode(key)) && (target.value.length < length);
}

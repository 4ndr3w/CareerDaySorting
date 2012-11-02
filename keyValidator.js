var lastEvent = undefined;
function validateKeypress(e,type,length)
{
	switch(type){
		case 0:
		case "all":
			return e.srcElement.value.length < length;
		case 1:
		case "text":
			matchExpression = /[A-z ]/;
			break;
		case 2:
		case "num":
			matchExpression = /\d/;
			break;
	}
	return matchExpression.test(String.fromCharCode(e.keyCode)) && (e.srcElement.value.length < length);
}


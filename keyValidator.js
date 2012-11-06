var lastEvent = undefined;
function validateKeypress(e,type,length)
{
	if(e.keyCode == 13)
		e.target.blur();
			switch(type){
				case 0:
				case "all":
					return e.srcElement.value.length < length;
				case 1:
				case "text":
					return ((e.keyCode > 64 && e.keyCode < 91) || ( e.keyCode > 96 && e.keyCode < 123) || e.keyCode == 45 || e.keyCode == 32);
				case 2:
				case "num":
					return matchExpression = e.keyCode > 47 && e.keyCode < 58;
				break;
			}
		return true;
}


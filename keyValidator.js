/*
	Career Day Sorting - Career Day Signup and Scheduling system
    Copyright (C) 2013 Andrew Lobos and Benjamin Thomas

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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

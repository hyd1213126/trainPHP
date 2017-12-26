var w;
var printCal;
var befEle;
var haveCalendar=false;
window.onload=function(){
	if(haveCalendar)
	{
		newcalendar.style.display="";
		newcalendar.style.left="0px";
		newcalendar.style.top="0px";
		w=0;
		printCal=document.printCal
		befEle=3;
		setMonth();
		setDates();
	}
}
	
function getLayer(n,doc)
{
	var x;
	if(!doc) var doc=document;
	if(!(x=doc[n])&&doc.all) x=doc.all[n];
	for(i=0;!x&&doc.layers&&i<doc.layers.length;i++) x=getLayer(n,doc.layers[i].document);
	if(!x && doc.getElementById) x=doc.getElementById(n);
	if(x.style) x=x.style;
	return x;
}
function shLayer(){
var i,v,obj,args=shLayer.arguments;
for (i=0;i<(args.length-2);i+=3){
	if ((obj=getLayer(args[i]))!=null){
		v=args[i+2];
		v=(v=='show')?'visible':(v='hide')?'hidden':v;
		}
	obj.visibility=v;
	}
}
function moveLayer(n,x,y)
{
	var obj=getLayer(n);obj.left=x;obj.top=y;
}
//for Canendar
function getDaysInMonth(year,month)
{
	var daysOfMonth=new Array(31,28,31,30,31,30,31,31,30,31,30,31);
	if (((year%4)==0) && (month==2)) return daysOfMonth[month-1]+1;
	return daysOfMonth[month-1];
}
function setMonth(step)
{
	var year=parseInt(printCal.year.value);
	var month=parseInt(printCal.month.value);
	var newMonth;

	if (year && month)
	{
		newMonth=month+step;
		if (newMonth<1) 
		{
			newMonth=12;

			year--;
		}
		else if (newMonth>12)
		{
			newMonth=1;
			year++;
		}
	}
	else
	{
		var today=new Date();
		newMonth=today.getMonth()+1;
		year=today.getFullYear();
	}

	printCal.year.value=year;
	printCal.month.value=newMonth;
}
function setDates()
{
	var toDay=new Date();
	var todate=parseInt(toDay.getDate());
	var tomonth=parseInt(toDay.getMonth()+1);
	var toyear=parseInt(toDay.getFullYear());

	var year=parseInt(printCal.year.value);
	var month=parseInt(printCal.month.value);
	var firstDay=new Date(year,month-1,1);
	var befDays=parseInt(firstDay.getDay());
	var dayNum=getDaysInMonth(year,month);

	for (k=befEle;k<printCal.elements.length;k++)
	{
		if ((k<(befEle+befDays)) || (k>(befEle+befDays+dayNum-1)))
		{
			printCal.elements[k].value='';
			printCal.elements[k].disabled=true;

			var butn=getLayer(printCal.elements[k].name);
			butn.color="#ff0000";
			butn.backgroundColor="#EEEEEE";
		}
		else
		{
			if (((k-befEle-befDays+1)==todate) && (tomonth==month) && (toyear==year))
			{
				printCal.elements[k].value=k-befEle-befDays+1;
				printCal.elements[k].disabled=false;

				var butn=getLayer(printCal.elements[k].name);
				butn.color="#FF0000";
				butn.backgroundColor="#FFFFFF";
			}
			else
			{
				printCal.elements[k].value=k-befEle-befDays+1;
				//printCal.elements[k].disabled=false;
				printCal.elements[k].disabled=false;

				var butn=getLayer(printCal.elements[k].name);
				butn.color="#000000";
				butn.backgroundColor="#EEEEEE";
			}
		}
	}
	if (toyear>year)
	{
		for (k=befEle;k<printCal.elements.length;k++) printCal.elements[k].disabled=false;   //去年
	}
	else if (toyear==year)
	{
		if (tomonth > month)
		{
			for (k=befEle;k<printCal.elements.length;k++) printCal.elements[k].disabled=false;	//上个月
		}
		else if (tomonth == month)
		{
			for (k=befEle;k<printCal.elements.length;k++) if (todate > (k-befEle-befDays+1))  printCal.elements[k].disabled=false;	//昨天
		}
	}
}
function chgDate(date)
{
	var year=printCal.year.value;
	var month=printCal.month.value;
	if(month>=10) 
	{if(date>=10) var str=year+"-"+month+"-"+date;
	else var str=year+"-"+month+"-0"+date;
	}
	else {
	if(date>=10) var str=year+"-0"+month+"-"+date;
	else var str=year+"-0"+month+"-0"+date;}
		
	var tmp=printCal.transfer.value.split("*");
	eval("document."+tmp[0]+"."+tmp[1]+".value=str;");
	shLayer("newcalendar","","hide");
}

function showLayer(name,objButn)
{
	var left=0;
	var top=0;
	var p=objButn;
	while(p && p.tagName!="BODY")
	{
		left+=p.offsetLeft;
		top+=p.offsetTop;
		p=p.offsetParent;
	}
	
	moveLayer(name,left,top+23);
	shLayer(name,"","show");
}
function showCalendar(target,objButn)
{
	showLayer("newcalendar",objButn);
	printCal.transfer.value=target;
}

function changeValue(formN)
{
	if(formN.t1.value=="开始时间")
		formN.t1.value="";
	if(formN.t2.value=="结束时间")
		formN.t2.value="";
	if(formN.k.value=="输入关键字")
		formN.k.value="";
}
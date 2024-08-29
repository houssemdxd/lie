var positionimage=
{"p":1};

function randemnumer()
{min = Math.ceil(1);
    max = Math.floor(10);
    return Math.floor(Math.random() * (max - min +1)) + min;}

    
//tranlate number 
function translatenumber(x)
{
var databasecard = 
{1:'one',
2:'two',
3:'three',
4:'four',
5:'five',
6:'six',
7:'seven',
8:'eight',
9:'nine',
10:'ten'
}
return databasecard[x];
}
function pink()
{
    //change the background color
var div= document.getElementById("demo");
div.style.backgroundColor=" #F7CAC9";
//change button shape
var divpink=document.getElementById('pink');
divpink.style.borderRadius="20px";
//reset other button
var divgreen= document.getElementById("green");
divgreen.style.borderRadius="9px";

var divblue= document.getElementById("blue");
divblue.style.borderRadius="9px";
image=document.createElement('img');
image.src="pink.png";
}
function blue()
{//change the backgrround color
var div= document.getElementById("demo");
div.style.backgroundColor="#92A8D1 ";
//chnage the type of button blue
var divblue= document.getElementById("blue");
divblue.style.borderRadius="20px";
//reset the other button
//change button shape
var divpink=document.getElementById('pink');
divpink.style.borderRadius="9px";

var divgreen=document.getElementById("green");
divgreen.style.borderRadius="9px ";

}
function green()
{//change the background color
var div= document.getElementById("demo");
div.style.backgroundColor="#fba92c";
//change button shape
var div=document.getElementById("green");
div.style.borderRadius="20px ";
//reset other button
var divpink=document.getElementById('pink');
divpink.style.borderRadius="9px";
var divblue= document.getElementById("blue");
divblue.style.borderRadius="9px";

}
//dabatabeseofnumbers gloabal variable 
var finaltab=[];var aux=[];//les nombre docuurence 
 var id=5;
 /////////////
 var def=[]; 
//table for the cars puts in table 
var intable=[];
 //////////
 putcrads()
 
function putcrads()
{var hand=document.getElementById("ten2020")
//add the first card one important!!!!
var table=[];
table.push('one');
///////////put all the rest cards /////
for(i=0;i<9;i++)
{
var x=randemnumer();
var ch=translatenumber(x);
//put name all cards in element to treat them in next step
table.push(ch);  
}

console.log(table);
//know the defferent card name

for(i=0;i<table.length;i++)
{
if(aux.indexOf(table[i])<0)
{aux.push(table[i]);}}
//add to our database number of repetation
for(j=0;j<this.aux.length;j++)
{

finaltab.push(fctcalcul(table,aux[j]));
}

for(k=0;k<this.aux.length;k++)
{var idb=this.aux[k];
    var div= document.createElement('div');
    var idImage=generatId();
  div.innerHTML="<div id='wow"+idImage+"'><img src="+this.aux[k]+".png height=100 width=100 id ='"+idImage+"' onclick='hitcard("+idb+","+k+","+idImage+")'><button type='button'class='newturn btn-15' id= '"+idb+"'><span id='btn-design'>"+finaltab[k]+"</span></button></div>";
  hand.appendChild(div);
  console.log(div);
    }

}
function generatId()
{var x=randemnumer()*id
id++;
return x;}

function fctcalcul(t,ch)
{nb=0;
    for(i=0;i<t.length;i++)
    {if(ch==t[i])
        nb=nb+1;}
return nb;
}
///////////////////////////////hitvard///////////////
function hitcard(ide,k,idImage)
{e="f";console.log(k);
var f=finaltab[k]-1;
ide.textContent=f;
finaltab[k]--;
if (f==0)
{ide.style="color:green; background-color: green;";}
else{ide.style="  border-radius:50%;position: absolute;margin-left: -10px;background-color: #fba92c;border: 6px solid #fba92c;text-decoration: aliceblue;color: rgb(255, 240, 240);font-size: large;font: 1em sans-serif;font-weight: bold;"
}

    s=document.getElementById(idImage).getAttribute("src");
s=s.substring(0, s.length-4);
    intable.push(s);
// put cars in table
var last=document.getElementById("blackjack");
image = document.createElement("img");
image.src="My project (2).png";
image.style="width:500px;height:500px;boder:10px solid black;margin-top:-50px;position:absolute ;filter: drop-shadow(30px 10px 4px #4444dd);filter:drop-shadow(4px 4px 4px black);";

image.id='dynamic';

last.appendChild(image);

positionimage['p']++;

}
 
















    ///////////////////////////////////////////////////
function reverse()
{def=[];ping=[];
var hand=document.getElementById("ten2020");
    for(i=0;i<intable.length;i++)
    {
    if(aux.indexOf(intable[i])<0)
    {def.push(intable[i]);}}
    for(i=0;i<def.length;i++)
    {
    if(ping.indexOf(def[i])<0)
    {ping.push(def[i]);}
    }
    
        if (def.length>0)
        {def=[];
            for(i=0;i<ping.length;i++) 
                {def.push(ping[i]);}            //condition////////
for(cpt=0;cpt<def.length;cpt++)
{   
        var idb=def[cpt];
    var div= document.createElement('div');
        var idImage=generatId();
        var sum=finaltab.length+cpt;
      div.innerHTML="<div id='wow"+idImage+"'><img src='"+def[cpt]+".png' height=100 width=100 id ='"+idImage+"' onclick='hitcard("+idb+","+sum+","+idImage+")'><button type='button'class='newturn btn-15' id= '"+idb+"'></button></div>";
      hand.appendChild(div);

        


aux.push(def[cpt]);

finaltab.push(0);
        }
    }






        
////konw the new elements appear 

   
       

//ransfert to tabfinal
 
////////final
    for(j=0;j<this.aux.length;j++)
{

finaltab[j]=(finaltab[j])+fctcalcul(intable,aux[j]);
}
//////////refrech
//for(j=0;j<this.aux.length;j++)
//document.getElementById(aux[j]).nodeValue=finaltab[j];}
var ty=document.getElementsByClassName('newturn');
for(i=0;i<ty.length;i++)
{
ty[i].textContent=finaltab[i];
}
for(i=0;i<ty.length;i++)
{
if(ty[i].textContent>0)
{ty[i].style="border-radius:50%;position: absolute;margin-left: -10px;background-color: #fba92c;border: 6px solid #fba92c;text-decoration: aliceblue;color: rgb(255, 240, 240);font-size: large;font: 1em sans-serif;font-weight: bold;"}
}

intable=[]; 
def=[];
}



















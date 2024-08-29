function game(your_choise)
{alert("hi");
var mine,bot;
mine=your_choise.id;
bot=randomnum();
console.log(bot);
bot=numbertochoise(randomnum());
result=whowin(bot,mine);
msg=finalmsg(result)

frontend(mine,bot,msg);


}

databaseoccurence=
{one:10};



function randomnum()
{
    return Math.floor(Math.random()*3);
}
function numbertochoise(x)
{  
    return ['rock','paper','sizor'][x];}
function whowin(bot,mine)
{
    var database=
    {
        'rock':{'sizor':1,'rock':0.5,'paper':0},
        'paper':{'sizor':0,'rock':1,'paper':0.5},
        'sizor':{'sizor':0.5,'rock':0,'paper':1}
    }
    
    var myscore=database[mine][bot];
   
    return myscore

}
function finalmsg(x)
{if(x==0)
    {
        return {'msg':"you lost",'color':'red'};}
else if (x==0.5)
{
    return {'msg':"you tied",'color':'yellow'};
} 
else
{
    return {'msg':"you Won",'color':'green'};

}
}

function frontend(mine,bot,msg)
{   var imgdatabase=
    {'rock':document.getElementById('rock').src,
    'paper':document.getElementById('paper').src,
    'sizor':document.getElementById('sizor').src,
}
//remove alla images 
document.getElementById('rock').remove();
document.getElementById('paper').remove();
document.getElementById('sizor').remove();
console.log(imgdatabase['rock']);
var minediv=document.createElement('div');
var botdiv=document.createElement('div');
var msgdiv=document.createElement('div');
console.log(imgdatabase[mine]);
minediv.innerHTML ="<img src ='"+imgdatabase[mine]+"' height =150 width=150  ;'>";
botdiv.innerHTML ="<img src ='"+imgdatabase[bot]+"' height =100 width=100 style='box-shadow: 0px 20px 50px "+msg['color']+";'>";
msgdiv.innerHTML ="<h1><font color="+msg['color']+">"+msg['msg']+"</font><h1/>";
document.getElementById("table").appendChild(minediv);
document.getElementById("table").appendChild(msgdiv);
document.getElementById("table").appendChild(botdiv);

}
class  A{
static id=0;
}
var  person = [];
var icr=0; 
let compteur=-1;
 function hitverif(x)
{
    databaseoccurence["one"]--;
    console.log(databaseoccurence['one']);
    if (compteur<11)
    {
    if (databaseoccurence['one']!=0)
        {hitcard(c,x);
        
        var r=document.getElementById(x);
        
      
        person.push(r);
       
       
        compteur++;

   
   console.log(databaseoccurence["one"]);
}
   else{

    
    document.getElementById(x).remove();
   }
    return compteur;
    }
    else{
        return 0;}
}*/
function test()/////deal
{   
    for(i=0;i<person.length;i++)   {
        document.getElementById("dynamic").remove();

       var hand=document.getElementById("ten");
       hand.appendChild(person[i]);
     
    }
    person=[];
}

function hit()
{
    
var random= randemnumer();

var card=translatenumber(random)

frontendtable(card,random);

return numbertochoise;


}
function newFunction() {
    return 0;
}

function randemnumer()
{min = Math.ceil(1);
    max = Math.floor(10);
    return Math.floor(Math.random() * (max - min +1)) + min;

  
}
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

function frontendtable(card,c)
{var image= document.createElement('img');
var div=document.getElementById("blackjack");
image.src  ="ten.png";
image.height="150";
image.id="dynamic"
div.appendChild(image);}


function addcard()
{for(i=1;i<11;i++)
    {
var image=document.createElement('img');
image.src="ten.png";
image.style="width:100px;hover:opacity:0.7;-webkit-transform-origin-y:10px;";
var block=document.getElementById("ten");

image.id=i.toString();
block.appendChild(image)
    }
    

}
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

var iddatabase=
{








}

putcards()
function putcards()
{var hand=document.getElementById("ten");var table=[];
for(i=0;i<10;i++)
{
    var x=randemnumer();
  
//imnb age = document.createElement("img");
//image.src=translatenumber(x)+".png ";
//image.style="width:100px;height:120px;";
//image.id="hand";
//var image= document.getElementById("hand");
var div=document.createElement('div');
var poi=generatId();

div.innerHTML="<div class='new'><img src="+translatenumber(x)+".png  style='width:100px;height:120px;' id= '"+poi+"' onclick='hitverif(id)'class="+translatenumber(x)+"><br><button type='button' style='position:absolute; margin-top: -100px'>"+databaseoccurence['one']+"</button></div>" ; 

//"<img src="+translatenumber(x)+".png  style='width:100px;height:120px;' id= '"+poi+"' onclick='hitverif(id)'class='"+translatenumber(x)+"'>";
hand.appendChild(div);
}
x1=document.getElementsByClassName('one');
two=document.getElementsByClassName('two');
three=document.getElementsByClassName('three');
four=document.getElementsByClassName('four');
five=document.getElementsByClassName('five');

six=document.getElementsByClassName('six');

seven=document.getElementsByClassName('seven');

eight=document.getElementsByClassName('eight');






console.log(x1.length)

var x2=document.getElementsByClassName('new');
console.log(x2);
for(i=x1.length-1;i>0;i--)
{
    x1[i].remove();
 
    console.log(x1.length);
}


}










var b ={num:50};


function generatId()
{
var randomid=randemnumer()*randemnumer()*randemnumer();

console.log(randomid);

return randomid;
}

var c ={num:70};
/*function hitcard(c,k)
{
    
    var hand=document.getElementById("blackjack");
image = document.createElement("img");
image.src="inout (2).png";
image.style="width:150px;height:200px;transform: translate(200px);margin-top:"+c['num']+"px;position:absolute;";
image.id='dynamic';
var div =document.getElementById("ten");
console.log(div);
div.innerHTML="<div><img src=one.png  style='width:100px;height:120px;' id= '"+k+"' onclick='hitverif(id)'class='one'><br><button type='button' style='position:absolute; margin-top:-100px'>"+databaseoccurence['one']+"</button> </div>";
hand.appendChild(image);
c['num']+=12;
}


*/




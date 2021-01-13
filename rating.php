<?php
session_start();

if(!isset($_SESSION['password']))
{
    header('Location: login.php');
    exit;
}

require('load.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рассчёт рейтинга</title>
</head>
<link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>

<script>


</script>
<body>


  <!-- /.login-logo -->
<div class="card-header text-center">
    <h3 class="d-inline mr-4">
    <b>ВолгГМУ</b>
    </h3>
    <?php $menu = 'rating'; include_once('menu.php'); ?>
    <a href="login.php?page_logout" class="float-right my-2" >
        <img src="asserts/img/logout.png" class="h-20">
    </a>
</div>

  <div class="card-body mx-auto text-center">


<h4 class="d-inline-block mb-4 text-center">
    Рассчёт среднего балла студента за семестр по фотографии журнала
</h4>  

<!-- Content Header (Page header) -->
<section class="content-header mb-1">
        
        
         
            <div class="d-inline">
               Загрузить фото (jpg, png) журнала:
            </div>
            
            <input id="the-file-input" class="btn btn-danger" type="file" onchange="renderImage(this.files[0]);$('#the-file-input').removeClass('btn-danger');$('#the-file-input').addClass('btn-success');">


</section>

</div>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <!-- Default box -->
             

<div id="container" style="height: 350px;  overflow: auto;  border: 1px solid;">
    <canvas id='load_image'>Обновите браузер</canvas>
</div>
<script>

function mask(id, event,next) {
    var element = $("#" + id);
    var len = element.val().length + 1;
    var max = element.attr("maxlength");

    var cond = (49 < event.which && event.which < 54) || (49 < event.keyCode && event.keyCode < 54|| event.keyCode==8|| event.keyCode==32);
    
    if (!(cond && len <= max)) {
        event.preventDefault();
        return false;
    }
    else $("#" + next).focus();
}

function renderImage(file) {
    var reader = new FileReader();
    var journal = document.getElementById('load_image'),
            ctx     = journal.getContext('2d'),
            pic     = new Image();
        journal.width = document.body.clientWidth+150;
        journal.height = document.body.clientHeight+150;
        
        
    // подстановка изображения в атрибут src
    reader.onload = function(event) {
        the_url = event.target.result
        pic.src=the_url;
        
        pic.onload = function () {
            ctx.drawImage(pic, 0, 0, document.body.clientWidth-150, document.body.clientHeight+150);
        
        }
    }

 // при считке файла, вызывается метод, описанный выше
  reader.readAsDataURL(file)

}
	
	
function count() {
    var i = 0, points=0,points_i=0, c=0, c_i=0;
    while (i < 38) {
        if($('#i'+i).val()!=''&&$('#i'+i).val()!=' '&&$('#i'+i).val()!='0') {
            if(document.getElementById("check"+i).checked){points_i=points_i+parseInt($('#i'+i).val());c_i++;
            } else {
                points=points+parseInt($('#i'+i).val());c++;
            }
        } 
        i++;
    }
	
    var result_text=0,result_text2=0;
    if(c_i==0) result_text=parseFloat(points/c).toFixed(1); else result_text=parseFloat((points/c+points_i/c_i)/2).toFixed(1);
        
    document.getElementById('result').innerHTML=result_text;

    if(result_text==5.0)result_text2='100';
    if(result_text==4.9)result_text2='98-99';
    if(result_text==4.8)result_text2='96-97';
    if(result_text==4.7)result_text2='94-95';
    if(result_text==4.6)result_text2='92-93';
    if(result_text==4.5)result_text2='91';
    if(result_text==4.4)result_text2='88-90';
    if(result_text==4.3)result_text2='85-87';
    if(result_text==4.2)result_text2='82-84';
    if(result_text==4.1)result_text2='79-81';
    if(result_text==4.0)result_text2='76-78';
    if(result_text==3.9)result_text2='75';
    if(result_text==3.8)result_text2='74';
    if(result_text==3.7)result_text2='73';
    if(result_text==3.6)result_text2='72';
    if(result_text==3.5)result_text2='71';
    if(result_text==3.4)result_text2='69-70';
    if(result_text==3.3)result_text2='67-68';
    if(result_text==3.2)result_text2='65-66';
    if(result_text==3.1)result_text2='63-64';
    if(result_text==3.0)result_text2='61-62';
    if(result_text==2.9)result_text2='57-60';
    if(result_text==2.8)result_text2='53-56';
    if(result_text==2.7)result_text2='49-52';
    if(result_text==2.6)result_text2='45-48';
    if(result_text==2.5)result_text2='41-44';
    if(result_text==2.4)result_text2='36-40';
    if(result_text==2.3)result_text2='31-35';
    if(result_text==2.2)result_text2='21-30';
    if(result_text==2.1)result_text2='11-20';
    if(result_text==2.0)result_text2='0-10';

    document.getElementById('result2').innerHTML='('+result_text2+')&nbsp;';
    document.getElementById('result5').innerHTML='<input type="number" id="SR-point" maxlength="3" style="width:45px;height:20px;" value="'+result_text2.substr(0,2)+'">';


    if($('#CR1').val()!=''&&$('#CR1').val()!=' '&&$('#CR1'+i).val()!='0') {
    point_CR=parseFloat((parseInt($('#CR1').val())+parseInt($('#CR2').val()))/2).toFixed(1);
    
    if(point_CR==5.0)result_text3='100';
    if(point_CR==4.9)result_text3='98-99';
    if(point_CR==4.8)result_text3='96-97';
    if(point_CR==4.7)result_text3='94-95';
    if(point_CR==4.6)result_text3='92-93';
    if(point_CR==4.5)result_text3='91';
    if(point_CR==4.4)result_text3='88-90';
    if(point_CR==4.3)result_text3='85-87';
    if(point_CR==4.2)result_text3='82-84';
    if(point_CR==4.1)result_text3='79-81';
    if(point_CR==4.0)result_text3='76-78';
    if(point_CR==3.9)result_text3='75';
    if(point_CR==3.8)result_text3='74';
    if(point_CR==3.7)result_text3='73';
    if(point_CR==3.6)result_text3='72';
    if(point_CR==3.5)result_text3='71';
    if(point_CR==3.4)result_text3='69-70';
    if(point_CR==3.3)result_text3='67-68';
    if(point_CR==3.2)result_text3='65-66';
    if(point_CR==3.1)result_text3='63-64';
    if(point_CR==3.0)result_text3='61-62';
    if(point_CR==2.9)result_text3='57-60';
    if(point_CR==2.8)result_text3='53-56';
    if(point_CR==2.7)result_text3='49-52';
    if(point_CR==2.6)result_text3='45-48';
    if(point_CR==2.5)result_text3='41-44';
    if(point_CR==2.4)result_text3='36-40';
    if(point_CR==2.3)result_text3='31-35';
    if(point_CR==2.2)result_text3='21-30';
    if(point_CR==2.1)result_text3='11-20';
    if(point_CR==2.0)result_text3='0-10';
    
    document.getElementById('result3').innerHTML=result_text3;


    document.getElementById('result4').innerHTML='<input type="number" id="CR-point" maxlength="3" style="width:45px;height:20px;" value="'+result_text3.substr(0,2)+'">&nbsp;';

    document.getElementById('result6').innerHTML='&nbsp;&nbsp;<button type="button" class="btn btn-secondary btn-sm" onclick="count_SUM();"><font size="2">Rд(Итог)</font></button>&nbsp;&nbsp;';
    }


}

function count_SUM() {
    document.getElementById('result7').innerHTML='<font size="3"><b>Rпред</b>='+$('#SR-point').val()+'+'+$('#bonus').val()+'-'+$('#shtraf').val()+'=<b>'+parseFloat(parseInt($('#SR-point').val())+parseInt($('#bonus').val())-parseInt($('#shtraf').val()))+'</b><br>Rд=(Rпред+Rпа)/2<br>Rд=</font><font size="6">'+parseFloat((parseInt($('#SR-point').val())+parseInt($('#CR-point').val())+parseInt($('#bonus').val())-parseInt($('#shtraf').val()))/2).toFixed(2)+'</font>';
}


function clear_form() {
    var i = 0;
    $('#CR1').val('');  
    $('#CR2').val('');
    $('#shtraf').val(0);  
    $('#bonus').val(0);
    document.getElementById('result').innerHTML='';
    document.getElementById('result2').innerHTML='';
    document.getElementById('result3').innerHTML='';
    document.getElementById('result4').innerHTML='';
    document.getElementById('result5').innerHTML='';
    document.getElementById('result6').innerHTML='';
    document.getElementById('result7').innerHTML='';

    while (i < 38) {  
        $('#i'+i).val('');  
        $('#check'+i).prop('checked', false);
        i++;
    }
}

function form_size(val) {
var i = 0;

while (i < 38) {

    if(val=='plus'){
        document.getElementById("s"+i).style.width=(parseInt(document.getElementById("s"+i).style.width)+1)+"px";
    } else {
        document.getElementById("s"+i).style.width=(parseInt(document.getElementById("s"+i).style.width)-1)+"px";
    }
    i++;
    }

}



</script>


<table width="98%" style="background:#FFFFF0;position: relative; bottom: 185px; left:1px;"><tr><td align="right" width="220">Выберите&nbsp;&nbsp;&nbsp;&nbsp;<br><b>Оценка</b>&nbsp;&nbsp;&nbsp;&nbsp;<br>
Итоговая (отметить)&nbsp;&nbsp;&nbsp;&nbsp;</td>

<td>

<div id="recognize"></div>

<script>
var div = document.querySelector('#recognize');
    input = div.childNodes;

var i = 0;
while (i < 38) {

	div.innerHTML += '<div style="diplay:inline;float:left;border: 1px solid;text-align:center;"><select style="width:18px;" id="s'+i+'" onclick="$(\'#i'+i+'\').val(this.value);"><option value="0">...</option><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option></select><br><input type="text" maxlength="1" id="i'+i+'" style="width:15px;height:20px;" onkeypress="mask(this.id, event,\'i'+(i+1)+'\')"><br><input type="checkbox" id="check'+i+'" value="'+i+'" style="width:13px;"></div>';


  i++;
}

</script>
<td>
<table><tr><td colspan="2">Зачёт</td></tr><tr><td><select style="width:18px;" id="sCR1" onclick="$('#CR1').val(this.value);"><option value="0">...</option><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option></select><br><input type="text" maxlength="1" id="CR1" style="width:15px;height:20px;" onkeypress="mask(this.id, event,'CR2')"></td><td><select style="width:18px;" id="sCR2" onclick="$('#CR2').val(this.value);"><option value="0">...</option><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option></select><br><input type="text" maxlength="1" id="CR2" style="width:15px;height:20px;" onkeypress="mask(this.id, event,'CR2')"></td></tr></table>


</td>
</td></tr>
<tr>
<td align="right"><button type="button" class="btn btn-danger btn-sm" onclick="form_size('minus');" style="width:35px;"><font size="2">-</font></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success btn-sm" onclick="form_size('plus');" style="width:35px;"><font size="2">+</font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-secondary btn-sm" onclick="clear_form();"><font size="2">Очистить</font></button>&nbsp;&nbsp;&nbsp;</td><td>
  <table height="80"><tr><td align="center" width="180"><button type="button" class="btn btn-success btn-lg" onclick="count();">Рассчитать</button></td>
<td>

<table><tr><td>Б&nbsp;</td><td><input type="number" maxlength="2" id="bonus" style="width:40px;height:20px;" value="0">&nbsp;&nbsp;</td>
<td><h5>&nbsp;&nbsp;Ср. балл <font size="3">(Rтек)</font>:&nbsp;&nbsp;</h5></td><td><b><h2><div id="result"></div></h2></b></td><td><h3><div id="result2" style="diplay:inline;"></div></h3></td><td><div id="result5" style="diplay:inline;"></div></td><td rowspan="2" style="vertical-align: middle;"><div id="result6" style="diplay:inline;"></div></td><td rowspan="2" style="vertical-align: middle;"><div id="result7" style="diplay:inline;"></div></td></tr>

<tr><td>Ш&nbsp;</td><td><input type="number" maxlength="2" id="shtraf" style="width:40px;height:20px;" value="0">&nbsp;&nbsp;</td>
<td align="right">
<h5>Зачёт  <font size="3">(Rпа)</font>:&nbsp;&nbsp;&nbsp;</h5></td><td><h2><div id="result3" style="diplay:inline;"></div></h2></td><td>&nbsp;</td><td><div id="result4" style="diplay:inline;"></div>
</td></tr></table>

</td></tr></table>

</td></tr>
</table>

            </div>
      </section>

</body>
</html>
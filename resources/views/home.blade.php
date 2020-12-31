@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <select onchange="Changecolor(this.value)">
                    <option >Change Background</option>
                    <option value="red">Red</option>
                    <option value="green">Green</option>
                </select>
            <div>

                <input type="file" name="upload_image" id="imgLoader">

                <button onclick="DeleteObject()">Delete</button>
                <select onchange="ChangeColor(this.value)">
                    <option value="">Change selected object color</option>
                    <option value="red">Red</option>
                    <option value="green">Green</option>
                </select>
                <button onclick="SaveToDB()">GetJson</button>
            </div>    
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                
                <div class="card-body">
                   <div class="bg-svg"><?php 
                    echo (file_get_contents("http://gwl.test/assets/img/tshirt.svg"));
                ?>
            </div>
            <canvas  id="canvasarea"></canvas>
                </div>
            </div>
        </div>
        <div id="svgs">
            
        </div>
    </div>
</div>
<style type="text/css">
  .canvas-container {
    top: 36%;
    left: 28%;
    height: 300px !important;
  border-style: solid;
  position: absolute !important;
border-width: 19px 22px 22px 19px;
-moz-border-image: url(https://drive.google.com/uc?export=view&id=0B3ubyt3iIvkabEZhbkJseW5IZWc) 19 22 21 19 round;
-webkit-border-image: url(https://drive.google.com/uc?export=view&id=0B3ubyt3iIvkabEZhbkJseW5IZWc) 19 22 21 19 round;
-o-border-image: url(https://drive.google.com/uc?export=view&id=0B3ubyt3iIvkabEZhbkJseW5IZWc) 19 22 21 19 round;
border-image: url(https://drive.google.com/uc?export=view&id=0B3ubyt3iIvkabEZhbkJseW5IZWc) 19 22 21 19 round;
}

</style>

<script type="text/javascript">
    var canvas = new fabric.Canvas('canvasarea');

   document.getElementById('imgLoader').onchange = function handleImage(e) {
    var reader = new FileReader();
      reader.onload = function (event){
        var imgObj = new Image();
        imgObj.src = event.target.result;
        imgObj.onload = function () {
          var image = new fabric.Image(imgObj);
          image.set({
                angle: 0,
                padding: 10,
                cornersize:10,
                height:110,
                width:110,
               
          });
          canvas.centerObject(image);
          canvas.add(image);
          canvas.renderAll();
        }
      }
      reader.readAsDataURL(e.target.files[0]);
    }

    function DeleteObject(){
        console.log(canvas.getActiveObject());
         canvas.remove(canvas.getActiveObject());

    }

function GetXml(color){
    $.ajax({
        url :       canvas.getActiveObject().getSrc(),
        cache :     true, 
        dataType :  "xml"
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        console.error("Falied to get svg");
    }).done(function( data, textStatus, jqXHR ){
        console.log("Got the svg's SVG XML:", $(data).find('svg'));
        $('#svgs').html($(data).find('svg'))

         $('#svgs svg path').css('fill',color)


    });
}


function ChangeColor(color){
        GetXml(color);
        console.log(canvas.getActiveObject().getSrc());
        canvas.getActiveObject().backgroundColor=color
         canvas.renderAll();
        

    }

function SaveToDB(){
     canvasJSONdata = JSON.stringify(canvas.toDatalessJSON());
     console.log(canvasJSONdata);
}
    function Changecolor(color){
   $('.bg-svg svg path').css('fill',color)
    }


</script>
@endsection

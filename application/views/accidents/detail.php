<script type="text/javascript">
    function stateChange()
    {
        $("#comment_container_box").removeClass("has-error");
        var x=document.getElementById("comment_content");

        $("#post_button").removeClass("hidden");
            
    }


    $(function()
    {
        $("#post_button").click(function(){

            // The content in the comment box
            var comCon = $("#comment_content").val();

            // alert($("#comment_content"));

            // Serializing the comment content for posting with Ajax
            var dataString = 'comment_content='+comCon;

            if(!comCon)
            {
                $('#notifyModal').modal('show'); 
                //alert("This comment box cannot");
                //$("#comment_container_box").addClass("has-error");
            }



            else{
                $("#comment_confirmation").show();
                $("#comment_confirmation").fadeIn(600).html();


                $.ajax({
                    type: "POST",
                    url: "../../libraries/Routecontroller.php",
                    data: dataString,
                    cache: false,
                    success: function(html){


                    }
                });
            } return false;
        });
    });

</script>

<script>
    $(document).ready(function(){
       
        var showbox = document.getElementById('modal-body');

        $(".img-thumbnail").click(function(){
    
            var data = $(this).attr('data');
            var thumb_data = $(".img-thumbnail");
            // thumb_data = document.getElementById("photobox").getAttribute("data");
            //var img = document.createElement("img");
            // img.src = thumbdata;
            showbox.innerHTML="<img src="+data+">";

            $('#myModal').modal('show'); 
            //showbox.innerHTML="";

        });

        $('#edit-description').click(function(){
      
            var description = $(this).attr('id');
            var input=document.createElement("input");
    
            $('#image-description').hide();

 
  
        });
    });
</script>


<style>
    textarea.form-control {
        height: 35px;
    }

    textarea.form-control:focus {
        height: 70px;
    }


    .comment_container{
        max-width:820px;
        padding-left:15px;
    }
    .container_content{
        max-width:860px;

    }
    .list-group-item{
        min-height:80px;
        padding:15px;
    }
</style>

<?php
$date = array(
    "id" => "date",
    "name" => "date",
    "placeholder" => "Date",
    "class" => "form-control",
    "value" => set_value("date", date_mysql2human($details->date))
);

$time = array(
    "id" => "time",
    "name" => "time",
    "placeholder" => "Time",
    "class" => "form-control",
    "value" => set_value("time", time_mysql2human($details->time))
);

$building = array(
    "id" => "building",
    "name" => "building",
    "placeholder" => "Building",
    "class" => "form-control",
    "value" => set_value("building", $details->building)
);

$room = array(
    "id" => "room",
    "name" => "room",
    "placeholder" => "Room",
    "class" => "form-control",
    "value" => set_value("room", $details->room)
);

$description = array(
    "id" => "description",
    "name" => "description",
    "placeholder" => "Description",
    "class" => "form-control",
    "rows" => 3,
    "value" => set_value("description", $details->description)
);

$root = array(
    "id" => "root",
    "name" => "root",
    "placeholder" => "Root",
    "class" => "form-control",
    "rows" => 3,
    "value" => set_value("root", $details->root)
);

$prevention = array(
    "id" => "prevention",
    "name" => "prevention",
    "placeholder" => "Prevention",
    "class" => "form-control",
    "rows" => 3,
    "value" => set_value("prevention", $details->prevention)
);
?>




<?php
/*
  $params = array($details->id);

  $this->load->library('gallerybuilder',$params);
 */
?>


<?php echo form_open("accidents/add/save", array("class" => "form-horizontal formcs hidden", "role" => "form")); ?>

<?php echo form_hidden("revision_of", $details->revision_of); ?>
<?php echo form_hidden("user", $details->user); ?>

<div class="form-group ">
    <label for="<?php echo $date["id"]; ?>" class="col-sm-2 control-label"><?php echo $date["placeholder"]; ?></label>
    <div class="col-sm-10">
        <?php echo form_error($date["name"]); ?>
        <?php echo form_input($date); ?>
    </div>
</div>

<div class="form-group">
    <label for="<?php echo $time["id"]; ?>" class="col-sm-2 control-label"><?php echo $time["placeholder"]; ?></label>
    <div class="col-sm-10">
        <?php echo form_error($time["name"]); ?>
        <?php echo form_input($time); ?>
    </div>
</div>

<div class="form-group">
    <label for="<?php echo $building["id"]; ?>" class="col-sm-2 control-label"><?php echo $building["placeholder"]; ?></label>
    <div class="col-sm-10">
        <?php echo form_error($building["name"]); ?>
        <?php echo form_dropdown($building["name"], get_buildings(), set_value(CI()->input->post($building["name"]), $details->building), 'class="form-control" id="building"'); ?>
    </div>
</div>

<div class="form-group">
    <label for="<?php echo $room["id"]; ?>" class="col-sm-2 control-label"><?php echo $room["placeholder"]; ?></label>
    <div class="col-sm-10">
        <?php echo form_error($room["name"]); ?>
        <?php echo form_input($room); ?>
    </div>
</div>

<div class="form-group">
    <label for="<?php echo $description["id"]; ?>" class="col-sm-2 control-label"><?php echo $description["placeholder"]; ?></label>
    <div class="col-sm-10">
        <?php echo form_error($description["name"]); ?>
        <?php echo form_textarea($description); ?>
        <span class="help-block"><?php echo lang('cla_f_description'); ?></span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Severity</label>
    <div class="col-sm-10">
        <?php echo form_error("severity"); ?>
        <?php
        $i = 0;
        foreach (array("low", "medium", "high") as $severity):
            ?>
            <?php
            $selected = "";
            if ($this->input->post("severity") == $severity || $details->severity == $severity) {
                $selected = 'checked="checked"';
            }
            ?>
            <div class="radio">
                <label>
                    <input type="radio" name="severity" id="severity_<?php echo $severity; ?>" value="<?php echo $severity; ?>" <?php echo $selected; ?>>
    <?php echo severity_scale($severity); ?>
                </label>
            </div>
<?php endforeach; ?>
    </div>
</div>

<div class="form-group">
    <label for="<?php echo $root["id"]; ?>" class="col-sm-2 control-label"><?php echo $root["placeholder"]; ?></label>
    <div class="col-sm-10">
        <?php echo form_error($root["name"]); ?>
<?php echo form_textarea($root); ?>
        <span class="help-block"><?php echo lang('cla_f_root'); ?></span>
    </div>
</div>

<div class="form-group">
    <label for="<?php echo $prevention["id"]; ?>" class="col-sm-2 control-label"><?php echo $prevention["placeholder"]; ?></label>
    <div class="col-sm-10">
        <?php echo form_error($prevention["name"]); ?>
<?php echo form_textarea($prevention); ?>
        <span class="help-block"><?php echo lang('cla_f_prevention'); ?></span>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="well">
<?php echo form_button(array("type" => "submit", "class" => "btn btn-success", "content" => '<span class="glyphicon glyphicon-pencil"></span> Save')); ?>
        </div>
    </div>
</div>



*/
<?php echo form_close(); ?>


<!-----------------Demo---------------------->
<div class ="container formcs container_content">
    <!-------Time Information ----->
    <div class="panel panel-default" id = "time_info">
        <div class="panel-heading"><h4>Time</h4></div>
        <div class="panel-body">
            <b> Date: </b> <?php echo $date['value']; ?>
            <p></p>
            <b>Time:</b> <?php echo $time['value']; ?>
            <p></p>

        </div>
    </div>

    <!-----Building Information--->

    <div class="panel panel-default" id = "building_info">
        <div class="panel-heading"><h4>Building Information</h4></div>
        <div class="panel-body">
            <b> Building Name:  </b> <?php echo $building['value']; ?>
            <p></p>
            <b>Room Number:</b> <?php echo $room['value']; ?>
            <p></p>
        </div>
    </div>

    <!---- Accident Details---->

    <div class="panel panel-default" id = "accident_info">
        <div class="panel-heading"><h4>Accident Details</h4></div>
        <div class="panel-body">
            <b>Description: </b> <?php echo $description['value']; ?>
            <p></p>
            <b>Severity:</b> <?php echo severity_scale($severity); ?>
            <p></p>
            <b>Root Cause:</b> <?php echo $root['value']; ?>
            <p></p>
            <b>Prevention:</b> <?php echo $prevention['value']; ?>
            <p></p>
        </div>
    </div>

    <div class =" panel panel-default" id="photos">
        <div class="panel-heading"><h4> Accident Photos</h4></div>
        <div class="panel-body">


            <?php
            $params = array($details->id);

            $this->load->library('gallerybuilder', $params);
            ?>


        </div>
    </div>


</div>


<!-------------------------------------------------------------Notification Modal------------------------------>
<div class="modal fade" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="notifyModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Comment Box Is Empty</h4>
            </div>
            <div class="modal-body">
                <p>The comment box cannot be empty!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Got it!</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--------------------------------------------------------------Modal---------------------------------------------->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <center> <h2><small> Photo Management</small></h2></center>

            </div>
            <div class="modal-body" id="modal-body">
            </div>
            <div class="modal-footer">
                <div class="content hidden" id="image-description" style="float:left;">Our Description here.  This will go under the photos</div>
                <div class="hidden"> <textarea class="form-control" rows="3" placeholder="" value="Our Description here.  This will go under the photos"></textarea> <br/><input class="btn btn-primary" type="submit" value="Submit Changes" ></div><br/>
                <!----------------------------------------------------Toggle Edit Button------------------------------------------------------------------->

                <!-- Single button -->
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">

                        <span class="glyphicon glyphicon-cog"></span><span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#" id="edit-description"><span class="glyphicon glyphicon-pencil" ></span>  Edit Description</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Remove Photo</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
<!------------------------------------------------------Modal Assets------------------------------------------------------------------->
<div class="comment_container container_content container">
    <h5><b>Accident Comments</b></h5>

    <!-----------------------------------------------Comments Area--------------------------------------------------------------------->
    <div class="comment_container_box" id ="comment_container_box" style="width:auto;padding:15px; background-color:#f5f5f5; border:1px solid #dddddd;">
        <form method="post" name="form" action="">
            <textarea class="form-control" rows="1" placeholder="What would you like to say?" id ="comment_content" onfocus="stateChange()" required></textarea>

    </div>
    <input type="submit" class="btn btn-primary hidden" id="post_button" value="Post" style="margin-top:10px; float:right;  margin-bottom:10px;" />
</form>
<div class="horizontal_spacer" style="min-height:50px;"></div>
<?php
$accidentid = $details->id;

$params = array('accidentid' => $accidentid,
    'cmd' => 'print');

$this->load->library('commenthandler', $params);
?>  
</div>
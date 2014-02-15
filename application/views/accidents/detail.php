

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



<style>

    #photoview{
        max-width: 815px;
        margin:0 auto;
        margin-bottom:50px;
        height:auto;
        width:auto !important;

        padding-left:15px;
    }
    .equamargins{
        width: 90% !important;
    }
    .formcs{
        max-width:900px;
        margin:0 auto;


    }



</style>
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
<?php $i = 0;
foreach (array("low", "medium", "high") as $severity): ?>
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
<div class ="container formcs">
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
        <div class="panel-heading"><h4>Accident Photos</h4></div>
        <div class="panel-body">


<?php
$params = array($details->id);

$this->load->library('gallerybuilder', $params);
?>



            <!-----------------Demo---------------------->
        </div>
    </div>
    
<?php echo form_open("accidents/comment", array("class" => "form-horizontal formcs", "role" => "form","encytype" =>"")); ?>
    
    
    
    
    
    
    <?php echo form_button(array("type" => "submit", "class" => "btn btn-success", "content" => '<span class="glyphicon glyphicon-plus"></span> Add Report')); ?>
<?php echo form_close(); ?>
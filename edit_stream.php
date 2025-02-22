<?php 
include 'head.php';

$error=NULL;
$case=1;
$con = new db_connect();

$connection=$con->connect();
$insert = new Select_DB($con->connect);

if(isset($_GET['stream_id']))
{
    $id = base64_decode($_GET['stream_id']);
    $stream = $insert->get_stream($id);
}

if(isset($_SESSION['user_info'])){

if(isset($_POST['save']))
{ 
if($_POST['name']=='' || $_POST['name']==NULL)
{
    $case=0;
    $error="<li>Please Enter Reseller Name</li>";
    
}

if($case==1){

if($connection==1){
         
    //var_dump($_POST['play_pool']);
    $connection=$insert->edit_stream($id, $_POST['category'], $_POST['name'], isset($_POST['source_pool']) ? $_POST['source_pool'] : '', isset($_POST['play_pool']) ? $_POST['play_pool'] : '', $_POST['method'], isset($_POST['servers']) ? $_POST['servers'] : '[]', $_POST['transcoding'], $_POST['epg'], $_POST['epg_channel'], $_POST['native_frame'], $_POST['flag'],$_POST['proxy'], $_POST['agent'], $_POST['auto_restart'], $_POST['logo'], isset($_POST['demand']) ? $_POST['demand'] : 'off', $stream['stream_status'], isset($_POST['restart']) ? $_POST['restart'] : 'off');

    if($connection)
    {
        $text = base64_encode('Edit Stream Succeded. Please check with search box.');
        echo "<script>location.href='manage_stream.php?text=".$text."'</script>";
    }
    else
    {
        $case = 0;
        $error = "<li>Database Connection Error! Please try again.</li>";
    }
}
}
}


// else
// {
//   echo "<script>location.href='manage_stream.php'</script>";
// }

$categories = $insert->get_categories();
$servers = $insert->get_servers();
$transcodes = $insert->get_transcodes();
$epgs = $insert->get_epgs();

// $url = "http://venomtvstream.ddns.net/";
// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_HEADER, true);
// curl_setopt($curl, CURLOPT_TIMEOUT, 10);
// curl_setopt($curl, CURLOPT_NOBODY, true);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// $data = curl_exec($curl);
// curl_close($curl);
// preg_match("/HTTP\/1\.[1|0]\s(\d{3})/",$data,$matches);
// echo ($matches[1] == 200 ? "connected" : "Not connected");

?>
<!-- PAGE CONTENT -->
<?php
  if($_SESSION['user_info']['user_is_admin'] == 1 && !empty($_SESSION)){
  ?>
<div class="page-content"> 
  
  <!-- START X-NAVIGATION VERTICAL -->
  <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
    <!-- TOGGLE NAVIGATION -->
    <li class="xn-icon-button"> <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a> </li>
    <!-- END TOGGLE NAVIGATION --> 
    
    <!-- SIGN OUT -->
    <li class="xn-icon-button" style = "position:relative; float:right;">
     <a href="<?php echo SITE_URL; ?>logout.php" class="mb-control" data-box="#mb-signout" style="margin-left:-50px; width:100px"><span class="fa fa-sign-out"></span> Logout</a> </li>
    <!-- END SIGN OUT -->
    
  </ul>
  <!-- END X-NAVIGATION VERTICAL --> 
  
  <!-- START BREADCRUMB -->
  <ul class="breadcrumb">
    <li><a href="#">Admin </a></li>
    <li><a href="#"> Stream Management </a></li>
    <li class="active"> Edit Stream </li>
  </ul>
  <!-- END BREADCRUMB --> 
  
  <!-- PAGE CONTENT WRAPPER -->
  <div class="page-content-wrap">
    <?php if($case==0){?>
    <div class="alert alert-danger" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
      <strong>ERROR!</strong> <?php echo $error; ?> </div>
    <?php }else if($case == 2){ ?>
        <div class="alert alert-success" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
      <strong>NOTICE!</strong> <?php echo $notice; ?> </div>
    <?php }?>
    <div class="row">
      <div class="col-md-12">
        <form class="form-horizontal" enctype="multipart/form-data" method="post">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Edit</strong> Stream</h3>
              <ul class="panel-controls">
                <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
              </ul>
            </div>
            <div class="panel-body">

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM NAME</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group">
                    <select name = "category" style = "margin-right:20px; border-radius: 4px;" value = "33">
                     <?php while($category = mysqli_fetch_assoc($categories)){
                        echo '<option value="'.$category['stream_category_id'].'"'.($stream['stream_category_id'] == $category['stream_category_id'] ? "selected" : "").'>'.$category['stream_category_name'].'</option>';
                     }?> 
                    </select>
                    <input type="text" name = "name" class="form-control" style = "border-radius: 4px;" value = '<?php echo $stream['stream_name'];?>' required>
                  </div>
                  <span class="help-block">Enter Stream Category and Name(Please don't include "'" or include "\'"" here.)</span> </div>
              </div>
              
              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM SOURCE</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group">
                    <select name = "link_category" style = "margin-right:20px; border-radius: 4px;">
                     <option value = "1"> LINK </option>
                    </select>
                    <input type="text" name = "link" id = "linkbox" class="form-control" style = "border-radius: 4px;">
                    <a class = "btn btn-success" style = "margin-left:15px; border-radius: 4px; color:white" onclick = "addLink()"> ADD </a>
                  </div>
                  <span class="help-block">Choose an Option and press CTRL + C to copy to the clipboard</span> 
                  <div class="input-group" style = "margin-top: 10px;">
                      <div class = "row">
                        <div class = "col-md-4" >
                          <select class="custom-select"  style = "width:250px; height:250px; font-size: 16px;" id = "source_box" multiple>
                            <?php
                            $sources = json_decode($stream['stream_source_pool']);
                            for($i = 0; $i < count($sources); $i ++)
                              echo '<option value = '.$sources[$i].'>'.$sources[$i].'</option>';
                          ?>  
                          </select>
                        </div>
                        <div class = "col-md-2">
                          <span style = "margin-left:50%; margin-top:30px;"class = "btn btn-default fa fa-arrow-up" onclick = "toUp()"></span>
                          <span style = "margin-left:50%; margin-top:10px;"class = "btn btn-default fa fa-arrow-right" onclick = "toRight()"></span>
                          <span style = "margin-left:50%; margin-top:10px;"class = "btn btn-default fa fa-arrow-left" onclick = "toLeft()"></span>
                          <span style = "margin-left:50%; margin-top:10px;"class = "btn btn-default fa fa-arrow-down" onclick = "toDown()"></span>
                          <span style = "margin-left:50%; margin-top:10px;"class = "btn btn-default fa fa-times" onclick = "toTrash()"></span>
                        </div>
                        <div class = "col-md-4">
                          <select class="custom-select"  style = "width:250px;  height:250px; font-size: 16px;" id = "play_box" multiple>
                            <?php
                            $plays = json_decode($stream['stream_play_pool']);
                            foreach($plays as $play)
                              echo '<option value = '.$play.' >'.$play.'</option>';
                          ?> 
                          </select>
                        </div>
                        <div class = "col-md-2">
                          <?php
                            $server_id = json_decode($stream['stream_server_id'], true);
                            $stream_status = json_decode($stream['stream_status'], true);
                            $val = $stream_status[0];
                            $stream_is_online = 0;
                            //var_dump($stream_status);
                            //exit();
                            for($i = 0; $i < count($server_id); $i ++)
                            {
                              if(!isset($val[intval($server_id[$i])]))
                                break;
                              
                              $check = $val[intval($server_id[$i])];

                              if($check == 1)
                                $stream_is_online = 1;
                            } 
                            echo '<div style = "margin-top:6px;">';
                            $plays = json_decode($stream['stream_play_pool']);
                            for($i = 0; $i < count($plays); $i ++)
                            {
                              if($i == intval($stream['stream_play_pool_id']) && $stream_is_online)
                                echo '<span class = "badge badge-success" style = "margin-left:10px; margin-bottom:10px; width:100%"> online </span>';
                              else
                                echo '<span class = "badge badge-danger" style = "margin-left:10px; margin-bottom:10px; width:100%"> offline </span>';
                            }
                            echo '</div>';
                          ?> 
                        </div>
                        <input type = "text" name = "source_pool" id = "source_str" hidden>
                        <input type = "text" name = "play_pool" id = "play_str" hidden>
                      </div>
                  </div>
                </div>
              </div>

              <div class="form-group">

                <label class="col-md-3 col-xs-12 control-label">STREAM METHOD</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group">
                    <select name = "method" style = "margin-right:20px; border-radius: 4px; height:30px; width:160px;">
                        <option value = "1" <?php echo $stream['stream_method'] == "1"? "selected" : "" ;?>> Live Streaming </option>
                        <option value = "2" <?php echo $stream['stream_method'] == "2"? "selected" : "" ;?>> Copy Streaming </option>
                        <option value = "3" <?php echo $stream['stream_method'] == "3"? "selected" : "" ;?>> Local Streaming </option>
                        <option value = "4" <?php echo $stream['stream_method'] == "4"? "selected" : ""; ?>> Loop Streaming </option>
                        <option value = "5" <?php echo $stream['stream_method'] == "5"? "selected" : "";?>> Adaptive Streaming </option>
                    </select>
                  </div>
                  <span class="help-block">Choose Stream Method</span> </div>
              </div>
             
              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM SERVER</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group">
                      <select name = "servers[ ]" id = "server" class="serverselect" multiple='multiple' >
                        <?php while($server = mysqli_fetch_assoc($servers)){

                          if($stream['stream_server_id'] == '')
                            $stream_servers = array();
                          else
                            $stream_servers = json_decode($stream['stream_server_id']);

                            if($server['server_id'] != 1)
                                echo '<option value="'.$server['server_id'].'"'.(in_array($server['server_id'], $stream_servers) ? "selected" : "").'>'.$server['server_name'].'</option>';
                        }?> 
                      </select>
                  </div>
                  <span class="help-block">Choose Stream Server</span> 
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM TRANSCODING</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group">
                    <select name = "transcoding" style = "margin-right:20px; border-radius: 4px; height:30px; width:160px;">
                      <option value="">No Transcoding</option>;
                        <?php while($transcode = mysqli_fetch_assoc($transcodes)){
                            echo '<option value="'.$transcode['transcoding_id'].'"'.($stream['stream_transcode_id'] == $transcode['transcoding_id'] ? "selected" : "").'>'.$transcode['transcoding_name'].'</option>';
                        }?> 
                    </select>
                  </div>
                  <span class="help-block">Choose Transcoding</span> </div>
                </div>

            <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM EPG</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group">
                    <select name = "epg" style = "margin-right:20px; border-radius: 4px; height:30px; width:160px;">
                        <?php while($epg = mysqli_fetch_assoc($epgs)){
                            echo '<option value="'.$epg['epg_id'].'"'.($stream['stream_epg_id'] == $epg['epg_id'] ? "selected" : "").'>'.$epg['epg_name'].'</option>';
                        }?> 
                    </select>
                  </div>
                  <span class="help-block">Choose EPG</span> </div>
                </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM EPG CHANNEL</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                    <input type="text" name="epg_channel" class="form-control" placeholder="0" value = '<?php echo $stream['stream_epg_channel_id'];?>'/>
                  </div>
                  <span class="help-block">Enter Stream Channel</span> </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM NATIVE FRAMES</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group"> 
                    <select name = "native_frame" style = "margin-right:20px; border-radius: 4px; height:30px; width:160px;" value = '<?php echo $stream['stream_native_frame'];?>'>
                        <option value = "0" <?php echo $stream['stream_native_frame'] == "0"? "selected" : "" ;?>> No </option>
                        <option value = "1" <?php echo $stream['stream_native_frame'] == "1"? "selected" : "" ;?>> Yes </option>
                    </select>
                  </div>
                  <span class="help-block">Choose Native Frame</span> </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">FORMAT FLAGS</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                    <input type="text" name="flag" class="form-control" value = '<?php echo $stream['stream_format_flags'];?>'/>
                  </div>
                  <span class="help-block">Enter format flags</span> </div>
              </div>
              
              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM HTTP PROXY</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                    <input type="text" name="proxy" class="form-control" value = '<?php echo $stream['stream_http_proxy'];?>'/>
                  </div>
                  <span class="help-block">Enter Stream Http Proxy</span> </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM USER AGENT</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                    <input type="text" name="agent" class="form-control" value = '<?php echo $stream['stream_user_agent'];?>'/>
                  </div>
                  <span class="help-block">Enter Stream User Agent</span> </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">AUTO RESTART STREAM</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group">
                    <div class="input-append date" id="datetimepicker" data-date="12-02-2012" data-date-format="dd-mm-yyyy h:i">
                        <input style = "height:32px; border-radius: 4px;"size="24" name = "auto_restart" type="text" value="<?php echo $stream['stream_auto_restart']?>" readonly>
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                </div>
                <span class="help-block">Enter Auto Restart Stream</span> </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">CHANNEL LOGO</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                    <input type="text" name="logo" class="form-control" value = '<?php echo $stream['stream_logo'];?>'/>
                  </div>
                  <span class="help-block">Enter Stream Channel Logo</span> </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">STREAM ON DEMAND</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group" style = "margin-top: 5px;"> 
                    <label>
                    <input type="checkbox" name = "demand" class="flat-green" <?php echo $stream['stream_is_demand'] == '1' ? "checked" : "";?>>
                  </label>
                  </div>
                  <span class="help-block">Check if your stream is on demand</span> </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">RESTART ON SUBMIT</label>
                <div class="col-md-6 col-xs-12">
                  <div class="input-group"> 
                    <label>
                    <input type="checkbox" name = "restart" class="flat-green">
                  </label>
                  </div>
                  <span class="help-block">Check if you want to restart stream after submit.</span> </div>
              </div>

              <!-- <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label">SHOW</label>
                <div class="col-md-6 col-xs-12">
                   <div align="justify" id="livemedia" style="width: 621px; height: 384px;">
                   </div>
              </div>
            </div> -->

            <div class="panel-footer">
              <input type="reset" class="btn btn-default" value="Clear Form" />
              <input type="submit" name="save" class="btn btn-primary pull-right" value="Submit" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END PAGE CONTENT --> 
<?php
  }
  else { ?>
    echo "<script>location.href='index.php'</script>";
<?php }?>
<!-- MESSAGE BOX-->
<div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
  <div class="mb-container">
    <div class="mb-middle">
      <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
      <div class="mb-content">
        <p>Are you sure you want to log out?</p>
        <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
      </div>
      <div class="mb-footer">
        <div class="pull-right"> <a href="logout.php" class="btn btn-success btn-lg">Yes</a>
          <button class="btn btn-default btn-lg mb-control-close">No</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="url-box animated fadeIn" data-sound="alert" id="mb-link">
  <div class="mb-container">
    <div class="mb-middle">
      <div class="mb-title">Edit <strong>LINK</strong> </div>
      <div class="mb-content">
          <div class = "row">
            <div class = "col-md-12">
              <input class="form-control" type="text" id="link" style = "font-size:16px;"placeholder="Source Link..." >
            </div>
          </div>   
        <div class = "row" style = "margin-top:20px; margin-bottom:20px;">
          <div class = "col-md-7">
          </div>
          <div class = "col-md-5">
           <button class="btn btn-success" style = "width:60px; height:30px; font-size:16px;" onclick = "saveToSelect()">Save</button>
            <button class="btn btn-default  url-control-close" style = "margin-left:20px; width:60px; height:30px; font-size:16px;" >Exit</button>
          </div>
        </div>

    </div>
  </div>
</div>
</div>

<!-- END MESSAGE BOX--> 

<!-- START PRELOADS -->
<audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
<!-- END PRELOADS --> 

<!-- START SCRIPTS --> 
<!-- START PLUGINS --> 
<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script> 
<script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script> 
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script> 
<!-- END PLUGINS --> 

<!-- THIS PAGE PLUGINS --> 
<script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script> 
<script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script> 
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script> 
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script> 
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script> 
<script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script> 
<!-- END THIS PAGE PLUGINS --> 

<!-- START TEMPLATE --> 

<script type="text/javascript" src="js/plugins.js"></script> 
<script type="text/javascript" src="js/actions.js"></script> 
<!-- END TEMPLATE -->

<script type="text/javascript" src="js/jquery.multi-select.js"></script>
<script type="text/javascript" src="js/jquery.quicksearch.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
<!-- <script src="https://cdn.jwplayer.com/players/UVQWMA4o-kGWxh33Q.js"></script> -->

<!-- END SCRIPTS --> 
<script>

    function collectSource()
    {
      var source_arr = [];
      var play_arr = [];
      
      for(i=source_box.options.length-1;i>=0;i--)
          source_arr.push('"' + source_box.options[i].value + '"');
      
      for(i=play_box.options.length-1;i>=0;i--)
          play_arr.push('"' + play_box.options[i].value + '"');
      
      $("#source_str")[0].value = source_arr;
      $("#play_str")[0].value = play_arr;
      //console.log(source_arr);
      //console.log(play_arr);

      // jwplayer("livemedia").setup({
      //   "skin":"http://www.tpai.tv/swf/jwplayer/skins/nacht/nacht.zip",
      //   "id":"livemediaplayer",
      //   "autostart":"false",
      //   "rtmp.subscribe":"true",
      //   "file":"",
      //   "controlbar":"none",
      //   "volume":100,
      //   "width":"100%",
      //   "height":"100%",
      //   "modes":[{type:"flash",src:"http://www.tpai.tv/swf/jwplayer/player.swf"}]});
    }

    document.getElementById('play_box').ondblclick = function(){
    //alert(this.selectedIndex);
    // or alert(this.options[this.selectedIndex].value);
    idx = this.selectedIndex;
    window.link_idx = idx;

    $("#link")[0].value = play_box.options[idx].value;
    var box = $("#mb-link");
    box.addClass("open");
    };

    function saveToSelect()
    {
        play_box.options[window.link_idx].value = $("#link")[0].value;
        play_box.options[window.link_idx].text = $("#link")[0].value;

        $("#mb-link").removeClass("open");
    }

    $(document).ready(function(){
        $("ul.streams_li:nth-child(2)").addClass("active");
        $("#streams_li").addClass("active");
        collectSource();
    });

    $('#datetimepicker').datetimepicker({
    format: 'yyyy-mm-dd hh:ii'
    });
    $('#datetimepicker').datetimepicker('setStartDate', '2019-11-01 00:00');

  $('.serverselect').multiSelect({
      selectableHeader: "<label class='control-label' style = 'margin-top:-20px; margin-bottom:10px;'>AVAILABLE SERVER</label>",
      selectionHeader: "<label class='control-label' style = 'margin-top:-20px; margin-bottom:10px;'>SERVER FOR THIS STREAM</label>",
      afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
      },
      afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
      }
    });

    $('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });

    function addLink()
    {
        var link = $("#linkbox")[0].value;
        console.log(link);
        var source_box = $('#source_box');
        source_box.append('<option value="' + link + '" >' + link + '</option>');
        $("#linkbox")[0].value = '';
        collectSource();
    }

    function toRight()
    {
      var i;
      for(i=source_box.options.length-1;i>=0;i--)
      {
        if(source_box.options[i].selected)
        {
          var optn = document.createElement("OPTION");
          optn.text = source_box.options[i].text;
          optn.value = source_box.options[i].value;
          play_box.options.add(optn);
          source_box.remove(i);
        }
      }
      collectSource();
    }

    function toLeft()
    {
      var i;
      for(i=play_box.options.length-1;i>=0;i--)
      {
        if(play_box.options[i].selected)
        {
          var optn = document.createElement("OPTION");
          optn.text = play_box.options[i].text;
          optn.value = play_box.options[i].value;
          source_box.options.add(optn);
          play_box.remove(i);
        }
      }
      collectSource();
    }

    function toUp() {
      
      var selectList = document.getElementById("source_box");
      var selectOptions = selectList.getElementsByTagName('option');
      
      for (var i = 1; i < selectOptions.length; i++) {
        var opt = selectOptions[i];
        if (opt.selected) {
          selectList.removeChild(opt);
          selectList.insertBefore(opt, selectOptions[i - 1]);
        }
      }

      selectList = document.getElementById("play_box");
      selectOptions = selectList.getElementsByTagName('option');
      console.log(selectOptions);
      for (var i = 1; i < selectOptions.length; i++) {
        var opt = selectOptions[i];
        if (opt.selected) {
          selectList.removeChild(opt);
          selectList.insertBefore(opt, selectOptions[i - 1]);
        }
      }
    }

    function toDown() {
      var selectList = document.getElementById("play_box");
      var selectOptions = selectList.getElementsByTagName('option');
      for (var i = selectOptions.length - 2; i >= 0; i--) {
        var opt = selectOptions[i];
        if (opt.selected) {
           var nextOpt = selectOptions[i + 1];
           opt = selectList.removeChild(opt);
           nextOpt = selectList.replaceChild(opt, nextOpt);
           selectList.insertBefore(nextOpt, opt);
        }
      }

      selectList = document.getElementById("source_box");
      selectOptions = selectList.getElementsByTagName('option');
      for (var i = selectOptions.length - 2; i >= 0; i--) {
        var opt = selectOptions[i];
        if (opt.selected) {
           var nextOpt = selectOptions[i + 1];
           opt = selectList.removeChild(opt);
           nextOpt = selectList.replaceChild(opt, nextOpt);
           selectList.insertBefore(nextOpt, opt);
        }
      }
    }

    function toTrash()
    {
      var i;
      for(i=source_box.options.length-1;i>=0;i--)
      {
        if(source_box.options[i].selected)
          source_box.remove(i);
      }
      collectSource();
    }

    </script>
</body></html><?php }else{
    
    echo "You are not authorized to visit this page direclty,Sorry";
    } ?>

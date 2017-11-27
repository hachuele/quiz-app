<?php

    if(!isset($user_id)){ $user_id = 'N/A';}

    #NEED TO GET USER ID THROUGH SHIB ENV VARIABLES
    #my $pi_sql = "select pi_id from pi_info where pi_rcf_user='$ENV{ShibuscNetID}'";
    $user_id = 'hachuelb';

    $help_modal_title = 'HPC QUIZZ HELP';
    $help_modal_txt = 'Please complete the selected Quizz...';

    $completed_coursework = "";
    $completed_coursework_tbl = "";

    #GET COMPLETED COURSEWORK FROM DATABASE FOR GIVEN USER
    $completed_coursework = array('HPC New User'=>'11/7/17', 'Intro to Linux'=>'11/7/17', 'HPC Installing Software'=>'11/9/17', 'HPC Advanced Topics'=>'11/10/17');


    if(($completed_coursework != "")){
        #TODO: CHANGE THIS AND DO IT THE SAME WAY AS FOREACH FOR THE COURSES AVAILABLE IN PUBLIC PAGE
        #WILL ADD IN PROGRESS COURSES (ON CLICK VIEW ANSWER, INFORMATION STORED.)
        foreach($completed_coursework as $x => $x_date) {
            $completed_coursework_tbl .= "<div class='alert alert-success completed_course'><strong>".$x."</strong><span style='float:right;'><i>  (Completed on: ".$x_date.")</i></span></div>";
        }
    }
    else{
        $completed_coursework_tbl .= "<div class='alert alert-danger'> No courses completed.</div>";
    }



?>

        <!-- Help Modal -->
        <div id="HelpModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Help Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo h($help_modal_title); ?></h4>
              </div>
              <div class="modal-body">
                <p><?php echo h($help_modal_txt); ?></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- User Info Modal -->
        <div id="UserInfoModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Help Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">COMPLETED QUIZZES FOR: <strong><?php echo h($user_id); ?></strong></h4>
              </div>
              <div class="modal-body">
                  <?php foreach($completed_coursework as $course => $compl_date) { ?>
                  <div class="alert alert-success completed_course">
                      <strong><?php echo h($course); ?></strong>
                      <span style='float:right;'><i>(Completed on: <?php echo h($compl_date); ?>)</i></span>
                  </div>
                  <?php } ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div id="footer_row" class="row footer container-fluid">
            <div class="col-xs-6">
                <button id="user_id_button" type="button" class="btn btn-info btn-sm" style="float: left;" data-toggle="modal" data-target="#UserInfoModal">
                    <span class="glyphicon glyphicon-user"></span> <?php echo h($user_id); ?>
                </button>
            </div>
            <div class="col-xs-6">
                <button id="help_button" type="button" class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#HelpModal">
                    <span class="glyphicon glyphicon-question-sign"></span> Help
                </button>
            </div>
        </div>


    </body>
</html>

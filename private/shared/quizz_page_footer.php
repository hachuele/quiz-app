<?php

    /******************************************************************
     * DESCRIPTION:
     *
     *                             ----
     * @author: Eric J. Hachuel
     * University of Southern California, High-Performance Computing
     ******************************************************************/

    $help_modal_title = 'HPC QUIZZ HELP';
    $help_modal_txt = 'Please complete the selected Quizz...';






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


        <div id="footer_row" class="row footer container-fluid">
            <div class="col-xs-6">
                <button id="user_id_button" type="button" class="btn btn-info btn-sm" style="float: left;">
                    <span class="glyphicon glyphicon-user"></span> <?php echo h($_SESSION["user_id"]); ?>
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


<!--DISCONNECT FROM THE DATABASE-->
<?php
    db_disconnect($db);
?>

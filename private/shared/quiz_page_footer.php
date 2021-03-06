<?php
/**************************************************************************
* DESCRIPTION: quiz_page_footer.php serves as a modular footer
* to use accross multiple pages in the site. Also serves as the
* exit point for the database
*                             ----
* @author: Eric J. Hachuel
* Copyright 2018 University of Southern California. All rights reserved.
*
* DISCLAIMER.  USC MAKES NO EXPRESS OR IMPLIED WARRANTIES, EITHER IN FACT OR
* BY OPERATION OF LAW, BY STATUTE OR OTHERWISE, AND USC SPECIFICALLY AND
* EXPRESSLY DISCLAIMS ANY EXPRESS OR IMPLIED WARRANTY OF MERCHANTABILITY OR
* FITNESS FOR A PARTICULAR PURPOSE, VALIDITY OF THE SOFTWARE OR ANY OTHER
* INTELLECTUAL PROPERTY RIGHTS OR NON-INFRINGEMENT OF THE INTELLECTUAL
* PROPERTY OR OTHER RIGHTS OF ANY THIRD PARTY. SOFTWARE IS MADE AVAILABLE
* AS-IS.
****************************************************************************/
?>
        <div id="HelpModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
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
            <div class="col-xs-3">
                <button id="user_id_button" type="button" class="btn btn-primary btn-sm" style="float: left;">
                    <span class="glyphicon glyphicon-user"></span> <?php echo h($user_id); ?>
                </button>
            </div>
            <div class="col-xs-6">
                <p style="margin-top: 20px; font-size: 10px; color: #757575;">&copy; Copyright 2018 University of Southern California. All rights reserved.</p>
            </div>
            <div class="col-xs-3">
                <button id="help_button" type="button" class="btn btn-grey-lighten btn-sm" style="float: right;" data-toggle="modal" data-target="#HelpModal">
                    <span class="glyphicon glyphicon-question-sign"></span> HELP
                </button>
            </div>
        </div>
    </body>
</html>


<?php
    /* disconnect from the db */
    db_disconnect($db);
?>

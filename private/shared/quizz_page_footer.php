<?php
/***********************************************************************
 * DESCRIPTION: quizz_page_footer.php serves as a modular footer
 * to use accross multiple pages in the site. Also serves as the
 * exit point for the database
 *                             ----
 * @author: Eric J. Hachuel
 * Copyright 2018 University of Southern California. All rights reserved.
 *
 * This software is experimental in nature and is provided on an AS-IS basis only.
 * The University SPECIFICALLY DISCLAIMS ALL WARRANTIES, EXPRESS AND IMPLIED,
 * INCLUDING WITHOUT LIMITATION ANY WARRANTY AS TO MERCHANTIBILITY OR FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * This software may be reproduced and used for non-commercial purposes only,
 * so long as this copyright notice is reproduced with each such copy made.
 *
 * ----------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ***********************************************************************/
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

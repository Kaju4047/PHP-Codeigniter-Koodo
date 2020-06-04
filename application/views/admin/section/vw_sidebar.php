<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
   <section class="sidebar">
      <ul class="sidebar-menu">
         <li class="header">MAIN NAVIGATION</li>
         <li class="dashboardLi">
            <a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
         </li>
         <?php

         $fld = 'UA_priviliges';
         $userid = $this->session->userdata['UID'];
          
         $condition = array('UA_pkey' => $userid);
         $privilige = $this->Md_database->getData('useradmin', $fld, $condition, '', '');
         $privilige = !empty($privilige[0]['UA_priviliges']) ? explode(',', $privilige[0]['UA_priviliges']) : '';
         // print_r($privilige1);exit();
         if (!empty($privilige)) {

         ?>

         <?php
            if (in_array('CMS', $privilige)) {?>
               <li class="staffLi">
                  <a href="<?php echo base_url(); ?>admin/cms"><i class="fa fa-pie-chart"></i><span>CMS </span></a>
               </li>
           <?php }else{}
            // (in_array('career', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); 
         ?>
           <?php
            if (in_array('master', $privilige)) {?>
                 <li class="masterLi" id="adminMasterLi">
                     <a href="">
                     <i class="fa fa-table"></i> <span>Master</span>
                     <span class="pull-right-container">
                     <i class="fa fa-angle-down pull-right"></i>
                     </span>
                     </a>
                      <ul class="treeview-menu">
                        <li class="sportsLi"><a href="<?php echo base_url(); ?>admin/sport"><i class="fa fa-list"></i><span>Sport</span></a></li>
                        <li class="taxLi"><a href="<?php echo base_url(); ?>admin/tax"><i class="fa fa-list"></i><span>Tax</span></a></li>
                        <li class="cityLi"><a href="<?php echo base_url(); ?>admin/city"><i class="fa fa-list"></i><span>City</span></a></li>
                        <li class="serviceLi"><a href="<?php echo base_url(); ?>admin/services"><i class="fa fa-list"></i><span>Services</span></a></li>
                     </ul>
                  </li>
           <?php }else{}
         ?>
          <?php
            if (in_array('subscription', $privilige)) {?>
               <li class="subscLi">
                  <a href="<?php echo base_url(); ?>admin/subscription"><i class="fa fa-address-card"></i><span> Subscription</span></a>
               </li>
           <?php  }else{}
         ?>
          <?php
            if (in_array('buy_subscription', $privilige)) {?>
               <li class="buysubscLi">
                  <a href="<?php echo base_url(); ?>admin/buy-subscription"><i class="fa fa-shopping-cart"></i><span> Buy Subscription</span></a>
               </li>
               <?php
            }else{}
         ?>
         <?php
            if (in_array('career', $privilige)) {?>
                <li class="careerLi">
                  <a href="<?php echo base_url(); ?>admin/career-list"><i class="fa fa-graduation-cap"></i><span>Get Hired</span></a>
               </li>
            <?php }else{}
         ?> 
         <?php
            if (in_array('reviews', $privilige)) {?>
                <li class="usersLi">
                  <a href="<?php echo base_url(); ?>admin/users-list"><i class="fa fa-user-o"></i><span>Users</span></a>
               </li>
            <?php }else{}
         ?>
         <?php
            if (in_array('users', $privilige)) {?>
  
                <li class="userevwsLi">
                  <a href="<?php echo base_url(); ?>admin/user-reviews-list"><i class="fa fa-comment-o"></i><span>User Reviews</span></a>
               </li>
                  <?php }else{}
         ?>
         <?php
            if (in_array('dealer_product', $privilige) || in_array('used_product', $privilige) ) {?>
                <li class="prodsLi" id="prodsMasterLi">
                  <a href="">
                  <i class="fa fa-cube"></i> <span>Products</span>
                  <span class="pull-right-container">
                  <i class="fa fa-angle-down pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                      <?php
                        if (in_array('dealer_product', $privilige)) {?>
                           <li class="dealprodsLi"><a href="<?php echo base_url(); ?>admin/dealer-products"><i class="fa fa-list"></i><span>Dealer Products</span></a></li>
                       <?php  }else{}
                     ?> 
                     <?php
                        if (in_array('used_product', $privilige)) {?>
                           <li class="userprodsLi"><a href="<?php echo base_url(); ?>admin/used-products"><i class="fa fa-list"></i><span>Used Products</span></a></li>
                        <?php }else{}
                     ?>

                  </ul>
               </li>
           <?php }else{}
         ?>

           <?php
            if (in_array('academy_list', $privilige)) {?>
                <li class="academicLi">
                  <a href="<?php echo base_url(); ?>admin/academic-list"><i class="fa fa-folder-open"></i><span>Coach Academy Listing</span></a>
              </li>
            <?php }else{}
         ?> 
         <?php
            if (in_array('tornaments', $privilige)) {?>

               <li class="tournLi">
                  <a href="<?php echo base_url(); ?>admin/tournaments-list"><i class="fa fa-trophy"></i><span>Tournaments</span></a>
               </li>
            <?php }else{}
         ?> 
        <?php if (in_array('enquiry', $privilige) || in_array('private_enquiry', $privilige) ) {?>
             <li class="mainenqsLi" id="enqMasterLi">
                 <a href="">
                 <i class="fa fa-question-circle"></i> <span>Enquiries</span>
                 <span class="pull-right-container">
                 <i class="fa fa-angle-down pull-right"></i>
                 </span>
                 </a>
                 <ul class="treeview-menu">
                    <?php
                        if (in_array('enquiry', $privilige)) {?>
                           <li class="enqsLi"><a href="<?php echo base_url(); ?>admin/enquiries"><i class="fa fa-list"></i> <span>Enquiries</span></a></li>
                        <?php }else{}
                         if (in_array('private_enquiry', $privilige)) {?>
                           <li class="prvcoenqLi"><a href="<?php echo base_url(); ?>admin/private-coaching-enquiry"><i class="fa fa-list"></i> <span>Private Coach Enquiry</span></a></li>
                        <?php }else{}?>
                 </ul>
            </li>
         <?php }?>
          <?php
            if (in_array('sportbook', $privilige)){?>
               <li class="sportsbookLi">
                  <a href="<?php echo base_url(); ?>admin/sports-book"><i class="fa fa-book"></i><span>Wall</span></a>
               </li>
            <?php }else{}
         ?>
          <?php
            if (in_array('sport_new', $privilige)) {?>
               <li class="sportsnewsLi">
                  <a href="<?php echo base_url(); ?>admin/advertise-with-us"><i class="fa fa-newspaper-o"></i><span>Advertise With Us</span></a>
               </li>
           <?php  }else{}
         ?>
         <?php
            if (in_array('sport_video', $privilige)) {?>
                <li class="sportvidLi">
                  <a href="<?php echo base_url(); ?>admin/sports-videos-list"><i class="fa fa-video-camera"></i><span>Sports Videos</span></a>
               </li>
            <?php }else{}
         ?>
         <?php
            if (in_array('sport_facility', $privilige)) {?>
                <li class="sportclubLi">
                  <a href="<?php echo base_url(); ?>admin/sports-clubs-list"><i class="fa fa-futbol-o"></i><span>Sports Facility</span></a>
               </li>
            <?php }else{}
         ?>
         <?php
            if (in_array('transaction', $privilige)) {?>
               <li class="transhisLi">
                  <a href="<?php echo base_url(); ?>admin/transaction-history"><i class="fa fa-exchange custom"></i><span>Transaction History</span></a>
               </li>
            <?php }else{}
         ?>
         <?php
            if (in_array('advertisement', $privilige)) {?>
               <li class="advLi">
                  <a href="<?php echo base_url(); ?>admin/advertisement-list"><i class="fa fa-tv"></i><span>Advertisement</span></a>
               </li>
           <?php  }else{}
         ?>
          <?php
            if (in_array('cust_note', $privilige)) {?>
                 <li class="notifiLi">
                  <a href="<?php echo base_url(); ?>admin/notification"><i class="fa fa-bell-o"></i><span>Custom Notifications</span></a>
               </li>
            <?php }else{}
         ?>
         <?php
            if (in_array('invitation', $privilige)) {?>
                 <li class="usrinvtLi">
                  <a href="<?php echo base_url(); ?>admin/user-invitation-list"><i class="fa fa-user-circle"></i><span>Users Invitation</span></a>
               </li>
            <?php }else{}
         ?> 
         <?php
            if (in_array('system_user', $privilige)) {?>
               <li class="sysLi">
                  <a href="<?php echo base_url('admin/sub-user'); ?>"><i class="fa fa-user"></i><span>System User</span></a>
               </li>
            <?php }else{}
         ?>
         

        
         <li class="reptLi">
            <a href="#"><i class="fa fa-bar-chart"></i><span>Reports</span></a>
         </li>
      </ul>
   <?php }?>
      <!-- End sidebar-menu -->
   </section>
   <!-- End sidebar -->
</aside>
<!-- End main-sidebar -->

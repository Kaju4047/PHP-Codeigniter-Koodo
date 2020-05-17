<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>


<!-- END:: Header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Dashboard</h1>
    </section>
  

    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 no-pad">

            <div class="row">
              <div class="col-md-3 col-xs-6">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3>Total Sports</h3>
                    <p class=""><?php echo !empty($sportDetails)?$sportDetails:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/sport" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                
                </div>
              </div>
 
              <div class="col-md-3 col-xs-6">
                <div class="small-box bg-yellow">
                  <div class="inner">
                    <h3>Total  Players</h3>
                    <p class=""><?php echo !empty($player)?$player:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=1&city=&sport_club=&deleted=No" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3>Total  Pro-players </h3>
                    <p class=""><?php echo !empty($pro_player)?$pro_player:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=1&city=&sport_club=&deleted=No&pro_player=1" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box bg-dark-slate-grey">
                  <div class="inner">
                    <h3>Total  Coaches</h3>
                    <p class=""><?php echo !empty($coach)?$coach:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=2&city=&sport_club=&deleted=No" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box bg-dark-brown">
                  <div class="inner">
                    <h3>Total Tournaments</h3>
                    <p class=""><?php echo !empty($tournaments)?$tournaments:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/tournaments-list" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box bg-khaki">
                  <div class="inner">
                    <h3>Total Sport Dealers </h3>
                    <p class=""><?php echo !empty($dealer)?$dealer:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=3&city=&sport_club=&deleted=No&otherid=24" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box light-violet">
                  <div class="inner">
                    <h3>Total Buy/Sell Products</h3>
                    <p class=""><?php echo !empty($dealer_product)?$dealer_product:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/dealer-products" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box light-red">
                  <div class="inner">
                    <h3>Total Orthopedic  </h3>
                    <p class=""><?php echo !empty($Orthopedic)?$Orthopedic:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=3&city=&sport_club=&deleted=No&otherid=16" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box br-pink">
                  <div class="inner">
                    <h3>Total  Physiotherapists</h3>
                    <p class=""><?php echo !empty($Physo_Therpist)?$Physo_Therpist:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=3&city=&sport_club=&deleted=No&otherid=2" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box dark-cyan">
                  <div class="inner">
                    <h3>Total Dietitians</h3>
                    <p class=""><?php echo !empty($Dietitians)?$Dietitians:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=3&city=&sport_club=&deleted=No&otherid=21" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-md-3 col-xs-6">
                <div class="small-box amber" >
                  <div class="inner">
                    <h3>Total Treatment and Spa</h3>
                    <p class=""><?php echo !empty($Treatment)?$Treatment:'0'?></p>
                  </div>
                 <a href="<?php echo base_url(); ?>admin/filter-user?fromdate=&todate=&type=3&city=&sport_club=&deleted=No&otherid=22" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              
            </div>  <!-- End col-md-12 -->
            <div class="clearfix"></div>
    </section>  <!-- End .content -->
</div>  <!-- End .content-wrapper -->
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->
<script type="text/javascript">
    $(".dashboardLi").addClass("active");
</script>
</body>
</html>

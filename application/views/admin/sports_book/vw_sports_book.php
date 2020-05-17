<!-- START:: Header -->
<?php include("application/views/admin/section/vw_header.php"); ?>
<!-- END:: Header -->
<!-- START:: Header -->
<?php include("application/views/admin/section/vw_sidebar.php"); ?>
<!-- END:: Header -->
<?php

$fld = 'UA_priviliges';
$userid = $this->session->userdata['UID'];
 
$condition = array('UA_pkey' => $userid);
$privilige = $this->Md_database->getData('useradmin', $fld, $condition, '', '');
$privilige = !empty($privilige[0]['UA_priviliges']) ? explode(',', $privilige[0]['UA_priviliges']) : '';
// print_r($privilige1);exit();
?>
<?php
// print_r($privilige);die();
(in_array('sportbook', $privilige) )  ? '' : redirect(base_url() . 'admin/dashboard'); //redirect if session expire
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <section class="content-header">
      <h1> Wall List 
      <div class="pull-right">
            <a href="<?php echo base_url().'admin/report-users'; ?>"><button type="button" class="btn btn-success"><i class=""></i> Report Users</button></a>
      </div> </h1>
    </section>
  
         <section class="content"> 
                  <div class="col-md-4 no-pad loaddata">
                     <div id="my-scrollbar">
                     <div class="box box-primary no-height">
                     <div class="box-body no-height mob-bot">
                     <div class="load_data">
                     <?php foreach ($commentList as $key => $value) {?>
                     <div class=" notifi click_me"  value="<?php echo $value['pk_id'] ?>" att="<?php echo $value['pk_id'] ?>">
                                    
                                                           
                        <div class="pro-pic">
                         <!--   <img src="<?php echo base_url();?>AdminMedia/images/passport.jpg" alt="profile_pic" > -->
                           <?php $imgdata = !empty($value['img']) ? 'uploads/users/' . $value['img'] : 'AdminMedia/images/avatar5.png'; ?>
                           <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" alt="profile_pic">              
                        </div>

                        <div class="noti-ttl" >                  
                           <h4><?php echo(ucwords($value['name'])); ?></h4>
                           <p><?php echo($value['date']); ?>,<?php echo(ucwords($value['city_name'])); ?>,<?php echo(' '.ucwords($value['state_name'])); ?>.</p> 
                        </div> 
                     <div>
                        <p><?php echo(ucfirst($value['text'])); ?></p>
                     </div>
                     <?php if($value['image']){?>
                     <div class="cnt-img">
                         <?php $imgdata = !empty($value['image']) ? 'uploads/sportbook/post/' . $value['image'] : 'AdminMedia/images/default.png'; ?>
                           <img src="<?php echo base_url(). $imgdata;?>" class="img-upload web-img" alt="profile_pic"> 
                     </div>
                     <?php }?>

                     <div class="lk-cnt">
                        <p style="float: left;"><i class="fa fa-thumbs-up"></i><?php echo('  ' . $value['likeCount']); ?></p>
                        <p style="float: right;"><i class="fa fa-comment"></i> <?php echo('  '.$value['commentCount']); ?> Comments</p>
                     </div>
                     <div class="clearfix"></div>                    
                  </div>
              
                                
                   <?php }?>
                   </div>     
               <button type="button" onclick="loadmore()" value="loadmore" >Load More</button>
               <input type="hidden" name="limit" id="limit" value="20"/>
               <input type="hidden" name="offset" id="offset" value="0"/>
                   
               </div>
            </div>
         </div>
      </div>
         <div class="col-md-8 no-pad-right no-mob-pad">
            <div class="box box-primary yes-height">
               <div class="box-body no-height mob-bot">
                  <div id="div1">
                  <div class="notifi">                     
                        <div class="pro-pic">
                
                           <?php $imgdata = !empty($viewUserProfile) ? 'uploads/users/' . $viewUserProfile: 'AdminMedia/images/avatar5.png'; ?>
                                                
                           <img src="<?php echo base_url(). $imgdata;?>" id="profile_pic" width="70%" style="border-radius: 100%">
         
                        </div>
                        <div class="noti-ttl"> 
                           <h4 id="viewname"><?php echo $viewName;?></h4>                 
                           <div class="d-inline">
                              <p id="date"><?php echo $viewPostDate;?></p>,
                              <p id="city"><?php echo $viewCity;?></p>,
                              <p id="state"><?php echo $viewState;?>.</p>
                           </div>  
                        </div>   
                     <div>
                        <p id="text"><?php echo $viewPostText;?></p>
                     </div>
                     <div class="cnt-img  comment-img">                      
                     <?php if(!empty($viewPostImg)){ ?>  
                        <?php $imgdata = !empty($viewPostImg) ? 'uploads/sportbook/post/' . $viewPostImg: ''; ?>
                      
                        <img src="<?php echo base_url(). $imgdata;?>" id="post_pic" alt="sport book">
                                      
                   <?php }?>
                     </div>
                     <div class="lk-cnt">
                        <p style="float: left;" ><i class="fa fa-thumbs-up"></i><span id="like_count"><?php echo ' '. $viewlikeCount;?></span></p>
                        <p style="float: right;" ><i class="fa fa-comment"></i><span id="comment_count"><?php echo ' '. $viewcommentCount;?></span> Comments</p>
                     </div>
                     <div class="clearfix"></div>
                  </div>
                  <div class="comments">
                    <?php if (!empty($viewCommentsList)) {
                      foreach ($viewCommentsList as $key => $value) {

                    ?>
                  <div class="commnt" >
                    <div class="pro-pic">

                       <?php $imgdata = !empty($value['img']) ? 'uploads/users/' . $value['img']: 'AdminMedia/images/avatar5.png'; ?>

                        <img src="<?php echo base_url().$imgdata;?>" id="comment_img" width="50%" style="border-radius: 50%;">
   
                    </div>
                    <div class="noti-ttl">
                        <h4 id="comment_name"><?php echo !empty($value['name'])?$value['name']:''?></h4>
                        <p id="comment_date"><?php echo !empty($value['comment_date'])?$value['comment_date']:''?></p>    
                        <p id="comment"><?php echo !empty($value['comment'])?$value['comment']:''?></p>    
                    </div>  
                  </div> 
                   <?php }} ?>
                  </div>               
               </div>                             
           </div>
         </div>
      </div>
   
      <div class="clearfix"></div>
   </section>
   <!-- End .content -->
</div>
<!-- End .content-wrapper --> 
<!-- START:: Footer -->
<?php include("application/views/admin/section/vw_footer.php"); ?>
<!-- END:: Footer -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/smooth-scrollbar/8.3.0/smooth-scrollbar.js"></script>
<script>
   $(".sportsbookLi").addClass("active");
   $("#example").DataTable();  
</script>
<script>
   var Scrollbar = window.Scrollbar;
   Scrollbar.init(document.querySelector('#my-scrollbar'));
</script>
<script>
 
 function loadmore(){
  // alert($('#offset').val());
  // alert($('#limit').val());
  var base_url = "<?php echo base_url(); ?>";
  var postimage = "<?php echo base_url(); ?>uploads/products/img/";
  var emptyimage = "<?php echo base_url(); ?>AdminMedia/images/default.png";
  // alert(emptyimage);
    $.ajax({
         type: "get",
         url: base_url+"admin/sports-book/Cn_sports_book/loadmore",
        data:{
          offset :$('#offset').val(),
          limit :$('#limit').val()
        },
        dataType: 'json', 
        success :function(data){
          // alert(JSON.stringify(data.commentList));
          var html='';
               if(data!=""){
                    $.each( data.commentList, function( key, value ){
                        html += '<div class=" notifi click_me"  value="'+value.pk_id+'" att="'+value.pk_id+'">'
                        html +='<div class="pro-pic">'  
                             if(value.img==null){
                            var imgdata =  base_url+'AdminMedia/images/avatar5.png';
                
                          }else{
                            var imgdata =  base_url+ 'uploads/users/'+value.img;
                          }
                        html += '<img src="'+imgdata+ '" class="img-upload web-img" alt="profile_pic">'                                             
                        html += '</div>'
                        html +='<div class="noti-ttl" > '               
                        html += '<h4>'+value.name+'</h4>'
                        html +='<p>'+value.date+','+value.city_name+','+value.state_name+'.</p>' 
                        html +='</div>'
                        html +='<div>'
                        html +=' <p>'+value.text+'</p>'
                        html +='</div>'
                        html += ' <div class="cnt-img">'
                          if(value.image==""){
                   
                          }else{
                            var imagedata =  base_url+ 'uploads/sportbook/post/'+value.image;
                            html += '<img src="'+imagedata+ '" class="img-upload web-img" alt="profile_pic">'
                          }
                        html += '</div>'                  
                        html +='<div class="lk-cnt">'
                        html +=' <p style="float: left;"><i class="fa fa-thumbs-up"></i>'+value.likeCount+'</p>'
                        html+='<p style="float: right;"><i class="fa fa-comment"></i> '+value.commentCount+' Comments</p>'
                        html +='</div>'
                        html +='<div class="clearfix"></div> '                   
                        html +='</div>'
                    });
                }
                $('.load_data').html(html); 

                $(document).on('click', '.click_me', function() {
                    $(".comments").html('');
                    var post_id= $(this).attr('att');
                    var base_url = "<?php echo base_url(); ?>";
                    $.ajax({
                        data: ({ post_id: post_id }),
                        dataType: 'json', 
                        type: "post",
                        url:  base_url + "admin/view-comment",
                   
                        success: function(data){
                            $("#viewname").html(data.name);
                            $("#city").html(data.city_name);
                            $("#state").html(data.state_name);
                            $("#text").html(data.text);
                            $("#date").html(data.date);
                            if (data.img==null){          
                                $("#profile_pic").attr("src",'<?php echo base_url()?>AdminMedia/images/avatar5.png');
                            }else{
                                $("#profile_pic").attr("src",'<?php echo base_url()?>uploads/users/'+data.img);
                            }
                           
                            if (data.image == '') {
                                $(".comment-img").html('');
                            }else{
                              var imaggg ="<?php echo base_url()?>uploads/sportbook/post/"+data.image;
                              var image_data = '<img src="'+imaggg+'" id="post_pic" alt="sport book">';
                                $(".comment-img").html(image_data);
                                // $("#post_pic").attr("src",'<?php echo base_url()?>uploads/sportbook/post/'+data.image);
                            }
                            $("#text").html(data.text);
                            $("#comment_count").html(data.commentCount);
                            $("#like_count").html(data.likeCount);
                            $(".comments").html('');
                            $.each(data.comments, function(index,value){
                                if(data.comments==''){
                                    $(".comments").html('');
                                }
                                else{
                                    if (value.img==null){
                                        var propic =  base_url+'AdminMedia/images/avatar5.png';             
                                    }else{
                                        var propic =  base_url+ 'uploads/users/'+value.img;
                                    }
                                    $(".comments").append(' <div class="commnt" ><div class="pro-pic"><img src="'+propic+ '" id="comment_img" width="50%" style="border-radius: 50%;"></div><div class="noti-ttl"><h4 id="comment_name">'+value.name+'</h4><p id="comment">'+value.comment_date+'</p><p id="comment">'+value.comment+'</p></div></div>');
                                }                
                            });                
                        }             
                    });
                });
            $('#offset').val(data.offset)
            $('#limit').val(data.limit)          
        }
    })   
}
  $(".click_me").click(function() {
    // alert("hii");
        $(".comments").html('');
        var post_id= $(this).attr('att');
        var base_url = "<?php echo base_url(); ?>";
        $.ajax({
            data: ({ post_id: post_id }),
            dataType: 'json', 
            type: "post",
            url:  base_url + "admin/view-comment",
       
            success: function(data){
                $("#viewname").html(data.name);
                $("#city").html(data.city_name);
                $("#state").html(data.state_name);
                $("#text").html(data.text);
                $("#date").html(data.date);
                if (data.img==null){          
                    $("#profile_pic").attr("src",'<?php echo base_url()?>AdminMedia/images/avatar5.png');
                }else{
                    $("#profile_pic").attr("src",'<?php echo base_url()?>uploads/users/'+data.img);
                }
               
                if (data.image == '') {
                    $(".comment-img").html('');
                }else{
                  var imaggg ="<?php echo base_url()?>uploads/sportbook/post/"+data.image;
                  var image_data = '<img src="'+imaggg+'" id="post_pic" alt="sport book">';
                    $(".comment-img").html(image_data);
                    // $("#post_pic").attr("src",'<?php echo base_url()?>uploads/sportbook/post/'+data.image);
                }
                $("#text").html(data.text);
                $("#comment_count").html(data.commentCount);
                $("#like_count").html(data.likeCount);
                $(".comments").html('');
                $.each(data.comments, function(index,value){
                    if(data.comments==''){
                        $(".comments").html('');
                    }
                    else{
                        if (value.img==null){
                            var propic =  base_url+'AdminMedia/images/avatar5.png';             
                        }else{
                            var propic =  base_url+ 'uploads/users/'+value.img;
                        }
                        $(".comments").append(' <div class="commnt" ><div class="pro-pic"><img src="'+propic+ '" id="comment_img" width="50%" style="border-radius: 50%;"></div><div class="noti-ttl"><h4 id="comment_name">'+value.name+'</h4><p id="comment">'+value.comment_date+'</p><p id="comment">'+value.comment+'</p></div></div>');
                    }                
                });                
            }             
        });
    });


</script>
</body>
</html>
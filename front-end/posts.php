<?php
$skip_morris = true;

include_once('../cores/definition.php');

include_once('../gammu/gammu-cores.php');
include_once('../cores/session.php');

require_once('../cores/db.php'); 
$post_id = get_var('id');
$c = fetch_query("select count(*) as jml from frontend_posts where id = '$post_id'");
$count = $c[0]['jml'];
unset($c);

if (empty($post_id) || empty($count) || ($count==0))
{
    force_404('../404.php');
}

$posts = fetch_query(
    "select p.id, p.pub_date, p.title, p.excerpt, p.content,
    (select count(c.id) from frontend_comments c where 
	c.comment_to_table = 'frontend_posts' and c.comment_to_id = p.id) as comment_count
    from frontend_posts p where p.id = '$post_id'");
$post = $posts[0];
unset($posts);
$page_name = $post['title'];

include "../pages/_head.php";


?>


<body>

    <div class="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" >
            <?php include '../pages/_logoarea.php'; ?>
            <ul class="nav navbar-top-links navbar-right" >                
                <li class="dropdown">                                                            
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <?php if (!user_logged_in()) { ?>
                        <li><a href="../pages/login.php"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
                        <?php } else { ?>
                        <li><a href="../pages/"><i class="fa fa-user fa-fw"></i> Dashboard</a></li>                        
                        <li class="divider"></li>
                        <li><a href="../cores/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                        <?php }?>
                    </ul>             
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
            
        </nav>
        <div class="row" >
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default" style="margin-top: 50px;">
                    <div class="panel-heading">
                        <strong><?php echo $page_name; ?></strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <i class="fa fa-calendar" title="Tanggal Terbit"></i> <?php echo date("m/d/y g:i A", strtotime($post['pub_date'])); ?>.<br />
                                    <i class="fa fa-comments" title="Jumlah Komentar"></i> 
                                    <a href="#comment-form" title="Tulis Komentar" alt="Tulis Komentar">
                                        <?php echo $post['comment_count']; ?> comment(s).<br />
                                    </a>
                                </div>
                            </div>   
                            <div class="col-md-8">
                               <?php echo $post['content']; ?>
                            </div>
                        </div>                      
                    </div>                        
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">                                
                                <h5><?php echo $post['comment_count']; ?> komentar sejauh ini.</h5>
                                <hr />
                            </div>
                        </div>
                        <?php 
                        $comments = fetch_query(
                            "select id, pub_date, nama, email, url, content 
                            from frontend_comments where comment_to_table = 'frontend_posts'
                            and comment_to_id = '$post_id' order by pub_date asc, id asc");
                        foreach($comments as $no => $comment)
                        {
                        ?>
                        <div class="row">
                            <div class="col-md-12">                                                                    
                                <label>
                                    <i class="fa fa-user"></i>
                                    <?php if (!empty($comment['url']))
                                    {
                                        echo '<a href="'.$comment['url'].'" title="'.$comment['nama'].'" target="_blank">';
                                        echo $comment['nama']; 
                                        echo '</a>';
                                    }
                                    else 
                                    {
                                        echo $comment['nama']; 
                                    }
                                    ?> 
                                </label>
                                on
                                <label>
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo date("m/d/y g:i A", strtotime($comment['pub_date'])); ?>
                                </label>
                                <div class="row">
                                    <div class="col-md-1"> 
                                        <i class="fa fa-quote-left fa-2x"></i>
                                    </div>
                                    <div class="col-md-11"> 
                                        <?php echo $comment['content']; ?>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <?php
                        }
                        unset($comments); ?>
                        <div class="row">
                            <div class="col-lg-12" id="comment-form">   
                                <div class="row" style="padding-bottom: 5px;">
                                    <div class="col-sm-6"><label  for="comment-nama" class=""> <span class="text-danger">Nama:</span><br /></label>
                                        <input type="text" id="comment-nama" class="input-sm form-control" />                                        
                                    </div>                                    
                                </div>                                
                                <div class="row" style="padding-bottom: 5px;">
                                    <div class="col-sm-6"><label  for="comment-email" class=""> <span class="text-danger">E-mail:</span><br /></label>
                                        <input type="text" id="comment-email" class="input-sm form-control" />                                        
                                    </div>                                        
                                </div>   
                                <div class="row" style="padding-bottom: 5px;">
                                    <div class="col-sm-6"><label  for="comment-url" class=""> <span class="">Website/URL:</span><br /></label>
                                        <input type="text" id="comment-url" class="input-sm form-control" /> 
                                    </div>                                        
                                </div>
                                <div class="row" style="padding-bottom: 5px;">
                                    <div class="col-sm-8"><label  for="comment-komentar" class=""> <span class="text-danger">Komentar:</span><br /></label>
                                        <textarea id="comment-komentar" class="input-sm form-control"></textarea> 
                                    </div>                                        
                                </div>
                                <hr />
                                <div class="row" style="padding-bottom: 6px;">
                                    <div class="col-sm-12">
                                        <a href="#" id="btn-save-comment" class="btn btn-sm btn-success">Kirim</a>
                                        <a href="#" id="btn-clear-comment" class="btn btn-sm btn-warning">Kosongkan</a>
                                        <span class="pull-right" style="display: none;" id="ajax-comment"><img src="../pages/img/ajax-loaders/ajax-loader-fan.gif" /></span>
                                    </div>                                
                                </div> 
                            </div>                                                        
                        </div>                    
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {            
       	    $("#btn-save-comment").click(function(e){
       	        e.preventDefault();
            
                var nama = $('#comment-nama').val();
                var email = $('#comment-email').val();
                var url = $('#comment-url').val();
                var komentar = $('#comment-komentar').val();
                var er = '';
                if (nama == '') { er += 'Nama harus diisi.<br>'; }
                if (email == '') { er += 'Email harus diisi.<br>'; }
                if (komentar == '') { er += 'Komentar harus diisi.<br>'; }
                if (er!='') {
                    msgBox('Error', er);
                    return false;
                }
                $('#ajax-comment').show('fast');
                // alert('<?php echo $post_id; ?>');
                
                $.post('ajax-comment-post.php',
    			{
    				nama: nama,
    				email: email,
                    url: url,
                    komentar: komentar,
                    postid: '<?php echo $post_id; ?>',
                    table: '<?php echo 'frontend_posts'; ?>',
                    r: Math.random()
    			},
    			function(data)
    			{    				
                    // var result = data.substr(0,2);					
                    msgBox('Komentar',data.substr(2));
                    if (data.substr(0,2)=='OK') {
                        location.reload();
                    }                   
    			});
                
                return false;
       	    });    
            $("#btn-clear-comment").click(function(e){
       	        e.preventDefault();
                $('#comment-nama').val('').focus();
                $('#comment-email').val('');
                $('#comment-url').val('');
                $('#comment-komentar').val('');
                return false;
       	    });
        });
    </script>    
<?php unset($post); ?>
<?php include '../pages/_footscripts.php'; ?>
<?php include '../pages/_foot.php'; ?>

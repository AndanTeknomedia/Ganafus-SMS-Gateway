<?php
function show_error_box_with_button($teks, $button='OK', $link='#')
{
	echo '<div class="box-content alerts">
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<i class="icon-warning-sign"></i> 
			'.$teks.'                                
		</div>
		<p class="pull-left">
			<a class="btn btn-success" href="'.$link.'" title="'.$button.'">
				<i class="icon-asterisk icon-white"></i> '.$button.'
			</a>
		</p>
	</div>';	
}

function show_error_box_without_button($teks)
{
	echo '<div class="box-content alerts">
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<i class="icon-ban-circle"></i> 
			'.$teks.'                                
		</div>
	</div>';	
}

function show_modal_ui($element_id,$title, $content)
{
    echo '
        <div class="modal hide fade" id="'.$element_id.'" style="height:auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>'.$title.'</h3>
            </div>
            <div class="modal-body" style="height:50x;">
                <p style="text-align:center;">
                	<img src="img/ajax-loaders/ajax-loader-7.gif" title="img/ajax-loaders/ajax-loader-7.gif">
				</p>
            </div>
            <!--
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
                <a href="#" class="btn btn-primary">Save changes</a>
            </div>
            -->
        </div>';
        
        /*
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        */
}

function show_fb_sdk()
{
	echo "
    <div id=\"fb-root\"></div>
	<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = \"//connect.facebook.net/id_ID/all.js#xfbml=1&appId=325396137559237\";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>";
}

function show_fb_comment_box($full_uri)
{
echo "
    <div 	class=\"fb-comments\" 
    		data-href=\"$full_uri\" 
            
            data-numposts=\"10\" 
            data-colorscheme=\"light\"
            data-order-by=\"time\"></div>";
}

/*

if (icon == "icon-facebook")
    {
        window.open("http://www.facebook.com/sharer.php?s=100&p[title]='.$title.'&p[url]='.abs_uri($page.'?'.$permalink).'&p[images][0]='.abs_uri($thumb_uri).'", "sharer", "toolbar=0,status=0,width=548,height=325");
        return false;
    }
    else
    if (icon == "icon-twitter")
    {
        window.open("http://twitter.com/share?text='.substr($title,0,50).'&url='.abs_uri($page.'?'.$permalink).'", "sharer", "toolbar=0,status=0,width=548,height=325");
        return false;
    }
});

*/

?>
<?php
global $homepage;
$box_headings=array("Full Width slider", "Works everywhere", "Multiple skins");
$box_contents=array("Aenean commodo, dui porta consequat, ipsum tortor amet.", "Aenean commodo, dui porta consequat, ipsum tortor amet.", "Aenean commodo, dui porta consequat, ipsum tortor amet.");
$box_icons=array("", "", "");

for($ctr=1; $ctr<=3;$ctr++){
	$i=$ctr-1;
	if(get_option(RM_SHORT.'box_heading'.$ctr) and get_option(RM_SHORT.'box_heading'.$ctr)!="")
		$box_headings[$i]=stripslashes(get_option(RM_SHORT.'box_heading'.$ctr));
		
	if(get_option(RM_SHORT.'box_content'.$ctr) and get_option(RM_SHORT.'box_content'.$ctr)!="")
		$box_contents[$i]=stripslashes(get_option(RM_SHORT.'box_content'.$ctr));	
		
	if(get_option(RM_SHORT.'box_icon'.$ctr) and get_option(RM_SHORT.'box_icon'.$ctr)!="")
		$box_icons[$i]=stripslashes(get_option(RM_SHORT.'box_icon'.$ctr));

}

?>
		<div id="top_box<?php if($homepage) echo'_index';?>">
		
			<span class="top_box_top"></span>
			<!-- /.top_box_top - adds the top Background -->
			
			<div class="content">
			
				<div class="top_box_block">
				
					<h4><?php echo $box_headings[0];?></h4>
					<p><?php echo $box_contents[0];?></p>
					<img src="<?php echo build_image($box_icons[0], 71, 70, 1);?>" class='icon' width='71' height='70' alt='' />
				
				</div>
				<!-- /.content .top_box_block -->
			
				<div class="top_box_block">
				
					<h4><?php echo $box_headings[1];?></h4>
					<p><?php echo $box_contents[1];?></p>
					<img src="<?php echo build_image($box_icons[1], 71, 70, 1);?>" class='icon' width='71' height='70' alt='' />
				
				</div>
				<!-- /.content .top_box_block -->
			
				<div class="top_box_block no_margin_r">
				
					<h4><?php echo $box_headings[2];?></h4>
					<p><?php echo $box_contents[2];?></p>
					<img src="<?php echo build_image($box_icons[2], 71, 70, 1);?>" class='icon' width='71' height='70' alt='' />
				
				</div>
				<!-- /.content .top_box_block -->
			
			</div>
			<!-- /#top_box .content -->
		
		</div>
		<!-- /#top_box -->
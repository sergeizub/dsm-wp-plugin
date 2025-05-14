<?php
namespace DanceStudioManager;

$last_check_videos = "";
$videos = App::GetClient()->GetController('videos')->GetVideos($_REQUEST['filter']);

foreach($videos['videos'] as $video) {
    $last_check_videos .= (!empty($last_check_videos) ? "," : "").$video['ID'];
}
if (!empty($last_check_videos))
    setcookie("last_check_videos", $last_check_videos, strtotime(" +1 year"), "/");
?>
<script>
    jQuery(function(){
        jQuery('.js_videos_count').hide();
    });
</script>
<div class="page-header">
	<h2><?php echo DSM_OC_VIDEOS_SECTION_TITLE; ?></h2>
</div>
<form method="post" id="videos-filter">
	<input type="hidden" name="boot_tab" value="tab-videos" />
	<input type="hidden" name="action" value="dsmclient"/>
	<div class="row form-group row">
		<div class="col-md-6">
			<select name="filter[category_id]" class="form-control">
				<?php foreach ($videos['video_categories'] as $k_cat => $category): ?>
            	<option value="<?php echo $k_cat; ?>" <?php if ($k_cat == "0" && empty($_POST['filter']['category_id']) || $_POST['filter']['category_id'] == $k_cat) echo "selected=selected";?>><?php echo $category; ?></option>
            	<?php endforeach; ?>
			</select>
		</div>
	</div>
</form>
<?php foreach ($videos['videos'] as $video): ?>
<div class="card mb-2" data-video-id="<?php echo $video['ID']; ?>">
	<div class="card-header">
    <?php echo $video['TITLE']; ?> <?php if ($video['LIVE'] == "1") : ?><div class="label label-danger">live</div><?php endif; ?>
		<div class="float-right"><?php echo $video['DATE_ADDED']; ?></div>
	</div>
	<div class="card-body">
		<div class="embed-responsive embed-responsive-16by9">
	  		<iframe class="embed-responsive-item" src="<?php echo $video['LINK']; ?>?modestbranding=1&controls=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
		<br>
		<p><?php echo $video['DESCRIPTION']; ?></p>
	</div>
</div>
<br/>
<?php endforeach; ?>
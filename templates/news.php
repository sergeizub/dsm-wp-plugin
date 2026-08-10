<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

$last_check_announcements = "";
$news = App::GetClient()->GetController('news')->GetNews();

foreach($news['data'] as $new) {
    $last_check_announcements .= (!empty($last_check_announcements) ? "," : "").$new['ID'];
}
if (!empty($last_check_announcements))
    setcookie("last_check_announcements", $last_check_announcements, strtotime(" +1 year"), "/"); 
?>
<style>
    .description-wrapper {
        position: relative;
        max-height: 180px;
        overflow: hidden;
        margin-bottom: 50px;
    }
    .description-wrapper::after {
        display: block;
        content: ' ';
        background-image: linear-gradient(0deg, white, transparent);
        height: 100px;
        width: 100%;
        position: absolute;
        top: 100px;
    }
</style>
<script>
    jQuery(function(){
        jQuery('.js_news_count').hide();
    });
</script>
<div id="tab-news" class="tab-pane">
    <h2 class="page-header"><?php echo esc_html(DSM_OC_ANNOUNCEMENTS_SECTION_TITLE); ?></h2>
<?php if (!is_array($news) || empty($news['data'])): ?>
    <div class="alert alert-warning">There are no announcements at the moment.</div>
<?php endif; ?>
<?php foreach ($news['data'] as $item): ?>
    <div class="card" data-news-id="<?php echo esc_attr($item['ID']); ?>" style="margin-top: 1rem !important;">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3" data-toggle="modal" data-target="#announcemebt-<?php echo esc_attr($item['ID']); ?>" style="cursor: pointer;">
                    <img src="<?php echo esc_attr($item['IMAGE']); ?>" class="img-fluid" alt="<?php echo esc_attr($item['TITLE']); ?>">
                </div>
                <div class="col-lg-9" style="position: relative;">
                    <h3 class="pointer" data-toggle="modal" data-target="#announcemebt-<?php echo esc_attr($item['ID']); ?>" style="cursor: pointer;"><?php echo esc_attr($item['TITLE']); ?></h3>
                    <div class="description-wrapper" style="">
						<?php echo wp_kses_post($item['DESCRIPTION']); ?>
                    </div>
                    <button 
                        type="button" 
                        class="btn btn-primary" 
                        data-toggle="modal" 
                        data-target="#announcemebt-<?php echo esc_attr($item['ID']); ?>"
                        style="position: absolute; bottom: 0; right: 10px;">
                        Read more
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="announcemebt-<?php echo esc_attr($item['ID']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo esc_html($item['TITLE']); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span><?php echo wp_kses_post($item['DESCRIPTION']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php
namespace DanceStudioManager;

$news = App::GetClient()->GetController('news')->GetNews();
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
<div id="tab-news" class="tab-pane">
    <h2 class="page-header"><?php echo DSM_OC_ANNOUNCEMENTS_SECTION_TITLE; ?></h2>
<?php if (!is_array($news) || empty($news['data'])): ?>
    <div class="alert alert-warning">There are no announcements at the moment.</div>
<?php endif; ?>
<?php foreach ($news['data'] as $item): ?>
    <div class="card" data-news-id="<?php echo $item['ID']; ?>" style="margin-top: 1rem !important;">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3" data-toggle="modal" data-target="#announcemebt-<?php echo $item['ID']; ?>" style="cursor: pointer;">
                    <img src="<?php echo $item['IMAGE']; ?>" class="img-fluid" alt="<?php echo $item['TITLE']; ?>">
                </div>
                <div class="col-lg-9" style="position: relative;">
                    <h3 class="pointer" data-toggle="modal" data-target="#announcemebt-<?php echo $item['ID']; ?>" style="cursor: pointer;"><?php echo $item['TITLE']; ?></h3>
                    <div class="description-wrapper" style="">
						<?php echo $item['DESCRIPTION']; ?>
                    </div>
                    <button 
                        type="button" 
                        class="btn btn-primary" 
                        data-toggle="modal" 
                        data-target="#announcemebt-<?php echo $item['ID']; ?>"
                        style="position: absolute; bottom: 0; right: 10px;">
                        Read more
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="announcemebt-<?php echo $item['ID']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $item['TITLE']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span><?php echo $item['DESCRIPTION']; ?></span>
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

<?php 
namespace DanceStudioManager;

class VideosController extends BaseController 
{
    public function __construct()
    {
      parent::__construct();
    }

    public function GetVideos($filter) 
    {
      //Prepare filter for Api - ignore array values
		  foreach ($filter as $k_filter => $v_filter) {
			  if (!is_array($v_filter) && !empty($v_filter))
				  $data[$k_filter] = $v_filter;
		  }
      return  json_decode(json_encode(parent::GetList('videos/?'.http_build_query($data))),true);
    } 
}
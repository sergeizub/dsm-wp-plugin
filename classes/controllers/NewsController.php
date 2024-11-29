<?php 
namespace DanceStudioManager;

class NewsController extends BaseController 
{
    public function __construct()
    {
      parent::__construct();
    }

    public function GetNews() 
    {
        return  json_decode(json_encode(parent::GetList("news")),true);
    } 

    public function GetCountNewAnnouncements($params = "") 
    {
        return  json_decode(json_encode(parent::GetList("news/count_new".$params)),true);
    } 
}
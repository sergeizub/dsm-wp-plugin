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
}
<?php


/**
* @version 1.0.0 $ 18.01.2014
* @package brilliant_instajoom
* @copyright (C) 2014 Yuriy Galin
* @license GNU General Public License version 3 or later
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


 
defined('_JEXEC') or die;
 
class modBrilliantInstaJoom
{
	public static function getInstaPhotos($CLIENT_ID, $USER_NAME)
	{    
	  // Инициализируем сеанс CURL                  
    $ch = curl_init('https://api.instagram.com/v1/users/search?q='.$USER_NAME.'&client_id='.$CLIENT_ID);
    // Указываем, что результат должен записаться в переменную
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Выполняем CURL запрос
    $data = curl_exec($ch);
    $content = json_decode( $data );
    $gen_id = $content->data[0]->id;
       
    // Инициализируем сеанс CURL
    $ch = curl_init('https://api.instagram.com/v1/users/'.$gen_id.'/media/recent/?client_id='.$CLIENT_ID);
    // Указываем, что результат должен записаться в переменную
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Выполняем CURL запрос
    $data = curl_exec($ch);
    $content = json_decode( $data );
    $ph_count = count($content->data);
    $rows = array();
    for ($i=0; $i<$ph_count; $i++){
      $rows[$i]["link"] = $content->data[$i]->link;
      $rows[$i]["standard_resolution"] = $content->data[$i]->images->standard_resolution->url;
      $rows[$i]["low_resolution"]= $content->data[$i]->images->low_resolution->url;
      $rows[$i]["thumbnail"]= $content->data[$i]->images->thumbnail->url;
    }
		//возвращаем результат
		return $rows;
	}
	
	
 
}
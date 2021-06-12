<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 11/06/21
 * Time: 20:36
 */

namespace App\Utils;


use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeFromPage extends CategoryTreeAbstract
{
    public function getCategoryList(array $categories_array)
    {
        $this->categoryList .= '<ul>';
        foreach ($categories_array as $value){
            $catName = $value['name'];
            $url = $this->urlgenerator->generate('video_list', [
                'categoryname' => $catName,
                'id' => $value['id']
            ]);
            $this->categoryList .= '<li>' . '<a href="' . $url. '">' .$catName. '</a>';

            if(!empty($value['children'])){
                $this->getCategoryList($value['children']);
            }
            $this->categoryList .= '</li>';
        }
        $this->categoryList .= '</ul>';
        return $this->categoryList;
    }

//<li><a href="#">Funny</a></li>
//<ul>
//<li><a href="#">Surprising</a></li>
//<li><a href="#">Exciting</a></li>
//<ul>
//<li><a href="#">Strange</a></li>
//<li><a href="#">Relaxing</a></li>
//</ul>
//</ul>
}
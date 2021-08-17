<?php

namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;
    protected $mockedCategoryTreeAdminList;
    protected $mockedCategoryTreeAdminOptionList;

    protected function setUp():void
    {
        $kernel = self::bootKernel();
        $urlgenerator = $kernel->getContainer()->get('router');
        $tested_classes = [
            'CategoryTreeAdminList',
            'CategoryTreeAdminOptionList',
            'CategoryTreeFrontPage'
        ];
        foreach($tested_classes as $class)
        {
            $name = 'mocked'.$class;
            $this->$name = $this->getMockBuilder('App\Utils\\'.$class)
                ->disableOriginalConstructor()
                ->setMethods() // if no, all methods return null unless mocked
                ->getMock();
            $this->$name->urlgenerator = $urlgenerator;
        }

    }

    /**
     * @dataProvider dataForCategoryTreeFrontPage
     */
    public function testCategoryTreeFrontPage($string, $array, $id)
    {
        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension;
        $main_parent_id = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($main_parent_id);
        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }


    public function dataForCategoryTreeFrontPage()
    {
        yield [
            '<ul><li><a href="/video-list/category/cameras,5">cameras</a></li><li><a href="/video-list/category/computers,6">computers</a><ul><li><a href="/video-list/category/laptops,8">laptops</a><ul><li><a href="/video-list/category/apple,10">apple</a></li><li><a href="/video-list/category/asus,11">asus</a></li><li><a href="/video-list/category/dell,12">dell</a></li><li><a href="/video-list/category/lenovo,13">lenovo</a></li><li><a href="/video-list/category/hp,14">hp</a></li></ul></li><li><a href="/video-list/category/desktops,9">desktops</a></li></ul></li><li><a href="/video-list/category/cell-phones,7">cell-phones</a></li></ul>',
            [
                ['name'=>'Electronics','id'=>1, 'parent_id'=>null],
                ['name'=>'Computers','id'=>6, 'parent_id'=>1],
                ['name'=>'Laptops','id'=>8, 'parent_id'=>6],
                ['name'=>'HP','id'=>14, 'parent_id'=>8]
            ],
            1
        ];


    }


}

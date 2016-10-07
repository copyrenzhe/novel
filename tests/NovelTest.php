<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NovelTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testVisitavailable()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
    }

    public function testHomePage()
    {
        $this->visit('/')
            ->see('书虫网');
    }

    public function test404Page()
    {
        $response = $this->call('GET', '/abcdefg');
        $this->assertEquals(404, $response->status());
    }

    //test category
    public function testXuanhuan()
    {
        $this->visit('/xuanhuan')
            ->see('玄幻小说');
    }

    public function testDushi()
    {
        $this->visit('/dushi')
            ->see('都市小说');
    }

    public function testLishi()
    {
        $this->visit('/lishi')
            ->see('历史小说');
    }

    public function testXiuzhen()
    {
        $this->visit('/xiuzhen')
            ->see('修真小说');
    }

    public function testWangyou()
    {
        $this->visit('/wangyou')
            ->see('网游小说');
    }

    public function testKehuan()
    {
        $this->visit('/kehuan')
            ->see('科幻小说');
    }

    public function testOther()
    {
        $this->visit('/other')
            ->see('其他');
    }

    public function testNewRelease()
    {
        $this->visit('/new-releases')
            ->see('最新发布');
    }

    public function testTopNovels()
    {
        $this->visit('/top-novels')
            ->see('排行榜单');
    }

    public function testAuthors()
    {
        $this->visit('/authors')
            ->see('作者大神');
    }

    public function testOverNovels()
    {
        $this->visit('/over-novels')
            ->see('完结小说');
    }
}

<?php 
use PHPUnit\Framework\TestCase;

/**
*  https://console.bce.baidu.com/ai/?fromai=1#/ai/ocr/app/list
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author yourname
*/
class BaidAIPTest extends TestCase
{
    // 你的 APPID AK SK
    public $APP_ID = '你的 App ID';
    public $API_KEY = '你的 Api Key';
    public $SECRET_KEY = '你的 Secret Key';

    public $client;

    protected function setUp()
    {
        $ini = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'baidu.ini');
        foreach(['APP_ID', 'API_KEY', 'SECRET_KEY'] as $key) {
            if (empty($ini[$key])) {
                throw new \Exception(sprintf("key : %s can not be empty.", $key));
            }
            $this->{$key} = $ini[$key];
        }
        $c = new \AipOcr($this->APP_ID, $this->API_KEY, $this->SECRET_KEY);
        $this->client = $c;
    }

  public function testLocalImageOCR()
  {
	    $image = file_get_contents(__DIR__ . '/img/example.png');
        $rs = $this->client->basicGeneral($image);
        $this->assertArrayHasKey('words_result_num', $rs);
        $this->assertEquals(29, $rs['words_result_num']);
        $this->assertEquals('接口能力', $rs['words_result'][0]['words']);
  }
  
  public function testWebImageOCR()
  {

    $image = file_get_contents(__DIR__ . '/img/art.jpg');
    $rs = $this->client->webImage($image);
    $this->assertArrayHasKey('words_result_num', $rs);
    $this->assertEquals(7, $rs['words_result_num']);
    $this->assertEquals('徐克导演作品', $rs['words_result'][0]['words']);
  }

    
  public function testIDCardImageOCR()
  {

    $image = file_get_contents(__DIR__ . '/img/gtl.jpg');
    $idCardSide = "front";

    $options = array();
    $options["detect_direction"] = "true";
    $options["detect_risk"] = "false";

    $rs = $this->client->idcard($image, $idCardSide, $options);
    // var_dump($rs);
    $this->assertArrayHasKey('image_status', $rs);
    $this->assertEquals('normal', $rs['image_status']);
    $this->assertEquals('500225199002260015', $rs['words_result']['公民身份号码']['words']);
  }
}

## 获得一个更酷的github昵称(顶级命名空间)

<br><br>

```php
<?php
namespace app\index\controller;

use nms\naming\curl\method\Head;
use nms\naming\curl\CurlInterface;
use nms\naming\curl\Pather;
use nms\naming\curl\MultipleCurl;

class Index extends Controller
{
    public function index()
    {
        $head = new Head();
        
        $pather = new Pather(range('a', 'z'), 5);
        $paths = $pather->permutations();
        
        $curlIterator = new CurlIterator($paths, $head, 'https://github.com/');
        
        $callback = function ($output, $info) {
            var_dump($info);
            var_dump($output);
            
            // if you use webSocekt push it here
            // $server->push($info['http_code']);
        };
        
        $mult = new MultipleCurl($callback, 30);
        $mult->addHandles($curlIterator);
        
        $mult->run();
        
    }
}
```


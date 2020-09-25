<?php
class mgr_goods extends mgr_base
{
    public function pageGetList()
    {
        $page  = !empty($_POST['page']) ? (int)$_POST['page'] : 1;
        $length= !empty($_POST['length']) ? (int)$_POST['length'] : 20;

        $res = model_server_goods::getList($page, $length);
        if(!empty($res['code'])){
            return $this->fail('获取数据失败', -1);
        }
        $result = $res['data'];

        $data['page'] = $result->page;
        $data['pageLength'] = $result->totalPage;
        foreach($result->items as $val){
            $data['list'][] = [
                'goodsId' => $val['pk_goods'],
                'name'    => $val['name'],
                'price'   => $val['price'] / 100,
                'image'   => $val['thumb'],
                'pro'     => $val['pro'],
                'isPrize' => $val['is_prize']
            ];
        }

        return $this->ok($data);
    }

    public function pageAdd()
    {
        $name  = !empty($_POST['name']) ? $_POST['name'] : '';
        $price = !empty($_POST['price']) ? $_POST['price'] : 0;
        $pro   = !empty($_POST['pro']) ? $_POST['pro'] : 0;
        $img   = !empty($_POST['pro']) ? $_POST['pro'] : 0;
        $isPrize = !empty($_POST['isPrize']) ? $_POST['isPrize'] : 0;
        if(empty($name) || empty($img) || empty($price)){
            return $this->fail('参数错误', -1);
        }

        $data = [
            'name' => $name,
            'price'=> $price,
            'pro'  => $pro,
            'img'  => $img,
            'isPrize' => $isPrize
        ];
        $res = model_server_goods::add($data);
        if(empty($res['code'])) return $this->ok('添加成功',0);

        return $this->fail('添加失败', -1);
    }

    public function pageGetGood()
    {
        $goodId  = !empty($_POST['goodId']) ? $_POST['goodId'] : '';
        if(empty($goodId)) return $this->fail('参数错误', -1);
        $res = model_server_goods::getInfo($goodId);
        if(!empty($res['code'])) return $this->fail('获取数据失败', -1);

        $data = [
            'goodsId' => $res['data']['pk_goods'],
            'name'    => $res['data']['name'],
            'img'     => $res['data']['thumb'],
            'price'   => $res['data']['price'] / 100,
            'pro'     => $res['data']['pro'],
            'isPrize' => $res['data']['is_prize']
        ];

        return $this->ok($data);
    }

    public function pageUploadImg()
    {
        $path = ROOT_WWW."/upload";
        if(!is_dir($path)) mkdir($path, 0777, true);
        $filename = "good.".time()."jpg";
        if($_FILES['file']['error'] > 0){
            return $this->fail('上传失败', -1);
        }
        $f = $path.'/'.$filename;
        if(!empty($_FILES['file']['tmp_name'])){
            $res = move_uploaded_file($_FILES['file']['tmp_name'], $f);
            if($res){
                list($width, $height, $type, $attr) = getimagesize($f);
                if($width < 200 || $height < 200){
                    return $this->fail('图片大小不符合标准 200 * 200', -1);
                    @unlink($f);
                }else{
                    $r = utility_file::upload($f);
                    $data['file'] = $this->imgURL($r->fid);
                    @unlink($f);
                    return $this->ok($data);
                }
            }
        }

        return $this->fail('上传失败', -1);
    }
}
<?php 
class mgr_shop extends mgr_base
{
    public function pageAdd()
    {
        $mobile    = !empty($_POST['mobile']) ? $_POST['mobile'] : '';
        $shopName  = !empty($_POST['shopName']) ? $_POST['shopName'] : '';
        $userName  = !empty($_POST['userName']) ? $_POST['userName'] : '';
        $address   = !empty($_POST['address']) ? $_POST['address'] : '';
        $thumb     = !empty($_POST['thumb']) ? $_POST['thumb'] : '';
        if(empty($mobile) || empty($userName) || empty($shopName) || empty($address)){
            return $this->fail('参数错误', -1);
        }

        if(!$this->mobile($mobile)) return $this->fail('手机格式错误', -1);
        $data = [
            'shopName'  => $shopName,
            'mobile'    => $mobile,
            'thumb'     => $thumb,
            'userName'  => $userName,
            'address'   => $address
        ];
        $res = model_server_shop::add($data);
        if(!empty($res['code'])) return $this->fail('添加失败', -1);

        return $this->ok('添加成功', 0);
    }
}
<?php 
class openapi_goods extends openapi_base
{
    public function pageGetList()
    {
        $page   = !empty($this->params['page']) ? $this->params['page'] : 1;
        $length = !empty($this->params['length']) ? $this->params['length'] : 20;

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
                'image'   => $val['thumb']
            ];
        }

        return $this->ok($data);
    }

    public function pageGetPrizeGoods()
    {
        $userId = !empty($this->params['userId']) ? $this->params['userId'] : 0;
	if(empty($userId)) return $this->fail('缺少参数', -1);
        $res = model_server_goods::getPrizeGoods();
        if(!empty($res['code'])){
            return $this->fail('获取数据失败', -1);
        }
        $prizeLogRes = model_server_prizeLog::getNum($userId);
        $data['num'] = empty($prizeLogRes['code']) ? $prizeLogRes['data']['num'] : 0; 
        $prizeVar = model_server_prizeLog::getInfoByUid($userId);
	    $data['var'] = empty($prizeVar['code']) ? $prizeVar['data']['var'] : 0;

        foreach($res['data']->items as $val){
            $data['list'][] = [
                'goodsId' => $val['pk_goods'],
                'name' => $val['name'],
                'img'  => $val['thumb']
            ];
        }

        return $this->ok($data);
    }

    public function pageGoDraw()
    {
        $userId = !empty($this->params['userId']) ? $this->params['userId'] : 0;
        $var = !empty($this->params['var']) ? $this->params['var'] : 0;
	    if(empty($userId)) return $this->fail('缺少参数', -1);
        $prizeLogRes = model_server_prizeLog::getInfoByUid($userId,$var);
        if(!empty($prizeLogRes['code'])) return $this->fail('没有抽奖次数', -4);

        $res = model_server_goods::getPrizeGoods();
        if(!empty($res['code'])){
            return $this->fail('获取数据失败', -1);
        }

        foreach($res['data']->items as $val){
            $data[] = [
                'goodsId' => $val['pk_goods'],
                'name' => $val['name'],
                'pro'  => $val['pro']
            ];
        }
    
        $arr = [];
        foreach ($data as $val) {
            $arr[$val['goodsId']] = $val['pro'];
        }
        $goodsId = $this->getRand($arr);
	    $prizeId = $prizeLogRes['data']['pk_prize'];
        $res = model_server_prizeLog::update($prizeId,['goodsId'=>$goodsId]);

        return $this->ok(['goodsId'=>$goodsId]);
    }

    public function pageGetGoods()
    {
        $goodsId = !empty($this->params['goodsId']) ? $this->params['goodsId'] : 0;
        if(empty($goodsId)) return $this->fail('缺少参数', -1);

        $res = model_server_goods::getInfo($goodsId);
        if(!empty($res['code'])) return $this->fail('获取数据失败', -2);

        $data = [
            'goodsId' => $res['data']['pk_goods'],
            'name'    => $res['data']['name'],
            'img'     => $res['data']['thumb']
        ];

        return $this->ok($data);
    }

    private function getRand($goods)
    {
        $goodsId = 0;
        if(empty($goods)) return $goodsId;
        $goodsSum = array_sum($goods);
        foreach($goods as $key=>$val){
            $randNum = mt_rand(1, $goodsSum);
            if($randNum <= $val){
                $goodsId = $key;
                break;
            }else{
                $goodsSum -= $val;
            }
        }
        unset($goods);
        return $goodsId;
    }
}

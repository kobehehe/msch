<?php

/**
 * Class ProtectForm
 * Protect info
 */
class ProductForm extends InitForm
{
    public function getProductList($supplier_id)
    {
        $result = array();
        // $types = $this->getSupplierTypes($accountId);

        $Product = SupplierProduct::model()->findAll(array(
            "condition" => "supplier_id=:supplier_id",
            "params" => array(
                ":supplier_id" => $supplier_id,
            ),
        ));

        foreach ($Product as $Product) {
            $item = array();
            $item["product_id"] = $Product->id;
            $item["name"] = $Product->name;
 
            $result[] = $item;
        }

        return $result;
    }

    
    public function productEdit($productId){
        $productInfo = SupplierProduct::model()->findByPk($productId);
      
        $arr['na'] = $productInfo->name;
        $arr['price'] = $productInfo->unit_price;
        $arr['unit'] = $productInfo->unit;
        $arr['cost'] = $productInfo->unit_cost;
        $arr['success'] = 1 ;
        return $arr;

    }
    public function productInsert($arr){
       
        $SupplierType = SupplierType::model()->find(
            array('condition'=>"account_id=:account_id",
                  'params'=>array(
                    'account_id'=>$arr['account_id']
                    ),
                )
            );
        // var_dump($SupplierType);die;
        $model = new SupplierProduct(); //产品
        $model->account_id = $arr['account_id'];
        $model->supplier_id = $arr['supplier_id'];
        $model->category = $arr['category'];
        $model->name = $arr['name'];
        $model->unit_price = $arr['unit_price'];
        $model->unit = $arr['unit'];
        $model->supplier_type_id = $arr['supplier_type_id'];
        $model->unit_cost = $arr['unit_cost'];
        $model->description = "111";
        $model->ref_pic_url = "000";
        $model->service_charge_ratio = "222";
        $model->update_time = date('Y-m-d');

        if($model->save()>0){
            $arr['success']='1';
            return $arr;
        }else{
            var_dump($model);
            $arr['success']='0';
            return $arr;
        }

    }

    public function productUpdate($productId,$arr){
        $arr2['supplier_id']    = $arr['supplier_id'];
        $arr2['name']    = $arr['name'];
        $arr2['unit_price']    = $arr['unit_price'];
        $arr2['unit']    = $arr['unit'];
        $arr2['unit_cost']    = $arr['unit_cost'];
        SupplierProduct::model()->updateAll($arr2,'id =:id',array( ":id" => $productId));
    }

    public function productDelete($productId,$account_id){
        $count = SupplierProduct::model()->deleteByPk($productId,'account_id=:account_id',array(':account_id'=>$account_id));
        if($count>0){
            $arr['success']= 1;
            return  $arr;
        }else{
            $arr['success']= 0;
            return  $arr;
        };

    }

    public function OpInsert($order_id,$sp_id,$amount,$price,$cost,$remark)
    {
        $sp = SupplierProduct::model()->findByPk($sp_id);
        if($price == "#"){
            $price = $sp['unit_price'];
        };
        if($cost == "#"){
            $cost = $sp['unit_cost'];
        };
        if($remark == "#"){
            $remark = $sp['description'];
        };

        $model = new OrderProduct(); //产品
        $model->account_id = $sp['account_id'];
        $model->order_id = $order_id;
        $model->product_type = 0;
        $model->product_id = $sp_id;
        $model->order_set_id = 0;
        $model->sort = 1;
        $model->actual_price = $price;
        $model->unit = $amount;
        $model->actual_unit_cost = $cost;
        $model->actual_service_ratio = 0;
        $model->remark = $remark;
        $model->update_time = date('Y-m-d');
        $model->save();
        $id = $model->attributes['id'];

        return $id;
    }

    public function SpInsert($account_id,$supplier_id,$supplier_type_id,$decoration_tap,$name,$category,$unit_price,$unit_cost,$unit,$url)
    {
        $sp = new SupplierProduct;
        $sp->account_id=$account_id;
        $sp->supplier_id=$supplier_id;
        $sp->service_product_id=0;
        $sp->supplier_type_id=$supplier_type_id;
        $sp->dish_type=0;
        $sp->decoration_tap=$decoration_tap;
        $sp->standard_type=0;
        $sp->name=$name;
        $sp->category=$category;
        $sp->unit_price=$unit_price;
        $sp->unit_cost=$unit_cost;
        $sp->unit=$unit;
        $sp->service_charge_ratio=0;
        $sp->ref_pic_url=$url;
        $sp->save();
        $id = $sp->attributes['id'];

        return $id;
    }

    public function SerpInsert($service_person_id, $service_type, $product_name, $price, $cost, $unit, $description, $total_inventory)
    {
        $admin = new ServiceProduct;
        $admin->service_person_id = $service_person_id;
        $admin->service_type = $service_type;
        $admin->product_name = $product_name;
        $admin->price = $price;
        $admin->cost = $cost;
        $admin->unit = $unit;
        $admin->description = $description;
        $admin->product_show = 1;
        $admin->total_inventory = $total_inventory;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $id = $admin->attributes['id'];

        return $id;
    }

    public function Tuidan_list($token)
    {
        $result = yii::app()->db->createCommand("select sp.id,staff.name ".
            " from supplier_product sp left join supplier s on sp.supplier_id=s.id left join staff on s.staff_id=staff.id ".
            " where sp.product_show=1 and sp.supplier_type_id=16 and sp.account_id in (select account_id from staff where id=".$token.")");
        $result = $result->queryAll();

        return $result;
    }

    public function add_string_to_url($url, $string)
    {
        $result = '';
        $t=explode('.', $url);
        if(isset($t[0]) && isset($t[1])){
            if($t[count($t)-2] != 'sm'){
                foreach ($t as $key => $value) {
                    if($key != count($t)-2 && $key != count($t)-1){
                        $result .= $value.".";
                    }else if($key == count($t)-2){
                        $result .= $value."_".$string.".";
                    }else if($key == count($t)-1){
                        $result .= $value;
                    };
                };
                return $result;
            }else{
                return $url;
            };
        };
    }

    public function get_product_inventory($order_date, $city_id, $service_product_id, $total_inventory)
    {
        $t = explode(' ', $order_date);
        $start_time = strtotime($t[0].' 00:00:00');
        $end_time = strtotime($t[0].' 23:59:59'); 
        $product = yii::app()->db->createCommand("select * ".
            " from order_product op where ".
            " order_id in (select id from `order` where UNIX_TIMESTAMP(order_date)>=".$start_time." and UNIX_TIMESTAMP(order_date)<=".$end_time." and account_id in (select id from staff_company where city_id=".$city_id.")) and ".
            " product_id in (select id from supplier_product where service_product_id=".$service_product_id.")");
        $product = $product->queryAll();
        return $total_inventory - count($product);
    }

    public function get_order_doing_and_done($result)
    {
        $order_doing = array();
        $order_done = array();
        $order_non_model = array();
        $order_nearest = array(
                'name' => '无订单',
                'to_date' => 0,
                'order_date' => ""
            );

        foreach ($result as $key => $value) {
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($value['order_date']);  //订单创建时间
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'model_id' => 0,
                    'to_date' => 0,
                );
            if($value['model_id'] != null){
                $item['model_id'] = $value['model_id'];
            };
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
                if($item['model_id'] == 0){
                    $order_non_model[]=$item;
                };
            }else{
                $item['to_date'] = ceil(($zero1-$zero2)/86400); //60s*60min*24h
                $order_done[]=$item;
            };
        };

        //order_doing 以“据婚礼时间”排序
        $to_date = array();
        foreach ( $order_doing as $key => $row ){
            $to_date[] = $row ['to_date'];
        };
        array_multisort($to_date, SORT_ASC, $order_doing);

        //order_done 以“据婚礼时间”排序
        $to_date1 = array();
        foreach ( $order_done as $key => $row ){
            $to_date1[] = $row['to_date'];
        };
        array_multisort($to_date1, SORT_ASC, $order_done);

        //构造最近一个订单
        if(!empty($order_doing)){
            $t = explode(' ', $order_doing[0]['order_date']);
            $name = "";
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($order_doing[0]['order_date']);  //订单创建时间

            foreach ($order_doing as $key => $value) {
                $order_date = explode(' ', $value['order_date']);
                if($order_date[0] == $t[0]){
                    $name .= $value['name'].",";
                };
            };
            $order_nearest['name'] = rtrim($name, ",");
            $order_nearest['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
            $order_nearest['order_date'] = $t[0];
        };  
        foreach ($order_doing as $key => $value) {
            $t = explode(' ', $value['order_date']);
            $order_doing[$key]['order_date'] = $t[0];
        };

        $return_data = array(
                'doing' => $order_doing,
                'done' => $order_done,
                'non_model' => $order_non_model,
                'nearest' => $order_nearest
            );

        return $return_data;
    }

    public function share_store($token, $area_id, $order_id, $city_id)
    {
        //预备信息
        $city_data = array();
        $filter_data = array();
        $staff = Staff::model()->findByPk($token);
        $order = Order::model()->findByPk($order_id);
        $area = OrderShowArea::model()->findByPk($area_id);

        if($city_id == 0){
            $city_id = $staff['city_id'];
        };
        $subarea = yii::app()->db->createCommand("select sub.id,sub.name,area.type ".
            " from order_show_area_subarea sub left join order_show_area area on sub.father_area=area.id ".
            " where sub.father_area=".$area_id.
            " order by sub.sort");
        $subarea = $subarea->queryAll();
        $profit_rate = StaffCompanyProfitRate::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $staff['account_id']
                    )
            ));
        $filter_data['subarea'] = $subarea;

        //构造返回数据
        $result = array();
        foreach ($subarea as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['type'] = $value['type'];
            $item['product_list'] = array();
            $result[] = $item;
        };

        //判断是否为人员统筹
        if($area['type'] != 3){  //不是人员统筹
            $product = yii::app()->db->createCommand("select sp.id,sp.subarea,sp.product_name,sp.cost,sp.unit,sp.total_inventory ".
                " from service_product sp ".
                " left join service_person sperson on sp.service_person_id=sperson.id ".
                " left join staff on sperson.staff_id=staff.id ".
                " where product_show=1 and staff.city_id=".$city_id.
                " and subarea in (select id from order_show_area_subarea where father_area=".$area_id.")");
            $product = $product->queryAll();
            
            foreach ($product as $key => $value) {
                foreach ($result as $key1 => $value1) {
                    if($value['subarea'] == $value1['id']){
                        //获取利润率
                        $cur_company_profit_rate = 0;
                        foreach ($profit_rate as $key2 => $value2) {
                            if($value1['type'] == $value2['area_type']){
                                $cur_company_profit_rate = $value2['profit_rate'];
                            };
                        };

                        //获取产品图片
                        $img = ServiceProductImg::model()->find(array(
                                'condition' => 'service_product_id=:spi',
                                'params' => array(
                                        ':spi' => $value['id']
                                    )
                            ));
                        $t = explode('/', $img['img_url']);
                        //构造产品信息
                        $tem = array();
                        $tem['service_product_id'] = $value['id'];
                        $tem['name'] = $value['product_name'];
                        $tem['price'] = round($value['cost']/(1-$cur_company_profit_rate), 0);
                        $tem['cost'] = $value['cost'];
                        $tem['unit'] = $value['unit'];
                        if($t[0] != 'http:'){
                            $tem['ref_pic_url'] = 'http://file.cike360.com'.$this->add_string_to_url($img['img_url'], 'xs');    
                        }else{
                            $tem['ref_pic_url'] = $img['img_url'].'?x-oss-process=image/resize,m_lfit,h_200,w_200';
                        };
                        $tem['inventory'] = $this->get_product_inventory($order['order_date'], $city_id, $value['id'], $value['total_inventory']);
                        $result[$key1]['product_list'][] = $tem;
                    };
                };
            };

            

            //取城市
            $city = yii::app()->db->createCommand('select * from service_province_city where id in '.
                ' (select city_id from staff where id in '.
                    ' (select staff_id from service_person where service_type in '.
                        ' (select supplier_type from order_show_area_subarea where father_area='.$area_id.'))) ');
            $city = $city->queryAll();

            $city_data = array(
                    'selected' => array(),
                    'other' => array()
                );
            foreach ($city as $key => $value) {
                $item = array();
                $item['id'] = $value['id'];
                $item['name'] = $value['city_name'];
                $item['decoration_service'] = array();

                //取当前城市，库房供应商
                $city_supplier = yii::app()->db->createCommand('select id,name from service_person where id in '.
                    ' (select service_person_id from service_product where product_show=1 and service_type in '.
                        ' (select supplier_type from order_show_area_subarea where father_area='.$area_id.') '.
                        ' and service_person_id in (select id from service_person where staff_id in (select id from staff where city_id='.$value['id'].')))');
                $city_supplier = $city_supplier->queryAll();

                foreach ($city_supplier as $key1 => $value1) {
                    $tem = array();
                    $tem['id'] = $value1['id'];
                    $tem['name'] = $value1['name'];
                    $item['decoration_service'][] = $tem;
                };

                //判断是否为当前城市
                if($value['id'] == $city_id){
                    $city_data['selected'][] = $item;
                }else{
                    $city_data['other'][] = $item;
                };

                //判断是否为当前用户所在城市
                if($value['id'] == $city_id){
                    $item['selected'] = true;
                }else{
                    $item['selected'] = false;
                };
                $filter_data['city'][] = $item;
            };
        }else{  //人员统筹
            //获取利润率
            $cur_company_profit_rate = 0;
            foreach ($profit_rate as $key => $value) {
                if($value['area_type'] == 3){
                    $cur_company_profit_rate = $value['profit_rate'];
                };
            };
            $person = yii::app()->db->createCommand("select sp.id,sp.name,sp.staff_id,sub.id as subarea ".
                " from service_person sp left join order_show_area_subarea sub on sp.service_type=sub.supplier_type ".
                " where sp.show=1 and service_type in (select supplier_type from order_show_area_subarea where father_area=".$area_id.") ");
            $person = $person->queryAll();
            foreach ($person as $key => $value) {
                $product = yii::app()->db->createCommand("select * ".
                    " from service_product serp".
                    " where serp.product_show=1 and service_person_id=".$value['id']);
                $product = $product->queryAll();
                if(!empty($product)){
                    //获取人员图片
                    $img = ServicePersonImg::model()->find(array(
                            'condition' => 'service_person_id=:spi',
                            'params' => array(
                                    ':spi' => $value['id']
                                )
                        ));

                    //构造人员信息
                    $tem = array();
                    $tem['service_product_id'] = $value['id'];
                    $tem['name'] = $value['name'];
                    $t = explode('/', $img['img_url']);
                    if(isset($t[0])){
                        if($t[0] == 'http:' || $t[0] == 'images'){
                            $tem['ref_pic_url'] = $img['img_url'];
                        }else{
                            $tem['ref_pic_url'] = 'http://file.cike360.com'.$this->add_string_to_url($img['img_url'], 'xs');
                        };
                    };
                    $tem['product'] = array();
                    $profit_rate = StaffCompanyProfitRate::model()->find(array(
                        'condition' => 'account_id=:account_id && area_type=:area_type',
                        'params' => array(
                                ':account_id' => $staff['account_id'],
                                ':area_type' => 3
                            )
                    ));

                    foreach ($product as $key1 => $value1) {
                        $item = array();
                        $item['id'] = $value1['id'];
                        $item['service_product_id'] = $value1['id'];
                        $item['unit'] = $value1['unit'];
                        $item['name'] = $value1['product_name'];
                        $item['price'] = round($value1['cost']/(1-(float)$profit_rate['profit_rate']), -2);;
                        $item['cost'] = $value1['cost'];
                        $item['description'] = $value1['description'];
                        $tem['product'][] = $item;
                    };

                    foreach ($result as $key1 => $value1) {
                        if($value['subarea'] == $value1['id']){
                            $result[$key1]['product_list'][] = $tem;
                        };
                    };
                };
            };
        };

        foreach ($result as $key => $value) {
            if(count($value['product_list']) == 0){
                // array_splice($result, $key, 1); 
                unset($result[$key]);
            };
        };

        $new_result = array();
        foreach ($result as $key => $value) {
            $new_result[] = $value;
        };

        $t = explode(' ', $order['order_date']);

        //取订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,oms.model_id from `order` o ".
            "left join order_model_select oms on o.id = oms.order_id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done($tt);

        //取个人搜索历史
        $keyword = yii::app()->db->createCommand("select id,keyword from staff_search_keyword where `show`=1 and staff_id=".$token);
        $keyword = $keyword->queryAll();

        //构造返回数据
        $return_data = array(
                'product' => $new_result,
                'order' => array(
                        'order_place' => $order['order_place'],
                        'order_date' => $t[0]
                    ),
                'order_doing' => $order_data['doing'],
                'city' => $city_data,
                'keyword' => $keyword,
                'filter_data' => $filter_data
            );

        return json_encode($return_data);
    }

    public function get_share_store_data($area_id, $subarea, $token, $order_id, $service_person_list, $low_price, $high_price, $keyword, $page)
    {
        //预备信息
        $product_list = array();
        $staff = Staff::model()->findByPk($token);
        $order = Order::model()->findByPk($order_id);

        //利润率
        $rate = yii::app()->db->createCommand('select * from staff_company_profit_rate where account_id='.$staff['account_id']);
        $rate = $rate->queryAll();

        //查找产品
        $product = array();

        //构造 subarea 查询条件
        $subarea_query = ''; 
        if($subarea != ''){
            $subarea_query = 'and subarea='.$subarea;
        }else{
            $subarea_data = OrderShowAreaSubarea::model()->find(array(
                    'condition' => 'father_area=:father_area',
                    'params' => array(
                            ':father_area' => $area_id,
                        )
                ));
            $subarea = $subarea_data['id'];
            $subarea_query = 'and subarea='.$subarea_data['id'];
        };

        //构造 service_person 查询条件
        $service_person_query = '';
        if($service_person_list != ''){
            $service_person_query = " and service_person_id in (".$service_person_list.")";
        }else{
            $account_service_person = yii::app()->db->createCommand('select id '.
                ' from service_person '.
                ' where service_type in '.
                ' (select supplier_type from order_show_area_subarea where id='.$subarea.') '.
                ' and staff_id in (select id from staff where account_id='.$staff['account_id'].')');
            $account_service_person = $account_service_person->queryAll();

            if(!empty($account_service_person)){
                $service_person_query = " and service_person_id in (select id from service_person where staff_id in (select id from staff where account_id=".$staff['account_id'].")) ";
            }else{
                $service_person_query = " and service_person_id in (select id from service_person where staff_id in (select id from staff where city_id=".$staff['city_id'].")) ";
            };
        };

        //构造 keyword 查询条件
        $keyword_query = '';
        if($keyword != ''){
            $keyword_query = " and (product_name like '%".$keyword."%' or description like '%".$keyword."%')";
        };

        //构造查询条件
        $query = $service_person_query.$subarea_query.$keyword_query;

        //构造页数
        $start = ($page-1)*16;

        //构造利润率
        $profit_rate = yii::app()->db->createCommand('select * from staff_company_profit_rate where account_id='.$staff['account_id'].
            ' and area_type in (select type from order_show_area where id in '.
                ' (select father_area from order_show_area_subarea where id='.$subarea.'))');
        $profit_rate = $profit_rate->queryAll();
        $cur_company_profit_rate = 0;
        if(!empty($profit_rate)){
            $cur_company_profit_rate = $profit_rate[0]['profit_rate'];
        };

        //查询产品
        $all_product = yii::app()->db->createCommand("select sp.id,sp.subarea,sp.product_name,sp.cost,sp.unit,sp.total_inventory,sp.description ".
            " from service_product sp ".
            " where product_show=1 ".$query);
        $all_product = $all_product->queryAll();

        $product = yii::app()->db->createCommand("select sp.id,sp.subarea,area.type as area_type,sp.product_name,sp.price,sp.cost,sp.unit,sp.total_inventory,sp.description ".
            " from service_product sp ".
            " left join order_show_area_subarea sub on sp.subarea=sub.id ".
            " left join order_show_area area on sub.father_area=area.id ".
            " where product_show=1 ".$query." order by sp.update_time DESC limit ".$start.",16");
        $product = $product->queryAll();

        $s = 0;
        foreach ($product as $key => $value) {
            //取利润率
            $profit_rate = 0;
            foreach ($rate as $key1 => $value1) {
                if($value1['area_type'] == $value['area_type']){
                    $profit_rate = $value1['profit_rate'];
                };
            };

            //获取产品图片
            $img = ServiceProductImg::model()->find(array(
                    'condition' => 'service_product_id=:spi',
                    'params' => array(
                            ':spi' => $value['id']
                        )
                ));
            $t = explode('/', $img['img_url']);

            //构造产品信息
            $tem = array();
            $tem['service_product_id'] = $value['id'];
            $tem['name'] = $value['product_name'];
            $tem['price'] = $value['cost']/(1-$profit_rate);
            $tem['cost'] = $value['cost'];
            $tem['unit'] = $value['unit'];
            $tem['description'] = $value['description'];
            $tem['data_id'] = $s;
            if($t[0] != 'http:'){
                $tem['ref_pic_url'] = 'http://file.cike360.com'.$this->add_string_to_url($img['img_url'], 'xs');    
            }else{
                $tem['ref_pic_url'] = $img['img_url'];
            };
            $tem['inventory'] = $this->get_product_inventory($order['order_date'], $staff['city_id'], $value['id'], $value['total_inventory']);
            
            if($low_price == 0 && $high_price == 0){
                $product_list[] = $tem;
                $s++;
            }else if($low_price != 0 && $high_price == 0){
                if(round($value['cost']/(1-$cur_company_profit_rate), 0) >= $low_price){
                    $product_list[] = $tem;
                    $s++;
                };
            }else if($low_price == 0 && $high_price != 0){
                if(round($value['cost']/(1-$cur_company_profit_rate), 0) <= $high_price){
                    $product_list[] = $tem;
                    $s++;
                };
            }else if($low_price != 0 && $high_price != 0){
                if(round($value['cost']/(1-$cur_company_profit_rate), 0) >= $low_price &&round($value['cost']/(1-$cur_company_profit_rate), 0) <= $high_price){
                    $product_list[] = $tem;
                    $s++;
                };
            };
        };

        $result = array(
                'total_page' => ceil(count($all_product)/16),
                'product_list' => $product_list
            );
        return $result;
    }

    public function share_store_filter($area_id, $token, $order_id, $city_id, $service_person_list, $low_price, $high_price, $keyword)
    {
        //预备信息
        $staff = Staff::model()->findByPk($token);
        $order = Order::model()->findByPk($order_id);
        $subarea = yii::app()->db->createCommand("select sub.id,sub.name,area.type ".
            " from order_show_area_subarea sub left join order_show_area area on sub.father_area=area.id ".
            " where sub.father_area=".$area_id.
            " order by sub.sort");
        $subarea = $subarea->queryAll();
        $profit_rate = StaffCompanyProfitRate::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $staff['account_id']
                    )
            ));

        //构造返回数据
        $result = array();
        foreach ($subarea as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['type'] = $value['type'];
            $item['product_list'] = array();
            $result[] = $item;
        };

        //查找产品
        $product = array();
        $service_person_query = '';
        $keyword_query = '';
        if($service_person_list != ''){
            $service_person_query = " and service_person_id in (".$service_person_list.")";
        }else{
            $service_person_query = " and service_person_id in (select id from service_person where staff_id in (select id from staff where city_id=".$city_id.")) ";
        };
        if($keyword != ''){
            $keyword_query = " and (product_name like '%".$keyword."%' or description like '%".$keyword."%')";
        };
        $query = $service_person_query.$keyword_query;

        $product = yii::app()->db->createCommand("select sp.id,sp.subarea,sp.product_name,sp.cost,sp.unit,sp.total_inventory ".
            " from service_product sp ".
            " where product_show=1 and subarea in (select id from order_show_area_subarea where father_area=".$area_id.")".$query);
        $product = $product->queryAll();
        
        foreach ($product as $key => $value) {
            foreach ($result as $key1 => $value1) {
                if($value['subarea'] == $value1['id']){
                    //获取利润率
                    $cur_company_profit_rate = 0;
                    foreach ($profit_rate as $key2 => $value2) {
                        if($value1['type'] == $value2['area_type']){
                            $cur_company_profit_rate = $value2['profit_rate'];
                        };
                    };

                    //获取产品图片
                    $img = ServiceProductImg::model()->find(array(
                            'condition' => 'service_product_id=:spi',
                            'params' => array(
                                    ':spi' => $value['id']
                                )
                        ));
                    $t = explode('/', $img['img_url']);
                    // echo 'http://file.cike360.com'.$this->add_string_to_url($img['img_url'], 'sm');die;
                    //构造产品信息
                    $tem = array();
                    $tem['service_product_id'] = $value['id'];
                    $tem['name'] = $value['product_name'];
                    $tem['price'] = round($value['cost']/(1-$cur_company_profit_rate), 0);
                    $tem['cost'] = $value['cost'];
                    $tem['unit'] = $value['unit'];
                    if($t[0] != 'http:'){
                        $tem['ref_pic_url'] = 'http://file.cike360.com'.$this->add_string_to_url($img['img_url'], 'xs');    
                    }else{
                        $tem['ref_pic_url'] = $img['img_url'];
                    };
                    $tem['inventory'] = $this->get_product_inventory($order['order_date'], $staff['city_id'], $value['id'], $value['total_inventory']);
                    
                    if($low_price == 0 && $high_price == 0){
                        $result[$key1]['product_list'][] = $tem;
                    }else if($low_price != 0 && $high_price == 0){
                        if(round($value['cost']/(1-$cur_company_profit_rate), 0) >= $low_price){
                            $result[$key1]['product_list'][] = $tem;
                        };
                    }else if($low_price == 0 && $high_price != 0){
                        if(round($value['cost']/(1-$cur_company_profit_rate), 0) <= $high_price){
                            $result[$key1]['product_list'][] = $tem;
                        };
                    }else if($low_price != 0 && $high_price != 0){
                        if(round($value['cost']/(1-$cur_company_profit_rate), 0) >= $low_price &&round($value['cost']/(1-$cur_company_profit_rate), 0) <= $high_price){
                            $result[$key1]['product_list'][] = $tem;
                        };
                    };
                };
            };
        };

        return json_encode($result);
    }

    public function insert_product($order_id, $service_product_id, $supplier_product_id, $subarea_id, $token, $sort)
    {
        //预备信息
        $staff = Staff::model()->findByPk($token);
        $subarea = OrderShowAreaSubarea::model()->findByPk($subarea_id);
        $service_product_img = ServiceProductImg::model()->find(array(
                'condition' => 'service_product_id=:service_product_id',
                'params' => array(
                        ':service_product_id' => $service_product_id
                    )
            ));

        $supplier_product = array();
        if($supplier_product_id == 0){
            //查 service_product 对应的 当前公司的 supplier_product
            $supplier_product = SupplierProduct::model()->findAll(array(
                    'condition' => 'account_id=:account_id && service_product_id=:service_product_id',
                    'params' => array(
                            ':account_id' => $staff['account_id'],
                            ':service_product_id' => $service_product_id,
                        )
                ));
        }else{
            $supplier_product = SupplierProduct::model()->findByPk($supplier_product_id);
        };
        

        //添加 order_product
        $admin = new OrderProduct;
        $admin->account_id = $staff['account_id'];
        $admin->order_id = $order_id;
        $admin->product_type = 0;
        $admin->product_id = $supplier_product[0]['id'];
        $admin->order_set_id = 0;
        $admin->sort = $sort+1;
        $admin->actual_price = $supplier_product['unit_price'];
        $admin->unit = 1;
        $admin->actual_unit_cost = $supplier_product['unit_cost'];
        $admin->actual_service_ratio = $supplier_product['service_charge_ratio'];
        $admin->remark = $supplier_product['description'];
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $order_product_id = $admin->attributes['id'];

        //添加 order_show
        $admin = new OrderShow;
        $admin->type = 2;
        $admin->img_id = 0;
        $admin->order_product_id = $order_product_id;
        $admin->words = 0;
        $admin->order_id = $order_id;
        $admin->subarea = $subarea_id;
        $admin->area_sort = $sort+1;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
    }

    public function product_insert($order_id, $service_product_id, $price, $amount, $remark, $token)
    {
        //预备信息
        $staff = Staff::model()->findByPk($token);
        $service_product_img = ServiceProductImg::model()->find(array(
                'condition' => 'service_product_id=:service_product_id',
                'params' => array(
                        ':service_product_id' => $service_product_id
                    )
            ));
        $service_product = ServiceProduct::model()->findByPk($service_product_id);
        $subarea = OrderShowAreaSubarea::model()->findByPk($service_product['subarea']);
        $service_staff = yii::app()->db->createCommand('select * from service_person where id in (select service_person_id from service_product where id='.$service_product_id.')');
        $service_staff = $service_staff->queryAll();
        $service_staff_id = 0;
        if(!empty($service_staff_id)){
            $service_staff_id = $service_staff[0]['staff_id'];
        };

        //查 service_product 对应的 当前公司的 supplier_product
        $supplier_product = SupplierProduct::model()->find(array(
                'condition' => 'account_id=:account_id && service_product_id=:service_product_id',
                'params' => array(
                        ':account_id' => $staff['account_id'],
                        ':service_product_id' => $service_product_id,
                    )
            ));
        //如果当前公司没有该产品，则新增supplier_product
        if(empty($supplier_product)){
            //构造supplier_id
            $supplier = Supplier::model()->find(array(
                    'condition' => 'staff_id=:staff_id',
                    'params' => array(
                            ':staff_id' => $service_staff_id
                        )
                ));
            if(empty($supplier)){
                $admin = new Supplier;
                $admin->account_id = $staff['account_id'];
                $admin->type_id = $subarea['supplier_type'];
                $admin->staff_id = $service_staff_id;
                $admin->update_time = date('y-m-d h:i:s',time());
                $admin->save();
                $supplier_id = $admin->attributes['id'];
            }else{
                $supplier_id = $supplier['id'];
            };
            

            $admin = new SupplierProduct;
            $admin->account_id = $staff['account_id'];
            $admin->supplier_id = $supplier_id;
            $admin->service_product_id = $service_product_id;
            $admin->supplier_type_id = $subarea['supplier_type'];
            $admin->name = $service_product['product_name'];
            $admin->unit_price = $service_product['price'];
            $admin->unit_cost = $service_product['cost'];
            $admin->unit = $service_product['unit'];
            $admin->description = $service_product['description'];
            $admin->product_show = 1;
            $admin->save();
            $supplier_product_id = $admin->attributes['id'];

            $supplier_product = SupplierProduct::model()->findByPk($supplier_product_id);
        };

        //查找sort
        $show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id && subarea=:subarea',
                'params' => array(
                        ':order_id' => $order_id,
                        ':subarea' => $subarea['id']
                    ),
                'order' => 'area_sort DESC'
            ));
        $sort = 1;
        if(!empty($show)){
            $sort = $show[0]['area_sort'] + 1;
        };

        //添加 order_product
        $admin = new OrderProduct;
        $admin->account_id = $staff['account_id'];
        $admin->order_id = $order_id;
        $admin->product_type = 0;
        $admin->product_id = $supplier_product['id'];
        $admin->order_set_id = 0;
        $admin->sort = $sort;
        $admin->actual_price = $price;
        $admin->unit = $amount;
        $admin->actual_unit_cost = $supplier_product['unit_cost'];
        $admin->actual_service_ratio = $supplier_product['service_charge_ratio'];
        $admin->remark = $remark;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $order_product_id = $admin->attributes['id'];

        //添加 order_show
        $admin = new OrderShow;
        $admin->type = 2;
        $admin->img_id = 0;
        $admin->order_product_id = $order_product_id;
        $admin->words = 0;
        $admin->order_id = $order_id;
        $admin->subarea = $subarea['id'];
        $admin->area_sort = $sort;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
    }

    public function no_supplier_product_insert($token, $order_id, $img_id, $img_type, $subarea_id, $name, $price, $cost, $unit, $amount, $description, $remark)
    {
        //预备信息
        $order = Order::model()->findByPk($order_id);
        $subarea = OrderShowAreaSubarea::model()->findByPk($subarea_id);
        $img_url = '';
        if($img_type == "'folder'" || $img_type == 'folder'){
            $library_web_case_img = LibraryWebCaseImg::model()->findByPk($img_id); 
            $t = explode('/', $library_web_case_img['local_URL']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $library_web_case_img['local_URL'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($library_web_case_img['local_URL'], '.');
            };
        }else if($img_type == 'case'){
            $case_resources = CaseResources::model()->findByPk($img_id); 
            $t = explode('/', $case_resources['CR_Path']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $case_resources['CR_Path'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($case_resources['CR_Path'], '.');
            };
        }else if($img_type == 'preference'){
            $preference = OrderPreference::model()->findByPk($img_id);
            $t = explode('/', $preference['img_url']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $preference['img_url'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($preference['img_url'], '.');
            };
        };
        
        $sort = 1;
        $os_type = 2;
        $os_img_id = 0;
        $order_product_id = 0;
        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id && subarea=:subarea',
                'params' => array(
                        ':order_id' => $order_id,
                        ':subarea' => $subarea_id
                    )
            ));
        $sort += count($order_show);

        //判断是效果图，还是产品
        if($subarea['father_area'] == 8 || $subarea['father_area'] == 1){ //效果图
            $os_type = 1;
            $admin = new OrderShowImg;
            $admin->subarea_id = $subarea_id;
            $admin->img_url = $img_url;
            $admin->staff_id = $token;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $os_img_id = $admin->attributes['id'];
        }else{
            //插入service_product
            $admin = new ServiceProduct;
            $admin->service_person_id = 0;
            $admin->service_type = $subarea['supplier_type'];
            $admin->product_name = $name;
            $admin->subarea = $subarea_id;
            $admin->decoration_tap = $subarea['decoration_tap'];
            $admin->price = $price;
            $admin->cost = $cost;
            $admin->unit = $unit;
            $admin->description = $description;
            $admin->product_show = 1;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $service_product_id = $admin->attributes['id'];

            //插入service_product_img
            $admin = new ServiceProductImg;
            $admin->service_product_id = $service_product_id;
            $admin->img_url = $img_url;
            $admin->sort = 1;
            $admin->img_show = 1;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();

            //插入supplier_product
            $sp = new SupplierProduct;
            $sp->account_id=$order['account_id'];
            $sp->supplier_id=0;
            $sp->service_product_id=$service_product_id;
            $sp->supplier_type_id=$subarea['supplier_type'];
            $sp->dish_type=0;
            $sp->decoration_tap=$subarea['decoration_tap'];
            $sp->standard_type=0;
            $sp->name=$name;
            $sp->category=2;
            $sp->unit_price=$price;
            $sp->unit_cost=$cost;
            $sp->unit=$unit;
            $sp->service_charge_ratio=0;
            $sp->ref_pic_url=$img_url;
            $sp->save();
            $supplier_product_id = $sp->attributes['id'];

            //插入order_product
            $model = new OrderProduct(); 
            $model->account_id = $order['account_id'];
            $model->order_id = $order_id;
            $model->product_type = 0;
            $model->product_id = $supplier_product_id;
            $model->order_set_id = 0;
            $model->sort = 1;
            $model->actual_price = $price;
            $model->unit = $amount;
            $model->actual_unit_cost = $cost;
            $model->actual_service_ratio = 0;
            $model->remark = $remark;
            $model->update_time = date('y-m-d h:i:s',time());
            $model->save();
            $order_product_id = $model->attributes['id'];
        }

        //插入order_show
        $os = new OrderShow;
        $os->type=$os_type;
        $os->img_id=$os_img_id;
        $os->order_product_id=$order_product_id;
        $os->words=0;
        $os->order_id=$order_id;
        $os->subarea=$subarea_id;
        $os->area_sort=$sort;
        $os->update_time=date('y-m-d h:i:s',time());
        $os->save();
        $os_id = $os->attributes['id'];
    }

    public function no_supplier_product_insert_to_my_product($token, $order_id, $img_id, $img_type, $subarea_id, $name, $price, $cost, $unit, $amount, $description, $remark)
    {
        //预备信息
        $order = Order::model()->findByPk($order_id);
        $subarea = OrderShowAreaSubarea::model()->findByPk($subarea_id);
        $img_url = '';
        if($img_type == "'folder'" || $img_type == 'folder'){
            $library_web_case_img = LibraryWebCaseImg::model()->findByPk($img_id); 
            $t = explode('/', $library_web_case_img['local_URL']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $library_web_case_img['local_URL'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($library_web_case_img['local_URL'], '.');
            };
        }else if($img_type == 'case'){
            $case_resources = CaseResources::model()->findByPk($img_id); 
            $t = explode('/', $case_resources['CR_Path']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $case_resources['CR_Path'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($case_resources['CR_Path'], '.');
            };
        }else if($img_type == 'preference'){
            $preference = OrderPreference::model()->findByPk($img_id);
            $t = explode('/', $preference['img_url']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $preference['img_url'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($preference['img_url'], '.');
            };
        };
        
        //构造service_person
        $service_person_id = 0;
        $service_person = yii::app()->db->createCommand('select * from service_person '.
            ' where service_type='.$subarea['supplier_type'].
            ' and staff_id in '.
            ' (select id from staff where account_id='.$order['account_id'].')');
        $service_person = $service_person->queryAll();
        if(empty($service_person)){
            $s = Staff::model()->find(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $order['account_id']
                        )
                ));

            $admin = new ServicePerson;
            $admin->name = $s['name'];
            $admin->telephone = $s['telephone'];
            $admin->staff_id = $s['id'];
            $admin->service_type = $subarea['supplier_type'];
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $service_person_id = $admin->attributes['id'];
        }else{
            $service_person_id = $service_person[0]['id'];
        };

        $sort = 1;
        $os_type = 2;
        $os_img_id = 0;
        $order_product_id = 0;
        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id && subarea=:subarea',
                'params' => array(
                        ':order_id' => $order_id,
                        ':subarea' => $subarea_id
                    )
            ));
        $sort += count($order_show);

        //判断是效果图，还是产品
        if($subarea['father_area'] == 8 || $subarea['father_area'] == 1){ //效果图
            $os_type = 1;
            $admin = new OrderShowImg;
            $admin->subarea_id = $subarea_id;
            $admin->img_url = $img_url;
            $admin->staff_id = $token;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $os_img_id = $admin->attributes['id'];
        }else{
            //插入service_product
            $admin = new ServiceProduct;
            $admin->service_person_id = $service_person_id;
            $admin->service_type = $subarea['supplier_type'];
            $admin->product_name = $name;
            $admin->subarea = $subarea_id;
            $admin->decoration_tap = $subarea['decoration_tap'];
            $admin->price = $price;
            $admin->cost = $cost;
            $admin->unit = $unit;
            $admin->description = $description;
            $admin->product_show = 1;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $service_product_id = $admin->attributes['id'];

            //插入service_product_img
            $admin = new ServiceProductImg;
            $admin->service_product_id = $service_product_id;
            $admin->img_url = $img_url;
            $admin->sort = 1;
            $admin->img_show = 1;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();

            //插入supplier_product
            $sp = new SupplierProduct;
            $sp->account_id=$order['account_id'];
            $sp->supplier_id=0;
            $sp->service_product_id=$service_product_id;
            $sp->supplier_type_id=$subarea['supplier_type'];
            $sp->dish_type=0;
            $sp->decoration_tap=$subarea['decoration_tap'];
            $sp->standard_type=0;
            $sp->name=$name;
            $sp->category=2;
            $sp->unit_price=$price;
            $sp->unit_cost=$cost;
            $sp->unit=$unit;
            $sp->service_charge_ratio=0;
            $sp->ref_pic_url=$img_url;
            $sp->save();
            $supplier_product_id = $sp->attributes['id'];
        }
    }

    public function own_account_new_product_insert($token, $order_id, $img_id, $img_type, $subarea_id, $name, $price, $cost, $unit, $amount, $description, $remark)
    {
        //预备信息
        $order = Order::model()->findByPk($order_id);
        $subarea = OrderShowAreaSubarea::model()->findByPk($subarea_id);
        $img_url = '';
        if($img_type == "'folder'" || $img_type == 'folder'){
            $library_web_case_img = LibraryWebCaseImg::model()->findByPk($img_id); 
            $t = explode('/', $library_web_case_img['local_URL']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $library_web_case_img['local_URL'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($library_web_case_img['local_URL'], '.');
            };
        }else if($img_type == 'case'){
            $case_resources = CaseResources::model()->findByPk($img_id); 
            $t = explode('/', $case_resources['CR_Path']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $case_resources['CR_Path'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($case_resources['CR_Path'], '.');
            };
        }else if($img_type == 'preference'){
            $preference = OrderPreference::model()->findByPk($img_id);
            $t = explode('/', $preference['img_url']);
            if($t[0] == 'http:' || $t[0] ==''){
                $img_url = $preference['img_url'];
            }else if($t[0]  = '.'){
                $img_url = ltrim($preference['img_url'], '.');
            };
        };
        
        //构造service_person
        $service_person_id = 0;
        $service_person = yii::app()->db->createCommand('select * from service_person '.
            ' where service_type='.$subarea['supplier_type'].
            ' and staff_id in '.
            ' (select id from staff where account_id='.$order['account_id'].')');
        $service_person = $service_person->queryAll();
        if(empty($service_person)){
            $s = Staff::model()->find(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $order['account_id']
                        )
                ));
            $account = StaffCompany::model()->findByPk($s['account_id']);

            $admin = new ServicePerson;
            $admin->name = $account['name'];
            $admin->telephone = $s['telephone'];
            $admin->staff_id = $s['id'];
            $admin->service_type = $subarea['supplier_type'];
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $service_person_id = $admin->attributes['id'];
        }else{
            $service_person_id = $service_person[0]['id'];
        };

        $sort = 1;
        $os_type = 2;
        $os_img_id = 0;
        $order_product_id = 0;
        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id && subarea=:subarea',
                'params' => array(
                        ':order_id' => $order_id,
                        ':subarea' => $subarea_id
                    )
            ));
        $sort += count($order_show);

        //判断是效果图，还是产品
        if($subarea['father_area'] == 8 || $subarea['father_area'] == 1){ //效果图
            $os_type = 1;
            $admin = new OrderShowImg;
            $admin->subarea_id = $subarea_id;
            $admin->img_url = $img_url;
            $admin->staff_id = $token;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $os_img_id = $admin->attributes['id'];
        }else{
            //插入service_product
            $admin = new ServiceProduct;
            $admin->service_person_id = $service_person_id;
            $admin->service_type = $subarea['supplier_type'];
            $admin->product_name = $name;
            $admin->subarea = $subarea_id;
            $admin->decoration_tap = $subarea['decoration_tap'];
            $admin->price = $price;
            $admin->cost = $cost;
            $admin->unit = $unit;
            $admin->description = $description;
            $admin->product_show = 1;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $service_product_id = $admin->attributes['id'];

            //插入service_product_img
            $admin = new ServiceProductImg;
            $admin->service_product_id = $service_product_id;
            $admin->img_url = $img_url;
            $admin->sort = 1;
            $admin->img_show = 1;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();

            //插入supplier_product
            $sp = new SupplierProduct;
            $sp->account_id=$order['account_id'];
            $sp->supplier_id=0;
            $sp->service_product_id=$service_product_id;
            $sp->supplier_type_id=$subarea['supplier_type'];
            $sp->dish_type=0;
            $sp->decoration_tap=$subarea['decoration_tap'];
            $sp->standard_type=0;
            $sp->name=$name;
            $sp->category=2;
            $sp->unit_price=$price;
            $sp->unit_cost=$cost;
            $sp->unit=$unit;
            $sp->service_charge_ratio=0;
            $sp->ref_pic_url=$img_url;
            $sp->save();
            $supplier_product_id = $sp->attributes['id'];
        }
    }































}

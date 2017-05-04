<?php

/**
 * Class ProtectForm
 * Protect info
 */
class OrderForm extends InitForm
{
    public function get_today_indoor($account_id, $staff_hotel_id)
    {
        //取当日进店数据
        $time = time();
        $date = date("y-m-d");

        $criteria = new CDbCriteria; 
        $criteria->addSearchCondition('update_time', $date);
        $criteria->addCondition('account_id=:account_id');
        $criteria->addCondition('staff_hotel_id=:staff_hotel_id');
        $criteria->params[':account_id']=$account_id;  
        $criteria->params[':staff_hotel_id']=$staff_hotel_id;  
        $order1 = Order::model()->findAll($criteria);

        return count($order1); 
    }

    public function get_today_follow($staff_hotel_id)
    {
        $follow =  yii::app()->db->createCommand("select * from order_merchandiser where order_id in ".
            "(select id from `order` where staff_hotel_id=".$staff_hotel_id.")");
        $follow =  $follow->queryAll();
        $follow_amount = 0;
        foreach ($follow as $key => $value) {
            $t = explode(' ', $value['time']);
            if(isset($t[0])){
                if($t[0] == date("Y-m-d")){
                    $follow_amount++;
                };
            };
        };   
        return $follow_amount;
    }

    public function get_today_open_order($account_id, $staff_hotel_id)
    {
        $result = yii::app()->db->createCommand("select `order`.id,order_payment.update_time from `order` right join order_payment on `order`.id = order_payment.order_id where type=0 and account_id=".$account_id." and staff_hotel_id=".$staff_hotel_id." order by id");
        $result = $result->queryAll();
        $open_order = array();

        // print_r($result);die;
        foreach ($result as $key => $value) {
            $t=0;
            foreach ($open_order as $key1 => $value1) {
                if($value['id'] == $value1){
                    $t++;
                };
            };
            $time = explode(' ', $value['update_time']);
            // print_r(date('Y-m-d',time()));die;
            if($t==0 && $time[0] == date('Y-m-d',time())){$open_order[] = $value['id'];};
        };

        return $open_order;
    }

    public function get_today_carry_out($staff_hotel_id)
    {
        //取当日执行订单总数
        $carry_out = 0;
        $result = yii::app()->db->createCommand("select * from `order` where staff_hotel_id=" . $staff_hotel_id);
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            $t = explode(' ', $value['order_date']);
            if(isset($t[0])){
                if($t[0] == date("Y-m-d")){
                    if($value['order_status'] != 0 && $value['order_status'] != 1){
                        $carry_out++;
                    };
                };
            };
        };

        return $carry_out;
    }

    public function get_today_payment($staff_hotel_id)
    {
        //取当日收款总数
        $today_cash = 0;
        $result = yii::app()->db->createCommand("select * from order_payment where order_id in (select id from `order` where staff_hotel_id=".$staff_hotel_id.")");
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            $t = explode(' ', $value['time']);
            if(isset($t[0])){
                if($t[0] == date("Y-m-d")){
                    $today_cash += $value['money'];
                };
            };
        };

        return $today_cash;
    }

    public function get_hotel_total_sales($staff_hotel_id)
    {
        //取累计订单
        $order_total = Order::model()->findAll(array(
                'condition' => 'staff_hotel_id=:staff_hotel_id',
                'params' => array(
                        ':staff_hotel_id' => $staff_hotel_id
                    )
            ));
        $wedding_all = 0;
        $meeting_all = 0;
        $wedding_doing = 0;
        $meeting_doing = 0;
        $sure_order_id = "(";

        // $ttt = "";

        foreach ($order_total as $key => $value) {
            // if($value['id'] == 481){echo 1;};
            // echo $value['id'].",";
            $t = explode(' ', $value['order_date']);
            $t1 = explode('-', $t[0]);
            // print_r(date('M'));die;
            if($value['order_status'] == 2 || $value['order_status'] == 3 || $value['order_status'] == 4 || $value['order_status'] == 5 || $value['order_status'] == 6){
                if($t1[0] == date('Y')){$sure_order_id .= $value['id'].",";};
                if($t1[0] >= date('Y')){
                    if($value['order_type'] == 1){
                        $meeting_all++;
                    }else if($value['order_type'] == 2){
                        // echo $value['id'].",".$t[0]."||";

                        $wedding_all++;
                        // if($value['id'] == 48s1){echo 2;};
                    };

                    if(($t1[1] == date('m') && $t1[2] >= date('d')) || ($t1[1] > date('m')) || $t1[0] > date('Y')){
                        if($value['order_type'] == 1){
                            $meeting_doing++;
                        }else if($value['order_type'] == 2){
                            $wedding_doing++;
                        };
                    };  
                };
            };
        };
        // print_r($ttt);die;
        $sure_order_id = rtrim($sure_order_id, ",");
        $sure_order_id .= ")";
        
        // print_r($sure_order_id);die;

        

        //取门店销售目标
        $hotel = StaffHotel::model()->findByPk($staff_hotel_id);


        //取销售额  ()
        $order_product_designOrder = array();
        if($sure_order_id != '()'){
            $order_product_designOrder = yii::app()->db->createCommand("".
                "select actual_price,order_product.unit,actual_unit_cost,actual_service_ratio,designer_id,planner_id,other_discount,feast_discount,discount_range,supplier_type_id,s1.`name` as designer_name,s2.`name` as planner_name ".
                "from (((order_product left join `order` on order_id = `order`.id) ".
                "left join supplier_product on product_id = supplier_product.id) ".
                "left join staff s1 on designer_id = s1.id) ".
                "left join staff s2 on planner_id = s2.id".
                " where order_id in " .$sure_order_id. " order by designer_id");
            $order_product_designOrder = $order_product_designOrder->queryAll(); 
        };
            

        // print_r(json_encode($order_product_designOrder));die;

        $hotel_total_sales = 0;
        $hotel_total_cost = 0;

        $design_person_sales = array();
        $tem_id = 0;
        if(!empty($order_product_designOrder)){
            $tem_id = $order_product_designOrder[0]['designer_id'];
        };
        $t_total_sales = 0;//存储个人策划总价
        $tem_person_data = array();//存储个人信息

        foreach ($order_product_designOrder as $key => $value){
            $hotel_total_cost += $value['actual_unit_cost'];
            if($value['designer_id'] != $tem_id){
                $t_total_sales = 0;
            };
            if($value['supplier_type_id'] == 2){
                $hotel_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1)*(1+$value['actual_service_ratio']*0.01);
            }else{
                $t=explode(',', $value['discount_range']);
                $tem = 0;
                foreach ($t as $key1 => $value1) {
                    if($value1 == $value['supplier_type_id']){$tem++;};
                };
                if($tem == 0){//不在折扣范围内
                    $hotel_total_sales += $value['actual_price']*$value['unit'];
                    $t_total_sales += $value['actual_price']*$value['unit'];
                }else{//在折扣范围内
                    $hotel_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1);
                    $t_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1);
                };
            };

            if($value['designer_id'] == $tem_id){
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    ); 
            }else{
                $design_person_sales[] = $tem_person_data;
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    );
            };
            $tem_id = $value['designer_id'];
        };

        $arr_order = array();
        if($sure_order_id != "()"){
            $arr_order = yii::app()->db->createCommand("select turnover from `order` where id in ".$sure_order_id);
            $arr_order = $arr_order->queryAll(); 
        };
        foreach ($arr_order as $key => $value) {
            if($value['turnover'] != 0 && $value['turnover'] != "" && $value['turnover'] != null){
                $hotel_total_sales -= $value['turnover'];
            };
        };

        //取收款总额
        $order_payment = array();
        if($sure_order_id != "()"){
            $order_payment = yii::app()->db->createCommand("select * from order_payment where order_id in " .$sure_order_id);
            $order_payment = $order_payment->queryAll(); 
        }; 
        $order_total_payment = 0;
        foreach ($order_payment as $key => $value) {
            $order_total_payment += $value['money'];
        };

        $result = array(
                'wedding_all' => $wedding_all,
                'meeting_all' => $meeting_all,
                'wedding_doing' =>  $wedding_doing,
                'meeting_doing' =>  $meeting_doing,
                'sure_order_id' =>  $sure_order_id,
                'order_total_payment' => $order_total_payment,
                'hotel_total_sales' => $hotel_total_sales,
                'hotel_total_cost' => $hotel_total_cost,
                'design_person_sales' => $design_person_sales
            );

        return $result;
    }

    public function get_order_win_and_lose($account_id, $staff_hotel_id)
    {
        //取订单池和飞单
        $result = yii::app()->db->createCommand("select o.id as order_id,o.order_date,o.update_time,s1.name as planner_name,s2.name as designer_name,o.order_type ".
            " from `order` o left join staff s1 on planner_id=s1.id ".
            " left join staff s2 on designer_id=s2.id ".
            " where order_status in (0,1) and o.account_id=".$account_id." and o.staff_hotel_id=".$staff_hotel_id." order by o.order_date");
        $result = $result->queryAll();

        $order_pool = array();
        $fly_order = array();
        foreach ($result as $key => $value) {
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($value['update_time']);  //订单创建时间
            $zero3=strtotime ($value['order_date']);  //活动日期
            $zero4=strtotime (date('Y-01-01 00:00:00'));

            $open_time=ceil(($zero1-$zero2)/86400); //60s*60min*24h

            $t = explode(' ', $value['order_date']);

            $item = array(
                    'order_id' => $value['order_id'],
                    'order_date' => $t[0],
                    'planner_name' => $value['planner_name'],
                    'designer_name' => $value['designer_name'],
                    'open_time' => $open_time,
                    'order_type' => $value['order_type']
                );
            if($zero3 >= $zero1){$order_pool[] = $item;};
            if($zero3 < $zero1 && $zero3 >= $zero4){$fly_order[] = $item;};
        };

        $result = array(
                'order_pool' => $order_pool,
                'fly_order' => $fly_order
            );
        return $result;
    }

    public function get_staff_sales($account_id)
    {
        //计算个人业绩
        $order_total = Order::model()->findAll(array( //取门店累计订单
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $account_id
                    )
            ));

        $sure_order_id = "(";

        foreach ($order_total as $key => $value) {
            $t = explode(' ', $value['order_date']);
            $t1 = explode('-', $t[0]);
            // print_r(date('M'));die;
            if($value['order_status'] == 2 || $value['order_status'] == 3 || $value['order_status'] == 4 || $value['order_status'] == 5 || $value['order_status'] == 6){
                if($t1[0] == date('Y')){$sure_order_id .= $value['id'].",";};
            };
        };
        $sure_order_id = rtrim($sure_order_id, ",");
        $sure_order_id .= ")";
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        
        $order_product_planGroup = array();
        if($sure_order_id != "()"){
            $order_product_planGroup = yii::app()->db->createCommand("". //个人餐饮业绩
                "select planner_id,s2.name,sum(actual_price*unit*feast_discount*0.1*(1+actual_service_ratio*0.01)) as total ".
                "from (order_product left join `order` on order_id = `order`.id) ".
                "left join staff s2 on planner_id = s2.id".
                " where order_id in " .$sure_order_id. " group by planner_id");
            $order_product_planGroup = $order_product_planGroup->queryAll(); 
        };

        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋

        $order_product_designOrder = array();
        if($sure_order_id != "()"){
            $order_product_designOrder = yii::app()->db->createCommand("".  //个人策划业绩
                "select actual_price,order_product.unit,actual_service_ratio,designer_id,planner_id,other_discount,feast_discount,discount_range,supplier_type_id,s1.`name` as designer_name,s2.`name` as planner_name ".
                "from (((order_product left join `order` on order_id = `order`.id) ".
                "left join supplier_product on product_id = supplier_product.id) ".
                "left join staff s1 on designer_id = s1.id) ".
                "left join staff s2 on planner_id = s2.id".
                " where order_id in " .$sure_order_id. "order by designer_id");
            $order_product_designOrder = $order_product_designOrder->queryAll(); 
        };

        $design_person_sales = array();
        $tem_id = 0;
        if(!empty($order_product_designOrder)){
            $tem_id = $order_product_designOrder[0]['designer_id'];    
        };
        $t_total_sales = 0;//存储个人策划总价
        $tem_person_data = array();//存储个人信息

        foreach ($order_product_designOrder as $key => $value){
            if($value['designer_id'] != $tem_id){
                $t_total_sales = 0;
            };
            if($value['supplier_type_id'] != 2){
                $t=explode(',', $value['discount_range']);
                $tem = 0;
                foreach ($t as $key1 => $value1) {
                    if($value1 == $value['supplier_type_id']){$tem++;};
                };
                if($tem == 0){//不在折扣范围内
                    $t_total_sales += $value['actual_price']*$value['unit'];
                }else{//在折扣范围内
                    $t_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1);
                };
            };

            if($value['designer_id'] == $tem_id){
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    ); 
            }else{    
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    );
                $design_person_sales[] = $tem_person_data;
            };
            // echo $tem_id."|";
            $tem_id = $value['designer_id'];
        };
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        $arr_staff_sales = array();//存全部员工销售额;
        $staff_sales = array();//存个人销售额；
        // print_r($design_person_sales);die;
        foreach ($order_product_planGroup as $key_p => $value_p) {
            $staff_sales['id'] = $value_p['planner_id'];
            $staff_sales['name'] = $value_p['name'];
            $staff_sales['sales'] = $value_p['total'];
            foreach ($design_person_sales as $key_d => $value_d) {
                if($value_p['planner_id'] == $value_d['designer_id']){
                    $staff_sales['sales'] += $value_d['total'];
                };/*else{
                    $t=array(
                            'id' => $value_d['designer_id'],
                            'name' => $value_d['name'],
                            'sales' => $value_d['total'],
                        );
                    foreach ($order_product_planGroup as $kp => $vp) {
                        
                    }
                };*/
            };
            $arr_staff_sales[] = $staff_sales;
        };
        // print_r($arr_staff_sales);die;
        foreach ($design_person_sales as $key => $value) {
            $staff_sales['id'] = $value['designer_id'];
            $staff_sales['name'] = $value['name'];
            $staff_sales['sales'] = $value['total'];
            $t=0;
            foreach ($arr_staff_sales as $k_arr => $val_arr) {
                if($val_arr['id'] == $value['designer_id']){
                    $t++;
                };
            };
            if($t == 0){
                $arr_staff_sales[] = $staff_sales;
            };
        };
        
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋

        foreach ($arr_staff_sales as $key => $value) {
            $arr_staff_sales[$key]['sales'] = round($value['sales']);        
        };


        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        $sales = array();
        foreach ($arr_staff_sales as $user) {
            $sales[] = $user['sales'];
        }
         
        array_multisort($sales, SORT_DESC, $arr_staff_sales);

        return $arr_staff_sales;
    }

    public function getOrderDetail($orderId)
    {
       $DetailArr = Order::model()->find(array(
            "condition" => "id=:id",
            "params" => array( ":id" => $orderId ),

       ));
       return $DetailArr;
    }

    public function getOrderIndex($accountId)
    {
        $result = array();

        $Order = order::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                ":account_id" => $accountId,
            ),
        ));
        //$Order = "SELECT order_date,count(order_time) from order GROUP BY order_date";
            $aa = '';
            $bb = '';
            $cc = '';
            $dd = '';
    // var_dump($Order);die;
        foreach ($Order as $Order) {
            $item = array();
            $time = $Order->order_time;
            // var_dump($time);
            $status = $Order->order_status;
            $date = $Order->order_date;
            if($time == '0' && $status != '1'){
                $aa.= $date.',';
            }
            if($time == '1' && $status != '1'){
                $bb.= $date.',';
            }
            if($time == '2' && $status != '1'){
                $cc.= $date.',';
            }
            if($status == '1'){
                $dd.=$date.',';
            }
        }
        $aa = rtrim($aa,',');
        $array1 = explode(',',$aa);
        // var_dump($array1);die;
        $bb = rtrim($bb,',');
        $array2 = explode(',',$bb);
        // var_dump($array2);die;
        $cc = rtrim($cc,',');
        $array3 = explode(',',$cc);
        // var_dump($array3);die;
        $dd = rtrim($dd,',');
        // var_dump($dd);die;
        $inter1 = array_intersect($array1,$array2,$array3);//取三者交际 一天有三个订单的
        $item['data'] = implode(',',$inter1);
        // var_dump($inter1);
        //把对应的当天（有三个订单的天数）的订单传到前台
        // $date_order = order::model()->findAll(array(
        //     "condition" => "order_date=:order_date",
        //     "params" => array(
        //         ":order_date" => $item['data'] ,
        //     ),
        // ));
        foreach ($inter1 as $key=>$val){
            $date_order = order::model()
            ->findAll(array(
            "condition" => "order_date=:order_date",
            "params" => array(
                ":order_date" => $inter1[$key],
            ),
        ));
         // var_dump($date_order); 
        }
        
         // var_dump($date_order);die;
        $item['data'] = date("d",strtotime($item['data']));
        // var_dump($item['data']);die;  
        $marge1 = array_merge($array1,$array2);//先取0 1并集    a
        $inter2 = array_intersect($marge1,$array3);//取0 1的并际结果(a)和 2 的交际   b 
        $inter3 = array_intersect($array1,$array2);//取0 1的交际    c
        $marge2 = array_unique((array_merge($inter2,$inter3)));//  b c 并集   一天有两个订单的
        $item['half_data'] = implode(',',$marge2);
        $item['half_data'] = date("d",strtotime($item['half_data']));
        
        $item['maybe_data'] = $dd;
        $item['maybe_data'] = date("d",strtotime($item['maybe_data']));
        // var_dump($item['half_date']);die;
 

        $result[] = $item;
        // var_dump($item);die;
        return $result;

    }

    public function orderInsert($arr){
 
        $Order = Order::model()->find(
            array('condition'=>"account_id=:account_id",
                    'params'=>array(
                        'account_id'=>$arr['account_id']
                        ),
                    )
        ); 
        $model = new Order(); //订单
        $model->order_date = $arr['order_date'];
        $model->order_type = $arr['order_type'];
        $model->order_time = $arr['order_time'];
        $model->planner_id = $arr['planner_id'];
        $model->designer_id = $arr['designer_id'];
        $model->staff_hotel_id=$arr['staff_hotel_id'];
        $model->account_id = $arr['account_id'];
        $model->order_name = $arr['order_name'];
        $model->order_status = $arr['order_status'];
        $model->expect_table = $arr['expect_table'];
        $model->update_time = date('Y-m-d');
        $date = explode(' ',$arr['order_date']);
        $dd = $date['0'];
        $time = $arr['order_time'];
        //把日期和上下午拼接起来
        $limit = $dd.$time;
        // var_dump($limit);
        $Order2 = Order::model()->findAll(
            array('condition'=>"account_id=:account_id",
                    'params'=>array(
                        'account_id'=>$arr['account_id']
                        ),
                    )
        ); 
        $result = array();
        foreach($Order2 as $value){
                // var_dump($value);
            $ss = explode(' ',$value['order_date']);
            $aa = $value['order_time'];
            $result[] = $ss['0'].$aa;
        }
        if(in_array($limit, $result)){
            echo '订单时间有冲突，请选择其他时间';
        }else{
            if($model->save()>0){
                $arr['success']='1';
                return $arr;
            }else{
                $arr['success']='0';
                return $arr;
            }
        }
    }

    public function single_order_price($order_id)
    {

    }

    public function many_order_price($order_list) // $order_list = (1,2,3); 例如
    {
        $result = yii::app()->db->createCommand("select p.id,p.order_id,p.actual_price,p.unit,p.actual_unit_cost,p.actual_service_ratio,o.order_type,o.planner_id,o.designer_id,feast_discount,other_discount,discount_range,cut_price,sp.supplier_type_id from order_product p left join `order` o on p.order_id=o.id left join supplier_product sp on p.product_id=sp.id where p.order_id in ".$order_list." order by order_id");;
        $result = $result->queryAll();
        // print_r(json_encode($result));die;

        $ttt = array(
                "id" => "0",
                "order_id" => "0",
                "actual_price" => "0",
                "unit" => "0",
                "actual_unit_cost" => "0",
                "actual_service_ratio" => "0",
                "order_type" => "0",
                "planner_id" => "0",
                "designer_id" => "0",
                "feast_discount" => "0",
                "other_discount" => "0",
                "discount_range" => "0",
                "cut_price" => "0",
                "supplier_type_id" => "0"
            );
        $result[]=$ttt;

        $order_price = array();
        $tem_order_id = $result[0]['order_id'];
        $tem_order_price = 0;
        foreach ($result as $key => $value) {            
            if($value['order_id'] != $tem_order_id){
                $item = array();
                $item['order_id'] = $tem_order_id;
                $item['total_price'] = $tem_order_price;
                $tem_order_id = $value['order_id'];
                $tem_order_price = 0;
                $order_price[] = $item;
            };
            if($value['supplier_type_id'] == 2){
                $tem_order_price += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1)*(1+$value['actual_service_ratio']*0.01);
                
            }else{
                $t = explode(',', $value['discount_range']);
                $m = 0;
                foreach ($t as $key_t => $value_t) {
                    if($value_t == $value['supplier_type_id']){
                        $m++;
                    };
                };
                // echo $value['order_id'];
                // return $t;
                // echo $m.",".$value['actual_price'].",".$value['unit'].",".$value['other_discount']*0.1."|";
                if($m == 0){
                // echo $m.",".$value['id']."|";
                    
                    $tem_order_price += $value['actual_price']*$value['unit'];
                }else{
                    $tem_order_price += $value['actual_price']*$value['unit']*$value['other_discount']*0.1;
                };
                // echo $tem_order_price.",";
            };
        };
        return $order_price;
    }

    public function get_order_detail($order_id, $token)
    {
        /************************************************************************/
        /************************************************************************/
        /******** CR_ID 构造规则：  ***********************************************/
        /******** 场地布置 10000000          ＋ sp_id     *************************/
        /******** 灯光／音响／视频  30000000   ＋  sp_id    *************************/
        /******** PPT 里的纯“图片” 60000000   ＋   show_id *************************/
        /******** 餐饮零点  90000000          +  sp_id     ************************/
        /******** 服务人员 120000000          ＋ CI_ID     ************************/
        /************************************************************************/
        /************************************************************************/

        // $post = json_decode(file_get_contents('php://input'));

        //取本订单 当前在order_show里的数据
        $result = yii::app()->db->createCommand("select s.id,s.type,s.words,s.theme_words,s.theme_remark,s.wed_style,s.wed_color,s.vi_img_url,s.img_description,subarea.father_area as show_area,i.id as order_show_img_id,i.img_url,s.order_product_id,sp.ref_pic_url,sp.supplier_type_id,sp.id as sp_id,sp.service_product_id,sp.service_product_id,words,s.subarea,area_sort,serp.product_name,serp.unit,serp.description,serp.price as service_product_price,op.remark,op.unit as amount ".
            "from order_show s ".
            "left join order_show_area_subarea subarea on s.subarea=subarea.id ".
            "left join order_show_img i on s.img_id=i.id ".
            "left join order_product op on s.order_product_id=op.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "left join service_product serp on sp.service_product_id=serp.id ".
            "where s.order_id=".$order_id);
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            $service_product_img = ServiceProductImg::model()->findAll(array(
                    'condition' => 'service_product_id=:spi',
                    'params' => array(
                            ':spi' => $value['service_product_id']
                        )
                ));
            $t0 = array();
            if(!empty($service_product_img)){
                $t0 = explode('/', $service_product_img[0]['img_url']);    
            };
            if(isset($t0[1])){
                if($t0[1] == 'upload'){
                    $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$this->add_string_to_url($service_product_img[0]['img_url'], 'xs');
                }else if($t0[1] == 'imgs'){
                    $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.ltrim($service_product_img[0]['img_url'], '.');                    
                }else{
                    $result[$key]['ref_pic_url'] = $service_product_img[0]['img_url'];
                };
            };
                
            $t2 = explode('/', $value['img_url']);
            if(isset($t2[0])){
                if($t2[0] != 'http:'){
                    $result[$key]['img_url'] = 'http://file.cike360.com'.$value['img_url'];    
                };
            };
        };
        //取本订单里的  order_product
        $result1 = yii::app()->db->createCommand("select op.id,op.order_set_id,o.other_discount,o.discount_range,o.feast_discount,ws.category as set_category,ws.name as set_name,st.name,op.actual_price,op.unit as amount,op.actual_unit_cost,op.actual_service_ratio,op.remark,sp.name as product_name,sp.service_product_id,sp.description,sp.ref_pic_url,sp.supplier_type_id,sp.unit,sp.id as sp_id,op.order_set_id,os.subarea,serp.subarea as serp_subare,subarea.father_area as father_area,subarea1.father_area as serp_father_area ".
            "from order_product op ".
            "left join `order` o on op.order_id=o.id ".
            "left join order_show os on op.id=os.order_product_id ".
            "left join order_show_area_subarea subarea on os.subarea=subarea.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "left join service_product serp on sp.service_product_id=serp.id ".
            "left join order_show_area_subarea subarea1 on serp.subarea=subarea1.id ".
            "left join supplier_type st on sp.supplier_type_id=st.id ".
            "left join order_set on op.order_set_id=order_set.id ".
            "left join wedding_set ws on order_set.wedding_set_id=ws.id ".
            "where op.order_id=".$order_id);
        $result1 = $result1->queryAll(); 
        foreach ($result1 as $key => $value) {
            $service_product_img = ServiceProductImg::model()->findAll(array(
                    'condition' => 'service_product_id=:spi',
                    'params' => array(
                            ':spi' => $value['service_product_id']
                        )
                ));
            $t0 = array();
            if(!empty($service_product_img)){
                $t0 = explode('/', $service_product_img[0]['img_url']);    
            };
            
            if(isset($t0[1])){
                if($t0[1] == 'upload'){
                    $t1 = explode('.', $service_product_img[0]['img_url']);
                    if(isset($t1[0]) && isset($t1[1])){
                        $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$t1[0]."_xs.".$t1[1];
                    }else{
                        $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                    };
                }else if($t0[1] == 'imgs'){
                    $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.ltrim($service_product_img[0]['img_url'], '.');                     
                }else{
                    $result1[$key]['ref_pic_url'] = $service_product_img[0]['img_url']; 
                };
            };
            if(isset($t0[0])){
                if($t0[0] == 'http:'){
                    $result1[$key]['ref_pic_url'] = $service_product_img[0]['img_url'];
                };
            };
                
        };

        //取本订单数据
        $order = Order::model()->findByPk($order_id);

        // *******************************************************
        // *****************   构造 订单基本信息    *****************
        // *******************************************************
        $result4 = array();
        if($order['order_type'] == 2){
            $result4 = yii::app()->db->createCommand("select o.id,o.order_name,o.order_type,feast_discount,other_discount,discount_range,cut_price,planner_id,s1.name as planner_name,s1.telephone as planner_phone,designer_id,s2.name as designer_name,s2.telephone as designer_phone,staff_hotel_id,sh.name as hotel_name,groom_name,groom_phone,groom_wechat,groom_qq,bride_name,bride_phone,bride_phone,bride_wechat,bride_qq,contact_name,contact_phone,ow.remark ".
                "from `order` o ".
                "left join staff_hotel sh on o.staff_hotel_id=sh.id ".
                "left join staff s1 on planner_id=s1.id ".
                "left join staff s2 on designer_id=s2.id ".
                "left join order_wedding ow on o.id=ow.order_id ".
                // "left join order_product op on o.id=op.order_id ".
                // "left join supplier_product sp on op.product_id=sp.id ".
                "where o.id=".$order_id/*." and sp.supplier_type_id=16"*/);
            $result4 = $result4->queryAll();
        }else if($order['order_type'] == 1){
            $result4 = yii::app()->db->createCommand("select o.id,o.order_name,o.order_type,feast_discount,other_discount,discount_range,cut_price,planner_id,s1.name as planner_name,s1.telephone as planner_phone,designer_id,s2.name as designer_name,s2.telephone as designer_phone,staff_hotel_id,sh.name as hotel_name,omc.company_name,omc.id as company_id,omcl.id as linkman_id,omcl.name as linkman_name,omcl.telephone as linkman_telephone,om.remark ".
                "from `order` o ".
                "left join staff_hotel sh on o.staff_hotel_id=sh.id ".
                "left join staff s1 on planner_id=s1.id ".
                "left join staff s2 on designer_id=s2.id ".
                "left join order_meeting om on o.id=om.order_id ".
                "left join order_meeting_company omc on om.company_id=omc.id ".
                "left join order_meeting_company_linkman omcl on om.company_linkman_id=omcl.id ".
                // "left join order_product op on o.id=op.order_id ".
                // "left join supplier_product sp on op.product_id=sp.id ".
                "where o.id=".$order_id/*." and sp.supplier_type_id=16"*/);
            $result4 = $result4->queryAll();
        };
            


        //构造统筹师、策划师列表
        $staff = yii::app()->db->createCommand("select * from staff where account_id in (select account_id from staff where id=".$token.")");
        $staff = $staff->queryAll();

        $designer_list = array();
        $planner_list = array();

        foreach ($staff as $key => $value) {
            $t = rtrim($value['department_list'], "]");
            $t = ltrim($t, "[");
            $t = explode(',', $t);
            $d = 0;
            $p = 0;
            foreach ($t as $key_t => $value_t) {
                if($value_t == 2){$p++;};
                if($value_t == 3){$d++;};
            };
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            if($d != 0){
                $designer_list[]=$item;
            };
            if($p != 0){
                $planner_list[]=$item;
            };
        };

        //构造渠道列表
        $result5 = new ProductForm;
        $tuidan_list = $result5->tuidan_list($token);

        //构造折扣范围列表
        $discount = array();
        if(!empty($result4)){
            $discount['feast_discount'] = $result4[0]['feast_discount'];
            $discount['other_discount'] = $result4[0]['other_discount'];
            $discount['list'] = array();
            $descount_list = yii::app()->db->createCommand("select id,name from supplier_type where role=1");
            $descount_list = $descount_list->queryAll();
            if($discount['other_discount'] != 10 && isset($result4[0]['discount_range'])){
                $t=explode(',', $result4[0]['discount_range']);
                foreach ($descount_list as $key => $value) {
                    $item = array();
                    $item['id']=$value['id'];
                    $item['name']=$value['name'];
                    $item['select']=0;
                    $m=0;
                    foreach ($t as $key_t => $value_t) {
                        if($value['id'] == $value_t){
                            $m++;
                        };
                    };
                    if($m!=0){
                        $item['select']=1;
                    };
                    $discount['list'][]=$item;
                };
            }else{
                foreach ($descount_list as $key => $value) {
                    $item = array();
                    $item['id']=$value['id'];
                    $item['name']=$value['name'];
                    $item['select']=1;
                    $discount['list'][]=$item;
                };
            };
        };
        // echo json_encode($discount['list']);die;

        //产品按分类计算总价
        $type_price = array(
                'service' => 0,
                'decorat' => 0,
                'light' => 0,
            );

        foreach ($result1 as $key => $value) {
            if($value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7){
                $type_price['service'] += $value['actual_price']*$value['amount'];
            }else if($value['supplier_type_id'] == 20){
                $type_price['decorat'] += $value['actual_price']*$value['amount'];
            }else if($value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){
                $type_price['light'] += $value['actual_price']*$value['amount'];
            };
        };

        //构造跟单
        $follow = OrderMerchandiser::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $order_id
                    ),
                'order'=>'time'
            ));

        $follow_data = array();
        foreach ($follow as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'type' => $value['type'],
                    'staff_name' => $value['staff_name'],
                    'time' => $value['time'],
                    'order_name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'remarks' => $value['remarks'],
                );
            $follow_data[] = $item;
        };

        //构造收款记录
        $payment = OrderPayment::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $order_id
                    ),
                'order' => 'time'
            ));
        $payment_data = array(
                '0' => array(
                        'total_price' => 0,
                        'list' => array(),
                    ),
                '1' => array(
                        'total_price' => 0,
                        'list' => array(),
                    ),
                '2' => array(
                        'total_price' => 0,
                        'list' => array(),
                    ),
            );
        foreach ($payment as $key => $value) {
            if($value['type'] == 0){
                $payment_data['0']['total_price'] += $value['money'];
                $item=array();
                $item['id'] = $value['id'];
                $item['money'] = $value['money'];
                $item['time'] = $value['time'];
                $payment_data['0']['list'][] = $item;
            }else if($value['type'] == 1){
                $payment_data['1']['total_price'] += $value['money'];
                $item=array();
                $item['id'] = $value['id'];
                $item['money'] = $value['money'];
                $item['time'] = $value['time'];
                $payment_data['1']['list'][] = $item;
            }else if($value['type'] == 2){
                $payment_data['2']['total_price'] += $value['money'];
                $item=array();
                $item['id'] = $value['id'];
                $item['money'] = $value['money'];
                $item['time'] = $value['time'];
                $payment_data['2']['list'][] = $item;
            };
        };  
        // print_r($result3);die;
        $order_data = array();
        if($order['order_type'] == 2){
            $order_data = array(
                    "id"=> $result4[0]['id'] ,
                    "order_name"=> $result4[0]['order_name'] ,
                    "order_type"=> $result4[0]['order_type'] ,
                    "planner_id"=> $result4[0]['planner_id'] ,
                    "planner_name"=> $result4[0]['planner_name'] ,
                    "planner_phone"=> $result4[0]['planner_phone'] ,
                    "designer_id"=> $result4[0]['designer_id'] ,
                    "designer_name"=> $result4[0]['designer_name'] ,
                    "designer_phone"=> $result4[0]['designer_phone'] ,
                    "staff_hotel_id"=> $result4[0]['staff_hotel_id'] ,
                    "hotel_name"=> $result4[0]['hotel_name'] ,
                    "order_place"=> $order['order_place'] ,
                    "groom_name"=> $result4[0]['groom_name'] ,
                    "groom_phone"=> $result4[0]['groom_phone'] ,
                    "groom_wechat"=> $result4[0]['groom_wechat'] ,
                    "groom_qq"=> $result4[0]['groom_qq'] ,
                    "bride_name"=> $result4[0]['bride_name'] ,
                    "bride_phone"=> $result4[0]['bride_phone'] ,
                    "bride_wechat"=> $result4[0]['bride_wechat'] ,
                    "bride_qq"=> $result4[0]['bride_qq'] ,
                    "contact_name"=> $result4[0]['contact_name'] ,
                    "contact_phone"=> $result4[0]['contact_phone'],
                    'company_name' => '',
                    'company_id' => '',
                    'contact_id' => '',
                    'remark' => $result4[0]['remark'],
                    'designer_list'=> $designer_list,
                    'planner_list'=> $planner_list,
                    'tuidan_list'=> $tuidan_list,
                    'discount'=> $discount,
                    'cut_price'=> $result4[0]['cut_price'],
                    'type_price'=> $type_price,
                    'follow_data'=> $follow_data,
                    'payment_data'=> $payment_data,
                    'guest_amount'=> $order['guest_amount']
                ); 
        }else{
            $order_data = array(
                    "id"=> $result4[0]['id'] ,
                    "order_name"=> $result4[0]['order_name'] ,
                    "order_type"=> $result4[0]['order_type'] ,
                    "planner_id"=> $result4[0]['planner_id'] ,
                    "planner_name"=> $result4[0]['planner_name'] ,
                    "planner_phone"=> $result4[0]['planner_phone'] ,
                    "designer_id"=> $result4[0]['designer_id'] ,
                    "designer_name"=> $result4[0]['designer_name'] ,
                    "designer_phone"=> $result4[0]['designer_phone'] ,
                    "staff_hotel_id"=> $result4[0]['staff_hotel_id'] ,
                    "hotel_name"=> $result4[0]['hotel_name'] ,
                    "order_place"=> $order['order_place'] ,
                    "groom_name"=> '' ,
                    "groom_phone"=> '' ,
                    "groom_wechat"=> '' ,
                    "groom_qq"=> '' ,
                    "bride_name"=> '' ,
                    "bride_phone"=> '' ,
                    "bride_wechat"=> '' ,
                    "bride_qq"=> '' ,
                    'company_name' => $result4[0]['company_name'],
                    'company_id' => $result4[0]['company_id'],
                    'contact_id' => $result4[0]['linkman_id'],
                    'contact_name' => $result4[0]['linkman_name'],
                    'contact_phone' => $result4[0]['linkman_telephone'],
                    'remark' => $result4[0]['remark'],
                    'designer_list'=> $designer_list,
                    'planner_list'=> $planner_list,
                    'tuidan_list'=> $tuidan_list,
                    'discount'=> $discount,
                    'cut_price'=> $result4[0]['cut_price'],
                    'type_price'=> $type_price,
                    'follow_data'=> $follow_data,
                    'payment_data'=> $payment_data,
                    'guest_amount'=> $order['guest_amount']
                ); 
        };
                   
        $t=explode(' ', $order['order_date']);
        $order_data['order_date']=$t[0];
        $result3 = yii::app()->db->createCommand("select sp.id,sp.name from order_product op left join supplier_product sp on product_id=sp.id where op.order_id=".$order_id." and sp.supplier_type_id=16");
        $result3 = $result3->queryAll();
        if(!empty($result3)){
            $order_data['tuidan_id']=$result3[0]['id'];
            $order_data['tuidan_name']=$result3[0]['name'];
        }else{
            $order_data['tuidan_id']="";
            $order_data['tuidan_name']="";
        };


        // *******************************************************
        // ********************   构造 PPT    ********************
        // *******************************************************

        //比较 s | p
        $order_show_list = array();
        foreach ($result as $key => $value) {
            if($value['supplier_type_id'] != 2){    //去掉餐饮部分
                $item=array();
                $item['show_id']=$value['id'];
                $item['show_type']=$value['type'];
                $item['show_area']=$value['show_area'];
                $item['subarea']=$value['subarea'];
                $item['area_sort']=$value['area_sort'];
                $item['supplier_type_id']=$value['supplier_type_id'];
                $item['product_name'] = $value['product_name'];
                $item['unit'] = $value['unit'];
                $item['remark'] = $value['remark'];
                $item['amount'] = $value['amount'];
                $item['vi_img_url'] = $value['vi_img_url'];
                $item['description'] = $value['description'];
                $item['service_product_price'] = $value['service_product_price'];
                $item['CR_ID'] = 0;
                if($value['type'] == 0){
                    $item['words_id'] = $value['words'];
                    $item['theme_words'] = $value['theme_words'];
                    $item['theme_remark'] = $value['theme_remark'];
                    $item['wed_style'] = $value['wed_style'];
                    $item['wed_color'] = $value['wed_color'];
                    $item['product_id']=0;
                }else if($value['type'] == 1){
                    $item['img_id'] = $value['order_show_img_id'];
                    $item['show_data']=$value['img_url'];
                    $item['img_description']=$value['img_description'];
                    $item['product_id']=0;
                    $item['CR_ID'] = 60000000 + $value['id'];
                }else if($value['type'] == 2){
                    $item['show_data']=$value['ref_pic_url'];
                    $item['product_id']=$value['order_product_id'];
                    if($value['supplier_type_id'] == 20){
                        $item['CR_ID'] = 10000000 + $value['sp_id'];
                    }else if($value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){
                        $item['CR_ID'] = 30000000 + $value['sp_id'];
                    }else if($value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7){
                        $CI_Type = 0;
                        if($value['supplier_type_id'] == 3){$CI_Type=6;};
                        if($value['supplier_type_id'] == 4){$CI_Type=13;};
                        if($value['supplier_type_id'] == 5){$CI_Type=14;};
                        if($value['supplier_type_id'] == 6){$CI_Type=15;};
                        if($value['supplier_type_id'] == 7){$CI_Type=21;};
                        $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value['sp_id']);
                        $result7 = $result7->queryAll();
                        if(isset($result7[0])){
                            $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                        };
                    };
                }else if($value['type'] == 3){
                    $color = OrderShowIdeaColor::model()->findByPk($value['wed_color']);
                    $item['color_id'] = $value['wed_color'];
                    $item['color_name'] = $color['name'];
                    $item['color_remark'] = $color['remark'];
                    $item['main_color'] = $color['main_color'];
                    $item['second_color'] = $color['second_color'];
                    $item['third_color'] = $color['third_color'];
                    $item['product_id'] = 0;
                }
                $order_show_list[]=$item;
            };
        };

        $result6 = yii::app()->db->createCommand("select * ".
            "from order_show where order_id=".$order_id." and subarea=0 ".
            "order by area_sort DESC ");
        $non_area_show = $result6->queryAll();
        $i=1;
        if(!empty($non_area_show)){
            $i=$non_area_show[0]['area_sort']+1;
        };
        foreach ($result1 as $key => $value) {
            if($value['supplier_type_id'] != 2 && $value['supplier_type_id'] != 16){
                $t=0;
                foreach ($order_show_list as $key_s => $value_s) {
                    if($value['id'] == $value_s['product_id']){
                        $t++;
                    };
                };
                if($t == 0 && $value['supplier_type_id'] !=2){
                    $admin=new OrderShow;
                    $admin->type=2;
                    $admin->order_product_id=$value['id'];
                    $admin->order_id=$order_id;
                    $admin->subarea=0;
                    $admin->area_sort= $i;
                    $admin->update_time=date('y-m-d h:i:s',time());
                    $admin->save();
                    $show_id = $admin->attributes['id'];

                    $item=array();
                    $item['show_id']=$show_id;
                    $item['show_type']=2;
                    $item['subarea']=0;
                    $item['area_sort']=$i++;
                    $item['show_data']=$value['ref_pic_url'];
                    $item['product_id']=$value['id'];
                    $item['supplier_type_id']=$value['supplier_type_id'];
                    $item['CR_ID']=0;                             //  没有区域的产品，在PPT部分，加了一个CR_ID＝0
                    $order_show_list[]=$item;
                };
            };
        };

        // echo json_encode($order_show_list);die;

        $order_show = array();
        $area = OrderShowArea::model()->findAll(array(
                'order' => 'sort'
            ));
        $subarea = OrderShowAreaSubarea::model()->findAll(array(
                'order' => 'sort'
            ));

        foreach ($area as $key => $value) {
            if($value['id'] != 8){
                $tem=array();
                $tem['area_id'] = $value['id'];
                $tem['area_name'] = $value['name'];
                $tem['eng_name'] = $value['eng_name'];
                $tem['description'] = $value['description'];
                $tem['subarea'] = array();
                $tem['subarea_empty'] = true;
                $tem['show_img_empty'] = true;
                $tem['show_img_list'] = array();

                //构造区域效果图
                foreach ($order_show_list as $key_si => $value_si) {
                    if ($value['id'] == 3) {
                        if($value_si['subarea'] == 93){
                            $tem['show_img_empty'] = false;
                            $item = array();
                            $item['show_id'] = $value_si['show_id'];
                            $item['img_id'] = $value_si['img_id'];
                            $item['img_url'] = $value_si['show_data'];
                            $item['img_description'] = $value_si['img_description'];
                            $tem['show_img_list'][] = $item;
                        };
                    };
                    if ($value['id'] == 4) {
                        if($value_si['subarea'] == 15){
                            $tem['show_img_empty'] = false;
                            $item = array();
                            $item['show_id'] = $value_si['show_id'];
                            $item['img_id'] = $value_si['img_id'];
                            $item['img_url'] = $value_si['show_data'];
                            $item['img_description'] = $value_si['img_description'];
                            $tem['show_img_list'][] = $item;
                        };
                    };
                    if ($value['id'] == 5) {
                        if($value_si['subarea'] == 4){
                            $tem['show_img_empty'] = false;
                            $item = array();
                            $item['show_id'] = $value_si['show_id'];
                            $item['img_id'] = $value_si['img_id'];
                            $item['img_url'] = $value_si['show_data'];
                            $item['img_description'] = $value_si['img_description'];
                            $tem['show_img_list'][] = $item;
                        };
                    };
                };
                
                
                foreach ($subarea as $key1 => $value1) {
                    if($value1['father_area'] == $value['id']){
                        $item = array();
                        $item['id'] = $value1['id'];
                        $item['name'] = $value1['name'];
                        $item['eng_name'] = $value1['eng_name'];
                        $item['single'] = true;
                        $item['even'] = false;  
                        $item['empty'] = false;
                        $item['data'] = array();
                        $tem['subarea'][] = $item;
                    };
                };        

                foreach ($tem['subarea'] as $key_sub => $value_sub) {
                    foreach ($order_show_list as $key_l => $value_l) {
                        if($value_l['subarea'] == $value_sub['id']){
                            //构造区域产品
                            $item=array();
                            $item['show_id']=$value_l['show_id'];
                            $item['data_type']=$value_l['show_type'];
                            $item['subarea'] = $value_l['subarea'];
                            if($value_l['show_type'] == 0){
                                $item['words_id'] = $value_l['words_id'];
                                $item['theme_words'] = $value_l['theme_words'];
                                $item['theme_remark'] = $value_l['theme_remark'];
                            }else if($value_l['subarea'] == 2){
                                // $item['wed_style'] = $value_l['wed_style'];
                            }else if($value_l['subarea'] == 3){
                                $item['color_id'] = $value_l['color_id'];
                                $item['color_name'] = $value_l['color_name'];
                                $item['color_remark'] = $value_l['color_remark'];
                                $item['main_color'] = $value_l['main_color'];
                                $item['second_color'] = $value_l['second_color'];
                                $item['third_color'] = $value_l['third_color'];
                            }else{
                                $item['show_data']=$value_l['show_data'];
                            };
                            $item['product_id']=$value_l['product_id'];
                            $item['product_name']=$value_l['product_name'];
                            $item['unit']=$value_l['unit'];
                            $item['description']=$value_l['description'];
                            $item['remark']=$value_l['remark'];
                            $item['amount']=$value_l['amount'];
                            $item['vi_img_url']=$value_l['vi_img_url'];
                            $item['sort']=$value_l['area_sort'];
                            $item['service_product_price'] = $value_l['service_product_price'];
                            $item['CR_ID']=$value_l['CR_ID'];
                            $tem['subarea'][$key_sub]['data'][]=$item;
                        };
                    };
                    if(!empty($tem['subarea']['data'])){
                        $num1 = array();
                        foreach ( $tem['subarea']['data'] as $key2 => $value2 ){
                            $num1[$key2] = $value2['sort'];
                        };
                        // print_r($num1);echo ',';print_r($tem['data']);echo '|';
                        array_multisort($num1, SORT_ASC, $tem['subarea']['data']);
                    };
                };
                $t = 0;
                foreach ($tem['subarea'] as $key3 => $value3) {
                    if(count($value3['data']) != 0){
                        $tem['subarea_empty'] = false;        
                    };
                };
                
                
                $order_show[]=$tem;
            };
        };

        $tem=array();
        $tem['area_id']=0;
        $tem['area_name']='待分配产品';
        $tem['eng_name']='Distributing Products';
        $tem['description']="";
        $tem['subarea']=array(
                0 =>array(
                        'id' => 0,
                        'name' => '待分配产品',
                        'eng_name' => 'Distributing Products',
                        'single' => true,
                        'even' => false,
                        'empty' => false,
                        'data' => array()
                    )
            );
        $tem['subarea_empty']=true; 
        foreach ($order_show_list as $key_l => $value_l) {
            if($value_l['subarea'] == 0 && $value_l['supplier_type_id'] != 2 && $value_l['supplier_type_id'] != 16){
                $item=array();
                $item['show_id']=$value_l['show_id'];
                $item['data_type']=$value_l['show_type'];
                $item['show_data']=$value_l['show_data'];
                $item['product_id']=$value_l['product_id'];
                $item['sort']=$value_l['area_sort'];
                $item['CR_ID']=$value_l['CR_ID'];
                $tem['subarea'][0]['data'][]=$item;
                $tem['subarea_empty']=false; 
            };
        };
        if(!empty($tem['data'])){
            $num1 = array();
            foreach ( $tem['subarea'][0]['data'] as $key => $value ){
                $num1[$key] = $value ['sort'];
            };
            array_multisort($num1, SORT_ASC, $tem['subarea'][0]['data']);    
        };
        
        
        $order_show[]=$tem;

        // *******************************************************
        // ********************   构造报价单    ********************
        // *******************************************************

        //有区域产品，按区域分组，并加总，计算出总价、折后总价；
        $area_product = array();
        $area = OrderShowArea::model()->findAll(array(
                'order' => 'sort'
            ));
        $discount_range=explode(',', $order['discount_range']);
        foreach ($area as $key => $value) {
            if($value['type'] != 0 && $value['type'] != 10 && $value['type'] !=11 && $value['type'] !=12){
                $tem=array();
                $tem['area_id']=$value['id'];
                $tem['area_name']=$value['name'];
                $tem['area_img'] = '';
                $tem['product_list'] = array();
                $tem['area_total'] = 0;
                $tem['discount_total'] = 0;
                $tem['total_cost'] = 0;
                foreach ($result1 as $key_p => $value_p) {
                    if($value['id'] == $value_p['father_area'] || $value['id'] == $value_p['serp_father_area']){
                        $item=array();
                        $item['product_id']=$value_p['id'];
                        $item['product_name']=$value_p['product_name'];
                        $item['description']=$value_p['description'];
                        $item['ref_pic_url']=$value_p['ref_pic_url'];
                        $item['price']=$value_p['actual_price'];
                        $item['amount']=$value_p['amount'];
                        $item['unit']=$value_p['unit'];
                        $item['cost']=$value_p['actual_unit_cost'];
                        $item['remark']=$value_p['remark'];
                        $item['set']="";

                        //构造 CR_ID  top
                        if($value_p['supplier_type_id'] == 20){
                            $item['CR_ID'] = 10000000 + $value_p['sp_id'];
                        }else if($value_p['supplier_type_id'] == 8 || $value_p['supplier_type_id'] == 9 || $value_p['supplier_type_id'] == 23){
                            $item['CR_ID'] = 30000000 + $value_p['sp_id'];
                        }else if($value_p['supplier_type_id'] == 3 || $value_p['supplier_type_id'] == 4 || $value_p['supplier_type_id'] == 5 || $value_p['supplier_type_id'] == 6 || $value_p['supplier_type_id'] == 7){
                            $CI_Type = 0;
                            if($value_p['supplier_type_id'] == 3){$CI_Type=6;};
                            if($value_p['supplier_type_id'] == 4){$CI_Type=13;};
                            if($value_p['supplier_type_id'] == 5){$CI_Type=14;};
                            if($value_p['supplier_type_id'] == 6){$CI_Type=15;};
                            if($value_p['supplier_type_id'] == 7){$CI_Type=21;};
                            $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value_p['sp_id']);
                            $result7 = $result7->queryAll();
                            // print_r($result7);die;
                            if(isset($result7[0])){
                                    $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                                };
                        }
                        //构造 CR_ID  end


                        $t = 0;
                        foreach ($discount_range as $key_r => $value_r) {
                            if($value_r == $value_p['supplier_type_id']){
                                $t++;
                            };
                        };
                        if($t!=0){
                            $item['discount'] = $order['other_discount']*0.1;
                            $tem['discount_total'] += $item['price']*$item['amount']*$order['other_discount']*0.1;
                        }else{
                            $item['discount'] = 1;
                            $tem['discount_total'] += $item['price']*$item['amount'];
                        };

                        if($value_p['order_set_id'] != 0){
                            $item['set']="套系产品";
                        };
                        $tem['product_list'][]=$item;
                        $tem['area_total'] += $item['price']*$item['amount'];
                        $tem['total_cost'] += $item['cost']*$item['amount'];
                    };
                };
                $area_product[] = $tem;
            };
        };

        //无区域产品，列出来
        $non_area_product = array();
        foreach ($result1 as $key => $value) {
            if($value['subarea'] == 0 && $value['supplier_type_id']!=2 && $value['supplier_type_id'] != 16){
                $discount_range = rtrim($value['discount_range'], ",");
                $discount_range = explode(',', $discount_range);
                $r = 0;
                foreach ($discount_range as $key_dr => $value_dr) {
                    if($value_dr == $value['supplier_type_id']){
                        $r++;
                    };
                };
                $item=array();
                $item['product_id']=$value['id'];
                $item['product_name']=$value['product_name'];
                $item['description']=$value['description'];
                $item['ref_pic_url']=$value['ref_pic_url'];
                $item['price']=$value['actual_price'];
                $item['amount']=$value['amount'];
                $item['unit']=$value['unit'];
                $item['cost']=$value['actual_unit_cost'];
                $item['remark']=$value['remark'];
                $item['set']="";

                //构造 CR_ID  top
                if($value_p['supplier_type_id'] == 20){
                    $item['CR_ID'] = 10000000 + $value_p['sp_id'];
                }else if($value_p['supplier_type_id'] == 8 || $value_p['supplier_type_id'] == 9 || $value_p['supplier_type_id'] == 23){
                    $item['CR_ID'] = 30000000 + $value_p['sp_id'];
                }else if($value_p['supplier_type_id'] == 3 || $value_p['supplier_type_id'] == 4 || $value_p['supplier_type_id'] == 5 || $value_p['supplier_type_id'] == 6 || $value_p['supplier_type_id'] == 7){
                    $CI_Type = 0;
                    if($value_p['supplier_type_id'] == 3){$CI_Type=6;};
                    if($value_p['supplier_type_id'] == 4){$CI_Type=13;};
                    if($value_p['supplier_type_id'] == 5){$CI_Type=14;};
                    if($value_p['supplier_type_id'] == 6){$CI_Type=15;};
                    if($value_p['supplier_type_id'] == 7){$CI_Type=21;};
                    $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value_p['sp_id']);
                    $result7 = $result7->queryAll();
                    // print_r($result7);die;
                    if(isset($result7[0])){
                        $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                    };
                }
                //构造 CR_ID  end

                if($r == 0){
                    $item['discount'] = 1;
                }else{
                    $item['discount'] = $value['other_discount']*0.1;
                };
                if($value['order_set_id'] != 0){
                    $item['set']="套系产品";
                };
                $non_area_product[]=$item;
            };
        };
        $non_area_total = 0;
        $non_area_cost = 0;
        foreach ($non_area_product as $key => $value) {
            $non_area_total += $value['price'] * $value['amount'] * $value['discount'];
            $non_area_cost += $value['cost'] * $value['amount'];
        };

        //取套餐数据（婚宴、婚礼）
        $result2 = yii::app()->db->createCommand("select os.id,os.order_id,os.amount,os.actual_service_ratio,os.remark,ws.id as ws_id,ws.name,ws.category ".
            " from order_set os left join wedding_set ws on wedding_set_id=ws.id ".
            " where os.order_id=".$order_id);
        $result2 = $result2->queryAll();

        $set_data = array();
        $set_data['feast']=array();
        $set_data['other']=array();
        foreach ($result2 as $key_s => $value_s) {
            $tem=array();
            $tem['order_set_id']=$value_s['id'];
            $tem['set_name']=$value_s['name'];
            $tem['amount']=$value_s['amount'];
            $tem['actual_service_ratio']=$value_s['actual_service_ratio'];
            $tem['remark']=$value_s['remark'];
            $tem['set_total']=0;
            $tem['discount_total']=0;
            $tem['total_cost']=0;
            $tem['product_list']=array();
            foreach ($result1 as $key_p => $value_p) {
                if($value_p['order_set_id'] == $value_s['id']){
                    $item=array();
                    $item['product_id']=$value_p['id'];
                    $item['product_name']=$value_p['product_name'];
                    $item['description']=$value_p['description'];
                    $item['ref_pic_url']=$value_p['ref_pic_url'];
                    $item['price']=$value_p['actual_price'];
                    $item['amount']=$value_p['amount'];
                    $item['unit']=$value_p['unit'];
                    $item['cost']=$value_p['actual_unit_cost'];
                    $item['CR_ID']=90000000 + $value_p['sp_id'];
                    if($value_p['supplier_type_id'] == 2){
                        $item['discount'] = $value_p['feast_discount'];
                    }else{
                        $t=explode(',', $value_p['discount_range']);
                        $m=0;
                        foreach ($t as $key_tm => $value_tm) {
                            if($value_tm == $value_p['supplier_type_id']){
                                $m++;
                            };
                        };
                        if($m == 0){
                            $item['discount'] = 1;
                        }else{
                            $item['discount'] = $value_p['other_discount']*0.1;
                        };
                    };
                    $tem['product_list'][]=$item;
                    if($value_s['category'] == 3 || $value_s['category'] == 4){
                        $tem['set_total'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01);
                        $tem['discount_total'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01)*$order['feast_discount']*0.1;
                    }else{
                        $tem['set_total'] += $value_p['actual_price']*$value_p['amount'];
                        $tem['discount_total'] += $value_p['actual_price']*$value_p['amount']*$item['discount'];
                    };
                };
            };
            foreach ($result1 as $key_p => $value_p) {
                if($value_p['supplier_type_id'] == 2 && $value_p['order_set_id'] == 0){
                    $item=array();
                    $item['product_id']=$value_p['id'];
                    $item['product_name']=$value_p['product_name'];
                    $item['description']=$value_p['description'];
                    $item['ref_pic_url']=$value_p['ref_pic_url'];
                    $item['price']=$value_p['actual_price'];
                    $item['amount']=$value_p['amount'];
                    $item['unit']=$value_p['unit'];
                    $item['CR_ID']=90000000 + $value_p['sp_id'];
                    $item['cost']=$value_p['actual_unit_cost'];
                    if($value_s['category'] == 3 || $value_s['category'] == 4){
                        $tem['product_list'][]=$item;
                        $tem['set_total'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01);
                        $tem['discount_total'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01)*$order['feast_discount']*0.1;
                    };
                };
            };
            foreach ($tem['product_list'] as $key => $value) {
                $tem['total_cost'] += $value['cost']*$value['amount'];
            };
            if($value_s['category']==3 || $value_s['category']==4){
                $set_data['feast'][]=$tem;
            }else{
                $set_data['other'][]=$tem;
            };
        };

        // *******************************************************
        // ***************   构造 email 列表    *******************
        // *******************************************************

        $email = StaffEmail::model()->findAll(array(
                'condition' => 'staff_id=:staff_id',
                'params' => array(
                        ':staff_id' => $token,
                    )
            ));

        $email_list = array();
        foreach ($email as $key => $value) {
            $item=array();
            $item['id'] = $value['id'];
            $item['email'] = $value['email'];
            $email_list[] = $item;
        };
        // print_r($email_list);die;


        // // *******************************************************
        // // ***************   构造 我的订单 列表    *******************
        // // *******************************************************

        // $result = yii::app()->db->createCommand("select * from `order` where designer_id=".$_GET['token']." or planner_id=".$_GET['token']);
        // $result = $result->queryAll();

        // // print_r($result);die;

        // $order_doing = array();
        // $order_done = array();
        // $order_nearest = array(
        //         'name' => '无订单',
        //         'to_date' => 0,
        //         'order_date' => ""
        //     );

        // foreach ($result as $key => $value) {
        //     $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
        //     $zero2=strtotime ($value['order_date']);  //订单创建时间
        //     $item = array(
        //             'id' => $value['id'],
        //             'name' => $value['order_name'],
        //             'order_date' => $value['order_date'],
        //             'to_date' => 0,
        //         );
        //     if($zero2 >= $zero1){
        //         $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
        //         $order_doing[]=$item;
        //     }else{
        //         $item['to_date'] = ceil(($zero1-$zero2)/86400); //60s*60min*24h
        //         $order_done[]=$item;
        //     }
        // }

        // // print_r($order_done);die;

        // $to_date = array();
        // foreach ( $order_doing as $key => $row ){
        //     $to_date[] = $row ['to_date'];
        // };

        // array_multisort($to_date, SORT_ASC, $order_doing);


        // $to_date1 = array();
        // foreach ( $order_done as $key => $row ){
        //     $to_date1[] = $row['to_date'];
        // };

        // array_multisort($to_date1, SORT_ASC, $order_done);

        // if(!empty($order_doing)){
        //     $t = explode(' ', $order_doing[0]['order_date']);
        //     $name = "";
        //     $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
        //     $zero2=strtotime ($order_doing[0]['order_date']);  //订单创建时间

        //     foreach ($order_doing as $key => $value) {
        //         $order_date = explode(' ', $value['order_date']);
        //         if($order_date[0] == $t[0]){
        //             $name .= $value['name'].",";
        //         };
        //     };
        //     $order_nearest['name'] = rtrim($name, ",");
        //     $order_nearest['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
        //     $order_nearest['order_date'] = $t[0];
        // };  

        // $order_data2 = array(
        //         'doing' => $order_doing,
        //         'done' => $order_done,
        //         'nearest' => $order_nearest,
        //     );

        $order_total = array(
            'feast' => array(),
            'other' => array(),
            'areap' => array(),
            'non_area' => array(),
            'total' => array('price' => 0, 'cost' => 0, 'profit' => 0)
        );
        foreach ($set_data['feast'] as $key => $value) {
            $item=array();
            $item['id'] = $value['order_set_id'];
            $item['name'] = $value['set_name'];
            $item['zheqian_total'] = $value['set_total'];
            $item['zhehou_total'] = $value['discount_total'];
            $item['unit'] = $value['amount'];
            $item['cost'] = $value['total_cost'];
            $order_total['feast'][] = $item;
            $order_total['total']['price'] += $item['zhehou_total'];
            $order_total['total']['cost'] += $item['cost'];
        };
        foreach ($set_data['other'] as $key => $value) {
            $item=array();
            $item['id'] = $value['order_set_id'];
            $item['name'] = $value['set_name'];
            $item['zheqian_total'] = $value['set_total'];
            $item['zhehou_total'] = $value['discount_total'];
            $item['unit'] = $value['amount'];
            $item['cost'] = $value['total_cost'];
            $order_total['other'][] = $item;
        };
        foreach ($area_product as $key => $value) {
            $item=array();
            $item['id'] = $value['area_id'];
            $item['name'] = $value['area_name'];
            $item['zheqian_total'] = $value['area_total'];
            $item['zhehou_total'] = $value['discount_total'];
            $item['cost'] = $value['total_cost'];
            $order_total['areap'][] = $item;
            $order_total['total']['price'] += $item['zhehou_total'];
            $order_total['total']['cost'] += $item['cost'];
        };
        $order_total['non_area']['zhehou_total'] = $non_area_total;
        $order_total['non_area']['cost'] = $non_area_cost;
        $order_total['total']['price'] += $non_area_total;
        $order_total['total']['cost'] += $non_area_cost;
        $order_total['total']['profit'] += $order_total['total']['price']-$order_total['total']['cost'];
        $order_total['total']['price'] -= $order['cut_price'];

        /*********************  当前order的model  *********************/
        $model_select = OrderModelSelect::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $order_id
                    )
            ));
        $cur_order_model = 0;
        if(!empty($model_select)){
            $cur_order_model = $model_select[0]['model_id'];
        };

        /***********************判断 area－subarea 是否为偶数元素*********************/
        foreach ($order_show as $key => $value) {
            foreach ($value['subarea'] as $key1 => $value1) {
                $order_show[$key]['subarea'][$key1]['empty'] = count($value1['data']) ;
                if(count($value1['data']) != 1){
                    $order_show[$key]['subarea'][$key1]['single'] = false;
                    if(count($value1['data'])%2 == 0){ $order_show[$key]['subarea'][$key1]['even'] = true;};
                };
            };
        };


        $order_detail = array();
        $order_detail['order_data'] = $order_data;
        $order_detail['order_show'] = $order_show;
        $order_detail['area_product'] = $area_product;
        $order_detail['non_area_product'] = $non_area_product;
        $order_detail['set_data'] = $set_data;
        $order_detail['email_list'] = $email_list;
        $order_detail['order_total'] = $order_total;
        $order_detail['cur_order_model'] = $cur_order_model;

        return $order_detail;
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
}

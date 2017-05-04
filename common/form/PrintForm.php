<?php
class CMailFile
{ 

var $subject; 
var $addr_to; 
var $text_body; 
var $text_encoded; 
var $mime_headers; 
var $mime_boundary = "--==================_846811060==_"; 
var $smtp_headers; 

function CMailFile($subject,$to,$from,$msg,$filename,$downfilename,$mimetype = "application/octet-stream",$mime_filename = false) { 
$this->subject = $subject; 
$this->addr_to = $to; 
$this->smtp_headers = $this->write_smtpheaders($from); 
$this->text_body = $this->write_body($msg); 
$this->text_encoded = $this->attach_file($filename,$downfilename,$mimetype,$mime_filename); 
$this->mime_headers = $this->write_mimeheaders($filename, $mime_filename); 
} 

function attach_file($filename,$downfilename,$mimetype,$mime_filename) { 
$encoded = $this->encode_file($filename); 
if ($mime_filename) $filename = $mime_filename; 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-type: " . $mimetype . "; name=\"$filename\";\n"; 
$out = $out . "Content-Transfer-Encoding: base64\n"; 
$out = $out . "Content-disposition: attachment; filename=\"$downfilename\"\n\n"; 
$out = $out . $encoded . "\n"; 
$out = $out . "--" . $this->mime_boundary . "--" . "\n"; 
return $out; 
} 

function encode_file($sourcefile) { 
if (is_readable($sourcefile)) { 
$fd = fopen($sourcefile, "r"); 
$contents = fread($fd, filesize($sourcefile)); 
$encoded = chunk_split(base64_encode($contents)); 
fclose($fd); 
}
return $encoded; 
} 

function sendfile() { 
$headers = $this->smtp_headers . $this->mime_headers; 
$message = $this->text_body . $this->text_encoded; 
mail($this->addr_to,$this->subject,$message,$headers); 
} 

function write_body($msgtext) { 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-Type: text/plain; charset=\"us-ascii\"\n\n"; 
$out = $out . $msgtext . "\n"; 
return $out; 
} 

function write_mimeheaders($filename, $mime_filename) { 
if ($mime_filename) $filename = $mime_filename; 
$out = "MIME-version: 1.0\n"; 
$out = $out . "Content-type: multipart/mixed; "; 
$out = $out . "boundary=\"$this->mime_boundary\"\n"; 
$out = $out . "Content-transfer-encoding: 7BIT\n"; 
$out = $out . "X-attachments: $filename;\n\n"; 
return $out; 
} 

function write_smtpheaders($addr_from) { 
$out = "From: $addr_from\n"; 
$out = $out . "Reply-To: $addr_from\n"; 
$out = $out . "X-Mailer: PHP3\n"; 
$out = $out . "X-Sender: $addr_from\n"; 
return $out; 
} 
} 

/**
 * Class CompanyForm
 * Protect info
 */
class PrintForm extends InitForm
{
    public function get_bill($order_id)
    {
        //取本订单 当前在order_show里的数据
        $result = yii::app()->db->createCommand("select s.id,s.type,subarea.father_area as show_area,i.img_url,s.order_product_id,sp.ref_pic_url,sp.supplier_type_id,sp.id as sp_id,words,subarea,area_sort ".
            "from order_show s ".
            "left join order_show_area_subarea subarea on s.subarea=subarea.id ".
            "left join order_show_img i on s.img_id=i.id ".
            "left join order_product op on s.order_product_id=op.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "where s.order_id=".$order_id);
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            $t0 = explode('/', $value['ref_pic_url']);
            if(isset($t0[1])){
                if($t0[1] == 'upload'){
                    $t1 = explode('.', $value['ref_pic_url']);
                    if(isset($t1[0]) && isset($t1[1])){
                        $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$t1[0]."_sm.".$t1[1];
                    }else{
                        $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                    };
                }else if($t0[1] == 'imgs'){
                    $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                };
            };
                
            $t2 = explode('.', $value['img_url']);
            $result[$key]['img_url'] = 'http://file.cike360.com'.$value['img_url'];
        };

        //取本订单里的  order_product
        $result1 = yii::app()->db->createCommand("select op.id,op.order_set_id,o.other_discount,o.discount_range,o.feast_discount,ws.category as set_category,ws.name as set_name,st.name,op.actual_price,op.unit as amount,op.actual_unit_cost,op.actual_service_ratio,sp.name as product_name,sp.description,sp.ref_pic_url,sp.supplier_type_id,sp.unit,sp.id as sp_id,op.order_set_id,os.subarea,subarea.father_area as father_area ".
            "from order_product op ".
            "left join `order` o on op.order_id=o.id ".
            "left join order_show os on op.id=os.order_product_id ".
            "left join order_show_area_subarea subarea on os.subarea=subarea.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "left join supplier_type st on sp.supplier_type_id=st.id ".
            "left join order_set on op.order_set_id=order_set.id ".
            "left join wedding_set ws on order_set.wedding_set_id=ws.id ".
            "where op.order_id=".$order_id);
        $result1 = $result1->queryAll(); 

        foreach ($result1 as $key => $value) {
            $t0 = explode('/', $value['ref_pic_url']);
            if(isset($t0[1])){
                if($t0[1] == 'upload'){
                    $t1 = explode('.', $value['ref_pic_url']);
                    if(isset($t1[0]) && isset($t1[1])){
                        $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$t1[0]."_sm.".$t1[1];
                    }else{
                        $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                    };
                }else if($t0[1] == 'imgs'){
                    $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];                    
                };
            };
                
        };

        // echo json_encode($result1);die;

        //取本订单数据
        $order = Order::model()->findByPk($order_id);
        // *******************************************************
        // *****************   构造 订单基本信息    *****************
        // *******************************************************
        
        $result4 = yii::app()->db->createCommand("select o.id,o.order_name,feast_discount,other_discount,discount_range,cut_price,planner_id,s1.name as planner_name,s1.telephone as planner_phone,designer_id,s2.name as designer_name,s2.telephone as designer_phone,staff_hotel_id,sh.name as hotel_name,groom_name,groom_phone,groom_wechat,groom_qq,bride_name,bride_phone,bride_phone,bride_wechat,bride_qq,contact_name,contact_phone ".
            "from `order` o ".
            "left join staff_hotel sh on o.staff_hotel_id=sh.id ".
            "left join staff s1 on planner_id=s1.id ".
            "left join staff s2 on designer_id=s2.id ".
            "left join order_wedding ow on o.id=ow.order_id ".
            // "left join order_product op on o.id=op.order_id ".
            // "left join supplier_product sp on op.product_id=sp.id ".
            "where o.id=".$_GET['order_id']/*." and sp.supplier_type_id=16"*/);
        $result4 = $result4->queryAll();
        // print_r($result4);die;


        //构造统筹师、策划师列表
        $staff = yii::app()->db->createCommand("select * from staff where account_id in (select account_id from staff where id=".$_GET['token'].")");
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
        }

        //构造渠道列表
        $result5 = new ProductForm;
        $tuidan_list = $result5->tuidan_list($_GET['token']);

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
                        ':order_id' => $_GET['order_id']
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
                        ':order_id' => $_GET['order_id']
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
        $order_data = array(
                "id"=> $result4[0]['id'] ,
                "order_name"=> $result4[0]['order_name'] ,
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
                'designer_list'=> $designer_list,
                'planner_list'=> $planner_list,
                'tuidan_list'=> $tuidan_list,
                'discount'=> $discount,
                'cut_price'=> $result4[0]['cut_price'],
                'type_price'=> $type_price,
                'follow_data'=> $follow_data,
                'payment_data'=> $payment_data
            );        
        $t=explode(' ', $order['order_date']);
        $order_data['order_date']=$t[0];
        $result3 = yii::app()->db->createCommand("select sp.id,sp.name from order_product op left join supplier_product sp on product_id=sp.id where op.order_id=".$_GET['order_id']." and sp.supplier_type_id=16");
        $result3 = $result3->queryAll();
        if(!empty($result3)){
            $order_data['tuidan_id']=$result3[0]['id'];
            $order_data['tuidan_name']=$result3[0]['name'];
        }else{
            $order_data['tuidan_id']="";
            $order_data['tuidan_name']="";
        };

        // *******************************************************
        // ********************   构造报价单    ********************
        // *******************************************************

        //有区域产品，按区域分组，并加总，计算出总价、折后总价；
        $area_product = array();
        $area = OrderShowArea::model()->findAll();
        $discount_range=explode(',', $order['discount_range']);
        foreach ($area as $key => $value) {
            if($value['type'] != 0){
                $tem=array();
                $tem['area_id']=$value['id'];
                $tem['area_name']=$value['name'];
                $tem['product_list'] = array();
                $tem['area_total'] = 0;
                $tem['discount_total'] = 0;
                $tem['total_cost'] = 0;
                foreach ($result1 as $key_p => $value_p) {
                    if($value['id'] == $value_p['father_area']){
                        $item=array();
                        $item['product_id']=$value_p['id'];
                        $item['product_name']=$value_p['product_name'];
                        $item['description']=$value_p['description'];
                        $item['ref_pic_url']=$value_p['ref_pic_url'];
                        $item['price']=$value_p['actual_price'];
                        $item['amount']=$value_p['amount'];
                        $item['unit']=$value_p['unit'];
                        $item['cost']=$value_p['actual_unit_cost'];
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

        //计算订单总价
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

        //构造返回数据
        $order_detail = array();
        $order_detail['order_data'] = $order_data;
        $order_detail['area_product'] = $area_product;
        $order_detail['non_area_product'] = $non_area_product;
        $order_detail['set_data'] = $set_data;
        $order_detail['order_total'] = $order_total;

        return $order_detail;
    }

    public function build_print_html($bill)
    {
        //头部
        $html = '<!DOCTYPE html>
                <html>

                <head>
                    <title>打印</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
                    <link rel="stylesheet" type="text/css" href="http://file.cike360.com/css/mui.min.css" />
                    <link rel="stylesheet" type="text/css" href="http://file.cike360.com/css/base.css">
                    <link rel="stylesheet" type="text/css" href="http://file.cike360.com/css/print.css">
                    <style>
                        .list_img {
                            width: 4rem;
                            min-height: 3rem;
                        }
                    </style>
                </head>

                <body style="margin:5rem;">
                    <article class="print_module">';


        //折扣部分
        $html .=    '<div class="print_top flexbox" style="margin-top: 0;">
                        <div class="print_title_box flex1" id="print_title">
                             <!--echart-->
                            <div class="chart_box flexbox center" style="float:right;">
                                <div id="main" style="width: 300px;height:300px;">

                                </div>
                            </div>
                            <div style="float:left;display:inline-block;width:45%;">
                                <header style="margin-bottom:0">
                                    <h1>'.$bill['order_data']['order_name'].'</h1>
                                    <p class="address">
                                        地址：<span>'.$bill['order_data']['order_place'].'</span>
                                    </p>
                                    <p class="date">
                                        日期：<span>'.$bill['order_data']['order_date'].'</span>
                                    </p>
                                </header>
                                
                                <div class="set_price_table_box" style="width:100%;margin-left:0">
                                    <table class="set_price" width=100%>
                                        <tr>
                                            <td>基本信息</td>
                                        </tr>
                                        <tr>
                                            <td><img style="height:2.1rem;width:auto;margin-top:.5rem;margin-bottom:.5rem;margin-left:.3rem" src="http://file.cike360.com/image/print_zk.png" alt="折扣"></td>
                                            <td>'.$bill['order_data']['discount']['other_discount'].'折</td>
                                        </tr>
                                        <tr>
                                            <td><img style="height:3rem;width:auto;" src="http://file.cike360.com/image/print_ml.png" alt=""></td>
                                            <td colspan="4">'.$bill['order_data']['cut_price'].'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <div class="foot flexbox v_center">
                                                    <div class="flex1">
                                                        <p>
                                                            婚宴统筹:
                                                            <br /><img src="http://file.cike360.com/image/print_hlyh.png" alt="" style="width:4rem;">
                                                        </p>
                                                        <p>
                                                            <span class="name">'.$bill['order_data']['planner_name'].' </span><span class="tel">'.$bill['order_data']['planner_phone'].'</span>
                                                        </p>
                                                    </div>
                                                    <div class="flex1">
                                                        <p>
                                                            婚礼策划:
                                                            <br /><img src="http://file.cike360.com/image/print_hlch.png" alt="" style="width:6.2rem;">
                                                        </p>
                                                        <p>
                                                            <span class="name">'.$bill['order_data']['designer_name'].' </span><span class="tel">'.$bill['order_data']['designer_phone'].'</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>';

        //餐饮部分
        $html .=    '<div style="margin-top: 2rem;">
                        <div id="print_feast">';

        foreach ($bill['set_data']['feast'] as $key => $value) {
            $html .=        '<section class="option_table_box">
                                <div class="option_table">
                                    <table width=100%;>
                                        <thead>
                                            <tr>
                                                <td><img src="http://file.cike360.com/image/print_feast.png" alt=""></td>
                                                <td>数量</td>
                                                <td>单位</td>
                                                <td>单价</td>
                                                <td>备注</td>
                                                <td>总价</td>
                                            </tr>
                                        </thead>
                                        <tbody>';
            foreach ($value['product_list'] as $key1 => $value1) {
                $html .=                    '<tr>
                                                <td>'.$value1['product_name'].'</td>
                                                <td>'.$value1['amount'].'</td>
                                                <td>'.$value1['unit'].'</td>
                                                <td>'.$value1['price'].'</td>
                                                <td class="list_remark">'.$value1['description'].'</td>
                                                <td>'.$value1['amount']*$value1['price'].'</td>
                                            </tr>';
            };
            $html .=                   '</tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="total green">总计</td>
                                                <td colspan="3">
                                                    <p>原总价(含服务费)</p>'.$bill['order_total']['areap'][$key]['zheqian_total'].'
                                                </td>
                                                <td colspan="2">
                                                    <p>最终价(含服务费)</p>'.$bill['order_total']['areap'][$key]['zhehou_total'].'
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </section>';
        };                            
        $html .=        '</div>';


        //其他区域
        $html .=        '<div id="print_area">';
        foreach ($bill['area_product'] as $key => $value) {
            if(count($value['product_list']) > 0){
                $html .=    '<section class="option_table_box">
                                <div class="option_table">
                                    <table width=100%;>
                                        <thead>
                                        <tr>
                                            <td><img src="http://file.cike360.com/image/print_t'.$value['area_id'].'.png" alt=""></td>
                                            <td>参考图</td>
                                            <td>数量</td>
                                            <td>单位</td>
                                            <td>价格</td>
                                            <td>备注</td>
                                            <td>总价</td>
                                        </tr>
                                        </thead>
                                        <tbody>';
                foreach ($value['product_list'] as $key1 => $value1) {
                    $html .=                '<tr style="height: 3rem; line-height: 3rem;">
                                                <td>
                                                    <div style="position: relative; top: -2rem">'.$value1['product_name'].'</div>
                                                </td>
                                                <td><img class="list_img" style="margin-top: 1rem; margin-left: .5rem;" src="'.$value1['ref_pic_url'].'"></td>
                                                <td>
                                                    <div style="position: relative; top: -2rem">'.$value1['amount'].'</div>
                                                </td>
                                                <td>
                                                    <div style="position: relative; top: -2rem">'.$value1['unit'].'</div>
                                                </td>
                                                <td>
                                                    <div style="position: relative; top: -2rem">'.$value1['price'].'</div>
                                                </td>
                                                <td>

                                                        <div class="list_remark" style="position: relative; top: -2rem">'.$value1['description'].'</div>
                                                    </td>
                                                    <td>
                                                        <div style="position: relative; top: -2rem">'.$value1['amount']*$value1['price'].'</div>
                                                    </td>
                                                </tr>';
                };
                $html .=                '</tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="total green">总计</td>
                                                <td colspan="3">
                                                    <p>原总价</p>'.$bill['order_total']['areap'][$key]['zheqian_total'].'
                                                </td>
                                                <td colspan="3">
                                                    <p>最终价</p>'.$bill['order_total']['areap'][$key]['zhehou_total'].'
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </section>';
            };
            
        };
        $html .=        '</div>';


        //无区域产品
        $html .=       '<div id="print_non_area">';
        if(count($bill['non_area_product']) > 0){
            $html .=        '<section class="option_table_box">
                                <div class="option_table">
                                    <table width=100%;>
                                        <thead>
                                            <tr>
                                                <td><img src="http://file.cike360.com/image/print_none_area.png" alt=""></td>
                                                <td>参考图</td>
                                                <td>数量</td>
                                                <td>单位</td>
                                                <td>单价</td>
                                                <td>备注</td>
                                                <td>总价</td>
                                            </tr>
                                        </thead>
                                        <tbody>';
            foreach ($bill['non_area_product'] as $key => $value) {
                $html .=                    '<tr>
                                                <td>'.$value['product_name'].'</td>
                                                <td><img class="list_img" style="margin-top: 1rem; margin-left: .5rem;" src='.$value['ref_pic_url'].'></td>
                                                <td>'.$value['amount'].'</td>
                                                <td>'.$value['unit'].'</td>
                                                <td>'.$value['price'].'</td>
                                                <td class="list_remark">'.$value['description'].'</td>
                                                <td>'.$value['amount']*$value['price'].'</td>
                                            </tr>';
            };
            $html .=                    '</tbody>
                                    </table>
                                </div>
                            </section>';
        };
        $html .=        '</div>';

        //尾部
        $orderTotal = json_encode($bill['order_total']);
        $html .=    "</div>
                </article>
                <script src='http://file.cike360.com/js/zepto.min.js'></script>
                <script src=\"http://file.cike360.com/js/echarts.min.js\"></script>
                <script>
                    //基本信息渲染
                    var orderTotal = '".$orderTotal."';
                    orderTotal = JSON.parse(orderTotal);
                    var allTotal = orderTotal.total.price.toFixed(0);
                    var pieName = null;
                    //var pieName = ['婚宴'];
                    var outData = [];
                    var inData = [];
                    //计算婚宴
                    var feast_total = 0;
                    $.each(orderTotal.feast, function (index, value) {
                        feast_total += parseFloat(value.zhehou_total);
                    });
                    //计算未分配
                    var non_area_total = orderTotal.non_area.zhehou_total;
                    //构造外圈呈现内容
                    outData.push({ value: feast_total, name: '婚宴', itemStyle: { normal: { color: '#dc69aa' } } });
                    $.each(orderTotal.areap, function (index, value) {
                        //pieName.push(value.name);
                        outData.push({ value: value.zhehou_total, name: value.name });
                    });
                    //pieName.push(\"待分配产品\", '服务人员', '场地布置', '灯光音响视频');
                    outData.push({ value: non_area_total, name: '待分配产品', itemStyle: { normal: { color: '#8d98b3' } } });

                    //构造内圈呈现内容
                    inData.push({ value: feast_total, name: '婚宴', itemStyle: { normal: { color: '#dc69aa' } } });
                    inData.push({ value: ".$bill['order_data']['type_price']['service'].", name: '服务人员', itemStyle: { normal: { color: '#2094e6' } } });
                    inData.push({ value: ".$bill['order_data']['type_price']['decorat'].", name: '场地布置', itemStyle: { normal: { color: '#ecd70f' } } });
                    inData.push({ value: ".$bill['order_data']['type_price']['light'].", name: '灯光音响视频', itemStyle: { normal: { color: '#0fec63' } } });

                    var myChart = echarts.init(document.getElementById('main'));
                    option = {
                        tooltip: {
                            trigger: 'item',
                            formatter: \"{a} <br/>{b}: {c} ({d}%)\"
                        },
                        legend: {
                            orient: 'horizontal',
                            y: 'bottom',
                            x: '20',
                            itemWidth: 15,
                            textStyle: {
                                fontSize: 10,
                                color: '#999',
                            },
                            data: pieName
                        },
                        series: [
                    {
                        name: '类别',
                        type: 'pie',
                        selectedMode: 'single',
                        radius: ['30%', '50%'],
                        itemStyle: {
                            normal: {
                                label: {
                                    show: false,
                                    normal: {
                                        position: 'inner'
                                    }
                                },
                                labelLine: {
                                    show: false
                                }
                            }
                        },
                        data: inData
                    },
                    {
                        name: '总价',
                        type: 'pie',
                        selectedMode: '',
                        radius: ['0', '32%'],
                        itemStyle: {
                            normal: {
                                label: {
                                    show: true,
                                    position: 'center',
                                    textStyle: {
                                        fontSize: '20',
                                        fontWeight: 'bold',
                                        color: '#333',
                                    },
                                    formatter: function (data) {
                                        return data.name + '\\n' + data.value;
                                    },
                                },
                                labelLine: {
                                    show: false
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data: [
                            {
                                value: '￥' + allTotal,
                                name: '总价TOTAL',
                                itemStyle: {
                                    normal: {
                                        color: '#fff'
                                    }
                                }
                            },
                        ]
                    },
                    {
                        name: '区域',
                        type: 'pie',
                        radius: ['54%', '73%'],
                        itemStyle: {
                            normal: {
                                label: {
                                    show: false
                                },
                                labelLine: {
                                    show: false
                                }
                            }
                        },
                        data: outData
                    }
                        ]
                    };
                    myChart.setOption(option);
                </script>
            </body>
            </html>";
        return $html;
    }

    public function same_to_page_print($order_id, $email, $token)
    {
        $data = new OrderForm;
        $bill = $data->get_order_detail($order_id, $token);
        $html = $this->build_print_html($bill);

        // echo $html;die;
        $fp = fopen("billtable.html","w");
        if(!$fp){
            // echo "System Error";
            exit(); 
        }else {
            fwrite($fp,$html);
            fclose($fp);
            // echo "Success";
        };


        //发送邮件 

        //主題 
        $subject = "new_bill"; 

        //收件人 
        //$sendto = 'trhyyy@hpeprint.com'; 
        $sendto = $email; 
        // echo $email;

        //發件人 
        //$replyto = '2837745713@qq.com'; 
        //$replyto = 'hunlicehuashi2016@126.com'; 
        $replyto = 'zhangsiheng0820@126.com'; 

        //內容 
        $message = ""; 

        //附件 
        //$filename = "billtable".$_SESSION['userid'].".html"; 
        $filename = "billtable.html"; 
        //附件類別 
        //$mimetype = "billtable".$_SESSION['userid'].".html";  
        $mimetype = "billtable.html";  
        
        $mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
        $mailfile->sendfile(); 
    }
 
    public function pad_print($order_id,$email)
    {
        $Order = Order::model()->findByPk($order_id);
        $date = explode(" ",$Order['order_date']);
        $t = new StaffForm();
        $wed = OrderWedding::model()->find(array(
            'condition' => 'order_id=:order_id',
            'params' => array(
                ':order_id' => $order_id
            )
        ));


        $order_data = array();
        $order_data['id']='W'.$order_id.'-'.$date[0];
        $order_data['feast_discount']=$Order['feast_discount'];
        $order_data['other_discount']=$Order['other_discount'];
        $order_data['cut_price']=$Order['cut_price'];
        $order_data['designer_name']=$t->getName($Order['designer_id']);
        $order_data['groom_name']=$wed['groom_name'];
        $order_data['groom_phone']=$wed['groom_phone'];
        $order_data['bride_name']=$wed['bride_name'];
        $order_data['bride_phone']=$wed['bride_phone'];

        //print_r($order_data);die;

        $orderId = $order_id;
        $supplier_product_id = array();
        $wed_feast = array();
        $arr_wed_feast = array();

        $order_discount = Order::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $orderId),
        ));

        /*print_r($order_discount['other_discount']);die;
*/
        /*********************************************************************************************************************/
        /*取餐饮数据*/
        /*********************************************************************************************************************/
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 2),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/
        if(!empty($supplier_id)){
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $criteria1->addCondition("category=:category");
            $criteria1->params[':category']=2; 
            $supplier_product = SupplierProduct::model()->findAll($criteria1);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = $value->id;
                $supplier_product_id[] = $item;
            };
            /*print_r($supplier_product_id);*/
        }
        
        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $wed_feast[] = $item;
            };
            /*print_r($wed_feast);*/
        }
        /*print_r($wed_feast);*/
        
        if(!empty($wed_feast)){
            $criteria3 = new CDbCriteria; 
            $criteria3->addCondition("id=:id");
            $criteria3->params[':id']=$wed_feast[0]['product_id']; 
            $supplier_product2 = SupplierProduct::model()->find($criteria3);
            /*print_r($supplier_product2);*/
            $arr_wed_feast = array(
                'name' => $supplier_product2['name'],
                'unit_price' => $wed_feast[0]['actual_price'],
                'unit' => $supplier_product2['unit'],
                'table_num' => $wed_feast[0]['unit'],
                'service_charge_ratio' => $wed_feast[0]['actual_service_ratio'],
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01)*$order_discount['feast_discount']*0.1,
                'total_cost' => $wed_feast[0]['actual_unit_cost']*$wed_feast[0]['unit'],
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01,
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01)/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01)),
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_wed_feast);*/

        /*********************************************************************************************************************/
        /*取灯光数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_light = array();
        $light = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 8),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $light[] = $item;
            };
        }
        if (!empty($light)) {
            $arr_light_total['total_price']=0;
            $arr_light_total['total_cost']=0;
            foreach ($light as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$light[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $light[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $light[$key]['unit'],
                );
                $arr_light[]=$item;
                $arr_light_total['total_price'] += $light[$key]['actual_price']*$light[$key]['unit'];
                $arr_light_total['total_cost'] +=$light[$key]['actual_unit_cost']*$light[$key]['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            if($arr_light_total['total_price'] != 0){
                $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];    
            }else if($arr_light_total['total_cost'] != 0){
                $arr_light_total['gross_profit_rate'] = 0;
            }     
        }else{
            $arr_light_total['gross_profit']=0;
            $arr_light_total['gross_profit_rate']=0;
            $arr_light_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }

        /*print_r($arr_light_total);*/

        /*********************************************************************************************************************/
        /*取视频数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_video = array();
        $arr_video_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 9),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            $video = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $video[] = $item;
            };
            /*print_r($video);*/
        }

        if (!empty($video)) {
            $arr_video_total['total_price']=0;
            $arr_video_total['total_cost']=0;
            foreach ($video as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$video[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $video[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $video[$key]['unit'],
                );
                $arr_video[]=$item;
                $arr_video_total['total_price'] += $video[$key]['actual_price']*$video[$key]['unit'];
                $arr_video_total['total_cost'] +=$video[$key]['actual_unit_cost']*$video[$key]['unit'];
            }
            
                $arr_video_total['gross_profit']=$arr_video_total['total_price']-$arr_video_total['total_cost'];
            if($arr_video_total['total_price'] != 0){
                $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];    
            }else if($arr_video_total['total_cost'] != 0){
                $arr_video_total['gross_profit_rate'] = 0;
            }           
            
        }

        /*print_r($arr_video_total);*/

        /*********************************************************************************************************************/
        /*取主持人数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_host = array();
        $arr_host_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 3),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            $host = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $host[] = $item;
            };
            /*print_r($host);*/
        }
        if (!empty($host)) {
            $arr_host_total['total_price']=0;
            $arr_host_total['total_cost']=0;
            foreach ($host as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$host[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $host[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $host[$key]['unit'],
                );
                $arr_host[]=$item;
                $arr_host_total['total_price'] += $host[$key]['actual_price']*$host[$key]['unit'];
                $arr_host_total['total_cost'] +=$host[$key]['actual_unit_cost']*$host[$key]['unit'];
            }        
            $arr_host_total['gross_profit']=$arr_host_total['total_price']-$arr_host_total['total_cost'];
            if($arr_host_total['total_price'] != 0){
                $arr_host_total['gross_profit_rate']=$arr_host_total['gross_profit']/$arr_host_total['total_price'];    
            }else if($arr_host_total['total_cost'] != 0){
                $arr_host_total['gross_profit_rate'] = 0;
            }   
        }

        /*print_r($arr_host);*/


        /*********************************************************************************************************************/
        /*取摄像数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_camera = array();
        $camera = array();
        $arr_camera_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 4),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $camera[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($camera)) {
            $arr_camera_total['total_price']=0;
            $arr_camera_total['total_cost']=0;
            foreach ($camera as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$camera[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $camera[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $camera[$key]['unit'],
                );
                $arr_camera[]=$item;
                $arr_camera_total['total_price'] += $camera[$key]['actual_price']*$camera[$key]['unit'];
                $arr_camera_total['total_cost'] +=$camera[$key]['actual_unit_cost']*$camera[$key]['unit'];
            }           
            $arr_camera_total['gross_profit']=$arr_camera_total['total_price']-$arr_camera_total['total_cost'];
            if($arr_camera_total['total_price'] != 0){
                $arr_camera_total['gross_profit_rate']=$arr_camera_total['gross_profit']/$arr_camera_total['total_price'];    
            }else if($arr_camera_total['total_cost'] != 0){
                $arr_camera_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取摄影数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_photo = array();
        $photo = array();
        $arr_photo_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 5),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $photo[] = $item;
            };
            /*print_r($photo);*/
        }
        if (!empty($photo)) {
            $arr_photo_total['total_price']=0;
            $arr_photo_total['total_cost']=0;
            foreach ($photo as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$photo[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $photo[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $photo[$key]['unit'],
                );
                $arr_photo[]=$item;
                $arr_photo_total['total_price'] += $photo[$key]['actual_price']*$photo[$key]['unit'];
                $arr_photo_total['total_cost'] +=$photo[$key]['actual_unit_cost']*$photo[$key]['unit'];
            }           
            $arr_photo_total['gross_profit']=$arr_photo_total['total_price']-$arr_photo_total['total_cost'];
            if($arr_photo_total['total_price'] != 0){
                $arr_photo_total['gross_profit_rate']=$arr_photo_total['gross_profit']/$arr_photo_total['total_price'];    
            }else if($arr_photo_total['total_cost'] != 0){
                $arr_photo_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_photo_total);*/

        /*********************************************************************************************************************/
        /*取化妆数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_makeup = array();
        $makeup = array();
        $arr_makeup_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 6),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $makeup[] = $item;
            };
            /*print_r($makeup);*/
        }
        if (!empty($makeup)) {
            $arr_makeup_total['total_price']=0;
            $arr_makeup_total['total_cost']=0;
            foreach ($makeup as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$makeup[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $makeup[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $makeup[$key]['unit'],
                );
                $arr_makeup[]=$item;
                $arr_makeup_total['total_price'] += $makeup[$key]['actual_price']*$makeup[$key]['unit'];
                $arr_makeup_total['total_cost'] +=$makeup[$key]['actual_unit_cost']*$makeup[$key]['unit'];
            }           
            $arr_makeup_total['gross_profit']=$arr_makeup_total['total_price']-$arr_makeup_total['total_cost'];
            if($arr_makeup_total['total_price'] != 0){
                $arr_makeup_total['gross_profit_rate']=$arr_makeup_total['gross_profit']/$arr_makeup_total['total_price'];    
            }else if($arr_makeup_total['total_cost'] != 0){
                $arr_makeup_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取其他人员数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_other = array();
        $other = array();
        $arr_other_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 7),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $other[] = $item;
            };
            /*print_r($other);*/
        }
        if (!empty($other)) {
            $arr_other_total['total_price']=0;
            $arr_other_total['total_cost']=0;
            foreach ($other as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$other[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $other[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $other[$key]['unit'],
                );
                $arr_other[]=$item;
                $arr_other_total['total_price'] += $other[$key]['actual_price']*$other[$key]['unit'];
                $arr_other_total['total_cost'] +=$other[$key]['actual_unit_cost']*$other[$key]['unit'];
            }           
            $arr_other_total['gross_profit']=$arr_other_total['total_price']-$arr_other_total['total_cost'];
            if($arr_other_total['total_price'] != 0){
                $arr_other_total['gross_profit_rate']=$arr_other_total['gross_profit']/$arr_other_total['total_price'];    
            }else if($arr_other_total['total_cost'] != 0){
                $arr_other_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/



        /*********************************************************************************************************************/
        /*计算人员部分总价*/
        /*********************************************************************************************************************/
        $arr_service_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        if(!empty($arr_host_total)){
            $arr_service_total['total_price'] += $arr_host_total['total_price'];
            $arr_service_total['total_cost'] += $arr_host_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_host_total['gross_profit'];
        }

        if(!empty($arr_camera_total)){
            $arr_service_total['total_price'] += $arr_camera_total['total_price'];
            $arr_service_total['total_cost'] += $arr_camera_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_camera_total['gross_profit'];
        }

        if(!empty($arr_photo_total)){
            $arr_service_total['total_price'] += $arr_photo_total['total_price'];
            $arr_service_total['total_cost'] += $arr_photo_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_photo_total['gross_profit'];
        }

        if(!empty($arr_makeup_total)){
            $arr_service_total['total_price'] += $arr_makeup_total['total_price'];
            $arr_service_total['total_cost'] += $arr_makeup_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_makeup_total['gross_profit'];
        }

        if(!empty($arr_other_total)){
            $arr_service_total['total_price'] += $arr_other_total['total_price'];
            $arr_service_total['total_cost'] += $arr_other_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_other_total['gross_profit'];
        }



        if($arr_service_total['total_price'] != 0){
            $arr_service_total['gross_profit_rate'] = $arr_service_total['gross_profit']/$arr_service_total['total_price'];
        }




        /*********************************************************************************************************************/
        /*取场地布置数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_decoration = array();
        $decoration = array();
        $arr_decoration_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 20),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $decoration[] = $item;
            };
            /*print_r($decoration);*/
        }
        if (!empty($decoration)) {
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
            foreach ($decoration as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$decoration[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);
                $ref_pic_url = "";
                $t = explode(".", $supplier_product2['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $ref_pic_url = "http://file.cike360.com".$t[0]."_sm.".$t[1];    
                };
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $decoration[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $decoration[$key]['unit'],
                    'ref_pic_url' => $ref_pic_url,
                );
                $arr_decoration[]=$item;
                $arr_decoration_total['total_price'] += $decoration[$key]['actual_price']*$decoration[$key]['unit'];
                $arr_decoration_total['total_cost'] +=$decoration[$key]['actual_unit_cost']*$decoration[$key]['unit'];
            }           
            $arr_decoration_total['gross_profit']=$arr_decoration_total['total_price']-$arr_decoration_total['total_cost'];
            if($arr_decoration_total['total_price'] != 0){
                $arr_decoration_total['gross_profit_rate']=$arr_decoration_total['gross_profit']/$arr_decoration_total['total_price'];    
            }else if($arr_decoration_total['total_cost'] != 0){
                $arr_decoration_total['gross_profit_rate'] = 0;
            }  
        }else{
            $arr_decoration_total['gross_profit']=0;
            $arr_decoration_total['gross_profit_rate']=0;
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }
        /*print_r($arr_decoration_total['total_cost']);die;*/

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取平面设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_graphic = array();
        $graphic = array();
        $arr_graphic_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 10),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $graphic[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($graphic)) {
            $arr_graphic_total['total_price']=0;
            $arr_graphic_total['total_cost']=0;
            foreach ($graphic as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$graphic[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $graphic[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $graphic[$key]['unit'],
                );
                $arr_graphic[]=$item;
                $arr_graphic_total['total_price'] += $graphic[$key]['actual_price']*$graphic[$key]['unit'];
                $arr_graphic_total['total_cost'] +=$graphic[$key]['actual_unit_cost']*$graphic[$key]['unit'];
            }           
            $arr_graphic_total['gross_profit']=$arr_graphic_total['total_price']-$arr_graphic_total['total_cost'];
            if($arr_graphic_total['total_price'] != 0){
                $arr_graphic_total['gross_profit_rate']=$arr_graphic_total['gross_profit']/$arr_graphic_total['total_price'];    
            }else {
                $arr_graphic_total['gross_profit_rate']=0;
            }
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视频设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_film = array();
        $film = array();
        $arr_film_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 11),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $film[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($film)) {
            $arr_film_total['total_price']=0;
            $arr_film_total['total_cost']=0;
            foreach ($film as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$film[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $film[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $film[$key]['unit'],
                );
                $arr_film[]=$item;
                $arr_film_total['total_price'] += $film[$key]['actual_price']*$film[$key]['unit'];
                $arr_film_total['total_cost'] +=$film[$key]['actual_unit_cost']*$film[$key]['unit'];
            }           
            $arr_film_total['gross_profit']=$arr_film_total['total_price']-$arr_film_total['total_cost'];
            if($arr_film_total['total_price'] != 0){
                $arr_film_total['gross_profit_rate']=$arr_film_total['gross_profit']/$arr_film_total['total_price'];    
            }else {
                $arr_film_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视策划师产品数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_designer = array();
        $designer = array();
        $arr_designer_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 17),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);die;*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $designer[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($designer)) {
            $arr_designer_total['total_price']=0;
            $arr_designer_total['total_cost']=0;
            foreach ($designer as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$designer[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $designer[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $designer[$key]['unit'],
                );
                $arr_designer[]=$item;
                $arr_designer_total['total_price'] += $designer[$key]['actual_price']*$designer[$key]['unit'];
                $arr_designer_total['total_cost'] +=$designer[$key]['actual_unit_cost']*$designer[$key]['unit'];
            }           
            $arr_designer_total['gross_profit']=$arr_designer_total['total_price']-$arr_designer_total['total_cost'];
            if($arr_designer_total['total_price'] != 0){
                $arr_designer_total['gross_profit_rate']=$arr_designer_total['gross_profit']/$arr_designer_total['total_price'];    
            }else {
                $arr_designer_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($designer);die;*/
        

        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_order_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        

        /*print_r($order_discount);die;*/

        if(!empty($arr_wed_feast)){
            $arr_order_total['total_price'] += $arr_wed_feast['total_price'] * $order_discount['feast_discount'] * 0.1;
            $arr_order_total['total_cost'] += $arr_wed_feast['total_cost'];
        }

        if(!empty($arr_video)){
            if($this->judge_discount(9,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_video_total['total_price'];
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_video_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }
            
        }

        if(!empty($arr_light)){
            if($this->judge_discount(8,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_light_total['total_price'];
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_light_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }
        }

        if(!empty($arr_service_total)){
            if($this->judge_discount(3,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_service_total['total_price'];
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_service_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }
        }

        if(!empty($arr_decoration_total)){
            if($this->judge_discount(20,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'];
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }
        }
        if(!empty($arr_graphic_total)){
            if($this->judge_discount(10,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'];
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }
        }
        if(!empty($arr_film_total)){
            if($this->judge_discount(11,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_film_total['total_price'];
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_film_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }
        }
        if(!empty($arr_designer_total)){
            if($this->judge_discount(17,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_designer_total['total_price'];
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_designer_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }
        }

        if($order_discount['cut_price'] != 0){
            $arr_order_total['total_price'] -= $order_discount['cut_price'];
        }

        /*print_r($arr_order_total['total_price']);die;*/
        $arr_order_total['gross_profit'] = $arr_order_total['total_price'] - $arr_order_total['total_cost'];

        if($arr_order_total['total_price'] != 0){
            $arr_order_total['gross_profit_rate']=$arr_order_total['gross_profit']/$arr_order_total['total_price'];    
        }else {
            $arr_order_total['gross_profit_rate']=0;
        }



        /*========================================================================================================
        ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊界面渲染＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
        ========================================================================================================*/




$html = '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
</head>
<body>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>

<table class="tftable" border="1">
<tr><th colspan="3">基本信息</th></tr>
<tr><td width="10%">订单编号</td><td colspan="2" width="90%">'.$order_data["id"].'</td></tr>
<tr><td width="10%">新郎信息</td><td width="50%">'.$order_data["groom_name"].'</td><td width="40%">'.$order_data["groom_phone"].'</td></tr>
<tr><td width="10%">新娘信息</td><td width="50%">'.$order_data["bride_name"].'</td><td width="40%">'.$order_data["bride_phone"].'</td></tr>
<tr><td width="10%">策划师</td><td colspan="2" width="90%">'.$order_data["designer_name"].'</td></tr>
<tr><td width="10%">婚宴折扣</td><td colspan="2" width="90%">'.$order_data["feast_discount"].'</td></tr>
<tr><td width="10%">婚礼折扣</td><td colspan="2" width="90%">'.$order_data["other_discount"].'</td></tr>
<tr><td width="10%">抹零</td><td colspan="2" width="90%">'.$order_data["cut_price"].'</td></tr>
<tr><td width="10%">订单总价</td><td colspan="2" width="90%">'.$arr_order_total['total_price'].'</td></tr>
</table>

';

/*<!-- 婚宴 -->*/
if (!empty($arr_wed_feast)) {

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<tr><td width="10%" rowspan = "5">婚宴</td><td width="4%">1</td><td width="12%">'.$arr_wed_feast['name'].'</td><td width="20%"></td><td width="4%">'.$arr_wed_feast['table_num'].'</td><td width="9%">'.$arr_wed_feast['unit'].'</td><td width="18%">'.$arr_wed_feast['unit_price'].'</td><td width="23%"> </td></tr>
</table>';


};


/*<!-- 灯光 -->*/

if (!empty($arr_light)) {
$i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

    foreach ($arr_light as $key => $value) {
        foreach ($value as $key1 => $value1) {
            $light[$key1] = $value1;
        }   


        if($i==1){
$html .= '<tr><td width="10%" rowspan = "'.count($arr_light).'">灯光</td><td width="4%">'.$i.'</td><td width="12%">'.$light['name'].'</td><td width="20%"></td><td width="4%">'.$light['amount'].'</td><td width="4%">'.$light['unit'].'</td><td width="23%">'.$light['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$light['name'].'</td><td width="20%"></td><td width="4%">'.$light['amount'].'</td><td width="4%">'.$light['unit'].'</td><td width="23%">'.$light['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .= '</table>';
};


/*<!-- 视频 -->*/

if (!empty($arr_video)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_video as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $video[$key1] = $value1;
            }
            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_video).'">视频</td><td width="4%">'.$i.'</td><td width="12%">'.$video['name'].'</td><td width="20%"></td><td width="4%">'.$video['amount'].'</td><td width="4%">'.$video['unit'].'</td><td width="23%">'.$video['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$video['name'].'</td><td width="20%"></td><td width="4%">'.$video['amount'].'</td><td width="4%">'.$video['unit'].'</td><td width="23%">'.$video['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';

  
    };

/*<!-- 主持人 -->*/

if (!empty($arr_host)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_host as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $host[$key1] = $value1;
            }
            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_host).'">主持人</td><td width="4%">'.$i.'</td><td width="12%">'.$host['name'].'</td><td width="20%"></td><td width="4%">'.$host['amount'].'</td><td width="4%">'.$host['unit'].'</td><td width="23%">'.$host['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$host['name'].'</td><td width="20%"></td><td width="4%">'.$host['amount'].'</td><td width="4%">'.$host['unit'].'</td><td width="23%">'.$host['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';
 
    };


/*<!-- 摄像 -->*/

if (!empty($arr_camera)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';
        foreach ($arr_camera as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $camera[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_camera).'">摄像</td><td width="4%">'.$i.'</td><td width="12%">'.$camera['name'].'</td><td width="20%"></td><td width="4%">'.$camera['amount'].'</td><td width="4%">'.$camera['unit'].'</td><td width="23%">'.$camera['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">.'.$camera['name'].'</td><td width="20%"></td><td width="4%">'.$camera['amount'].'</td><td width="4%">'.$camera['unit'].'</td><td width="23%">'.$camera['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 摄影 -->*/

    if (!empty($arr_photo)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_photo as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $photo[$key1] = $value1;
        }
        if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_photo).'">摄影</td><td width="4%">'.$i.'</td><td width="12%">'.$photo['name'].'</td><td width="20%"></td><td width="4%">'.$photo['amount'].'</td><td width="4%">'.$photo['unit'].'</td><td width="23%">'.$photo['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$photo['name'].'</td><td width="20%"></td><td width="4%">'.$photo['amount'].'</td><td width="4%">'.$photo['unit'].'</td><td width="23%">'.$photo['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 化妆 -->*/

    if (!empty($arr_makeup)) {
    $i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_makeup as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $makeup[$key1] = $value1;
            }

            if($i==1){

$html .= '<tr><td width="10%" rowspan = "'.count($arr_makeup).'">化妆</td><td width="4%">'.$i.'</td><td width="12%">'.$makeup['name'].'</td><td width="20%"></td><td width="4%">'.$makeup['amount'].'</td><td width="4%">'.$makeup['unit'].'</td><td width="23%">'.$makeup['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$makeup['name'].'</td><td width="20%"></td><td width="4%">'.$makeup['amount'].'</td><td width="4%">'.$makeup['unit'].'</td><td width="23%">'.$makeup['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .= '</table>';

 
    };


/*<!-- 其他 -->*/

    if (!empty($arr_other)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_other as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $other[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_other).'">其他</td><td width="4%">'.$i.'</td><td width="12%">'.$other['name'].'</td><td width="20%"></td><td width="4%">'.$other['amount'].'</td><td width="4%">'.$other['unit'].'</td><td width="23%">'.$other['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$other['name'].'</td><td width="20%"></td><td width="4%">'.$other['amount'].'</td><td width="4%">'.$other['unit'].'</td><td width="23%">'.$other['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';

    };


/*<!-- 场地布置 -->*/

    if (!empty($arr_decoration)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_decoration as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $decoration[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_decoration).'">场地布置</td><td width="4%">'.$i.'</td><td width="12%">'.$decoration['name'].'</td><td width="20%"></td><td width="4%">'.$decoration['amount'].'</td><td width="4%">'.$decoration['unit'].'</td><td width="23%">'.$decoration['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$decoration['name'].'</td><td width="20%"></td><td width="4%">'.$decoration['amount'].'</td><td width="4%">'.$decoration['unit'].'</td><td width="23%">'.$decoration['unit_price'].'</td><td width="23%"><img style="height:150px" src="'.$decoration['ref_pic_url'].'"></img></td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 平面设计 -->*/

    if (!empty($arr_graphic)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_graphic as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $graphic[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_graphic).'">平面设计</td><td width="4%">'.$i.'</td><td width="12%">'.$graphic['name'].'</td><td width="20%"></td><td width="4%">'.$graphic['amount'].'</td><td width="4%">'.$graphic['unit'].'</td><td width="23%">'.$graphic['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$graphic['name'].'</td><td width="20%"></td><td width="4%">'.$graphic['amount'].'</td><td width="4%">'.$graphic['unit'].'</td><td width="23%">'.$graphic['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 视频设计 -->*/

    if (!empty($arr_film)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_film as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $film[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_film).'">视频设计</td><td width="4%">'.$i.'</td><td width="12%">'.$film['name'].'</td><td width="20%"></td><td width="4%">'.$film['amount'].'</td><td width="4%">'.$film['unit'].'</td><td width="23%">'.$film['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$film['name'].'</td><td width="20%"></td><td width="4%">'.$film['amount'].'</td><td width="4%">'.$film['unit'].'</td><td width="23%">'.$film['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 策划费&杂费 -->*/
    if (!empty($arr_designer)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_designer as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $designer[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_designer).'">策划费&杂费</td><td width="4%">'.$i.'</td><td width="12%">'.$designer['name'].'</td><td width="20%"></td><td width="4%">'.$designer['amount'].'</td><td width="4%">'.$designer['unit'].'</td><td width="23%">'.$designer['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">.'.$i.'</td><td width="12%">'.$designer['name'].'</td><td width="20%"></td><td width="4%">'.$designer['amount'].'</td><td width="4%">'.$designer['unit'].'</td><td width="23%">'.$designer['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';

  
    };





$html .='</body>
</html>';

        //$fp = fopen("billtable".$_SESSION['userid'].".html","w");
        $fp = fopen("billtable.html","w");
        if(!$fp)
        {
        echo "System Error";
        exit();
        }
        else {
        fwrite($fp,$html);
        fclose($fp);
        echo "Success";
        }



        /*require_once "../library/email.class.php";
        //******************** 配置信息 ********************************
        $smtpserver = "smtp.qq.com";//SMTP服务器
        $smtpserverport =25;//SMTP服务器端口
        $smtpusermail = "2837745713@qq.com";//SMTP服务器的用户邮箱
        $smtpemailto = "zhangsiheng0820@126.com";//发送给谁
        $smtpuser = "2837745713";//SMTP服务器的用户帐号
        $smtppass = "xsxn1183";//SMTP服务器的用户密码
        $mailtitle = "报价单";//邮件主题
        $mailcontent = $html;//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = true;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

        echo   '<head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>报价单</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black">
                <meta name="format-detection" content="telephone=no">
                <link href="css/base.css" rel="stylesheet" type="text/css"/>
                <link href="css/style.css" rel="stylesheet" type="text/css"/>
                </head>
                <body>';

        echo "<div style='width:300px; margin:36px auto;'>";
        if($state==""){
            echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
            echo "<a href='index.html'>点此返回</a>";
            exit();
        }
        echo "恭喜！邮件发送成功！！";
        echo "<a href='index.html'>点此返回</a>";
        echo "</div></body>";*/

        
        

        //发送邮件 

        //主題 
        $subject = "test send email"; 

        //收件人 
        //$sendto = 'trhyyy@hpeprint.com'; 
        $sendto = $email; 
        // echo $email;

        //發件人 
        //$replyto = '2837745713@qq.com'; 
        //$replyto = 'hunlicehuashi2016@126.com'; 
        $replyto = 'zhangsiheng0820@126.com'; 

        //內容 
        $message = ""; 

        //附件 
        //$filename = "billtable".$_SESSION['userid'].".html"; 
        $filename = "billtable.html"; 
        //附件類別 
        //$mimetype = "billtable".$_SESSION['userid'].".html";  
        $mimetype = "billtable.html";  
        echo "1";

        $mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
        echo "2";
        $mailfile->sendfile(); 
        echo "3";
    }

    public function judge_discount($type_id,$order_id){
        $order = Order::model()->findByPk($order_id); 
        $discount_range = explode(",",$order['discount_range']);
        $t=0;
        foreach ($discount_range as $key => $value) {
            if($value == $type_id){
                $t=1;
            }
        }
        return $t;
    }
     

}

<?php

use yii\authclient\OAuth2;
use yii\web\HttpException;

include_once('../library/WPRequest.php');
include_once('../library/taobao-sdk-PHP-auto_1455552377940-20160505/TopSdk.php');
header('content-type:application:json;charset=utf8'); 
header("Content-Type:text/html;charset=utf-8"); 
header('Access-Control-Allow-Origin:*');  
header('Access-Control-Allow-Methods:POST');  
header('Access-Control-Allow-Headers:x-requested-with,content-type');




// include_once('../library/taobao-sdk-PHP-auto_1455552377940-20160505/top/TopClient.php');
// include_once('../library/taobao-sdk-PHP-auto_1455552377940-20160505/top/request/AlibabaAliqinFcSmsNumSendRequest.php');

class BackgroundController extends InitController
{
    public $defaultAction = 'index_front';
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main-not-exited';
        /**
     * @inheritdoc
     */
    public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
    public $authUrlMp = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.weixin.qq.com';
    public $type = null;
    /**
     * @inheritdoc
     */

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions' => array(''),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionRegister_pro()
    {
        $telephone = $_POST['telephone'];
        // $telephone = "18611323194";

        $url = "http://localhost/school/crm_web/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";

        $staff = Staff::model()->find(array(
            "condition" => "telephone = :telephone",
            "params"    => array(
                ":telephone" => $_POST['telephone']
                )
            ));
        if (empty($staff)) {
            echo "not exist";
        } elseif (!empty($staff['password'])){
            echo "registered";
        } elseif (isset($_POST['password'])) {
            if ($_POST['yzm'] == $_SESSION['code']) {
                Staff::model()->updateByPk( $staff['id'] ,array('password'=>$_POST['password']));
                echo "success";
            } else {
                echo "errow";
            }
            
        } else {
            echo "发送请求";
            // $data = json_encode(array(
            //     'telephone' => $_POST['telephone'],
            // ), JSON_UNESCAPED_UNICODE);
            $data = array('telephone' => $telephone, );
            $result = WPRequest::post_code($url, $data);
            Yii::app()->session['code'] = $result;

        }

        // $data = array('telephone' => $telephone, );
        // $result = WPRequest::post($url, $data);
        // echo "result:".$result;
        // print_r($_SESSION);

       
    }

    public function actionRegister_host_pro()
    {
        $url = "http://localhost/school/crm_web/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";

        $person_data = array();

        if($_POST['department'] == 11){
            $person_data['CI_Type'] = 6;
            $person_data['service_type'] = 3;
        };
        if($_POST['department'] == 12){
            $person_data['CI_Type'] = 13;
            $person_data['service_type'] = 4;
        };
        if($_POST['department'] == 13){
            $person_data['CI_Type'] = 14;
            $person_data['service_type'] = 5;
        };
        if($_POST['department'] == 14){
            $person_data['CI_Type'] = 15;
            $person_data['service_type'] = 6;
        };
        if($_POST['department'] == 15){
            $person_data['CI_Type'] = 7;
            $person_data['service_type'] = 20;
        };
        if($_POST['department'] == 16){
            $person_data['CI_Type'] = 18;
            $person_data['service_type'] = 8;
        };
        if($_POST['department'] == 17){
            $person_data['CI_Type'] = 19;
            $person_data['service_type'] = 23;
        };
        if($_POST['department'] == 18){
            $person_data['CI_Type'] = 20;
            $person_data['service_type'] = 9;
        };

        if (isset($_POST['password'])) { //注册
            if ($_POST['yzm'] == $_SESSION['code']) {
                $staff = Staff::model()->find(array(
                        'condition' => 'telephone=:telephone',
                        'params' => array(
                                ':telephone' => $_POST['telephone']
                            )
                    ));
                $staff_id = "";
                if(empty($staff)){ //手机号未注册，新建一个staff
                    $data = new Staff;
                    $data ->name = $_POST['name'];
                    $data ->telephone = $_POST['telephone'];
                    $data ->department_list = "[".$_POST['department']."]";
                    $data ->password = $_POST['password'];
                    $data ->save();
                    $staff_id = $data->attributes['id'];
                }else{  //手机号已经注册，但不是主持人，修改staff的department_list
                    $staff['department_list']=rtrim($staff['department_list'], "]");
                    $staff['department_list']=ltrim($staff['department_list'], "[");
                    $t=explode(',',$staff['department_list']);
                    $department_list = "[";
                    foreach ($t as $key => $value) {
                        $department_list .= $value . ",";
                    };
                    $department_list .= $_POST['department']."]";
                    $staff = Staff::model()->find(array(
                        'condition' => 'telephone=:telephone',
                        'params' => array(
                            ':telephone' => $_POST['telephone']
                        )
                    ));
                    Staff::model()->updateByPk($staff['id'],array('department_list'=>$department_list,'password'=>$_POST['password'],'name'=>$_POST['name']));
                    $staff_id = $staff['id'];
                };
                $data = new CaseInfo;
                $data ->CI_Name = $_POST['name'];
                $data ->CI_Pic = "";
                $data ->CI_Sort = 1;
                $data ->CI_Show = 1;
                $data ->CI_Type = $person_data['CI_Type'];
                $data ->CT_ID = $staff_id;
                $data ->save();
                $CI_ID = $data->attributes['CI_ID'];

                $data = new ServicePerson;
                $data ->team_id = 2;
                $data ->name = $_POST['name'];
                $data ->gender = 1;
                $data ->avatar = "";
                $data ->telephone = $_POST['telephone'];
                $data ->update_time = date('y-m-d h:i:s',time());
                $data ->staff_id = $staff_id;
                $data ->service_type = $person_data['service_type'];
                $data ->save();

                $data = new CaseBind;
                $data ->CB_Type = 4;
                $data ->TypeID = 0;
                $data ->CI_ID = $CI_ID;
                $data ->save();

                $staff_company = StaffCompany::model()->findAll();

                foreach ($staff_company as $key => $value) {
                    $data = new Supplier;
                    $data ->account_id = $value['id'];
                    $data ->type_id = $person_data['service_type'];
                    $data ->staff_id = $staff_id;
                    $data ->save();
                }
                echo "success"; 
            } else {
                echo "errow"  ;
            };
        } else {  //发验证码
            $staff = Staff::model()->find(array(
                        'condition' => 'telephone=:telephone',
                        'params' => array(
                                ':telephone' => $_POST['telephone']
                            )
                    ));
            if(!empty($staff)){ //如果手机号已经注册
                if($staff['department_list'] != ""){
                    $staff['department_list']=rtrim($staff['department_list'], "]");
                    $staff['department_list']=ltrim($staff['department_list'], "[");
                    $t=explode(',',$staff['department_list']);
                    $i = 0;
                    if(!empty($t)){
                        foreach ($t as $key => $value) {
                            if($value == $_POST['department']){$i++;};
                        };
                    };
                    if($i != 0){  //如果注册者已经是主持人
                        echo "该手机号已经注册！" ; 
                    }else{  //注册者还不是主持人
                        echo "验证码已发送到您的手机！";
                        $data = array('telephone' => $_POST['telephone'], );
                        $result = WPRequest::post_code($url, $data);
                        Yii::app()->session['code'] = $result;
                        //echo $_SESSION['code'];
                    };
                };
            }else{ // 如果手机号还未注册
                echo "您的手机未注册，验证码已发送到您的手机！";
                $data = array('telephone' => $_POST['telephone'], );
                $result = WPRequest::post_code($url, $data);
                Yii::app()->session['code'] = $result;
                //echo $_SESSION['code'];
            };
        };
    }


    public function actionLogin()
    {
        $this->render("login");
    }

    public function actionRegist()
    {
        $this->render("regist");
    }

    public function actionRegist_host()
    {
        $this->render("regist_host");   
    }

    public function actionLogin_pro()
    {
        //参数表
        $telephone = $_POST['telephone'];
        $password = $_POST['password'];
        // $telephone = '15101140405';
        // $password = '12345678';

        $staff = Staff::model()->find(array(
            "condition" => "telephone = :telephone",
            "params"    => array(
                ":telephone" => $telephone
                )
            ));
        $status = "";
        if (empty($staff)) {
            $status = "not exist";
        }else{
            if($staff['password'] == $password){
                $cookie = Yii::app()->request->getCookies();
                unset($cookie['userid']);

                $cookie = new CHttpCookie('userid',$staff['id']);
                $cookie->expire = time()+60*60*24*30*12;  //有限期100年
                Yii::app()->request->cookies['userid']=$cookie;

                // $cookies = Yii::$app->response->cookies;
                // $cookies->add(new \yii\web\Cookie([
                //     'name' => 'userid',
                //     'value' => $staff['id'],
                //     'expire'=>time()+3600*24*30*12
                // ]));

                $cookie = new CHttpCookie('account_id',$staff['account_id']);
                Yii::app()->request->cookies['account_id']=$cookie;  
                // setcookie("account_id", $staff['account_id'], time()+60*60*24*30*12*100);


                $cookie = new CHttpCookie('department_list',$staff['department_list']);
                Yii::app()->request->cookies['department_list']=$cookie; 
                // setcookie("department_list", $staff['department_list'], time()+60*60*24*30*12*100);


                $status = "success";
            }else{
                $status = "password error";
            };
        }
        $result = array(
                'token' => $staff['id'],
                'status' => $status
            );
        echo json_encode($result);
    }

    public function actionIndex()
    {
        $url = "http://file.cike360.com";
        if($_GET['CI_Type'] == 1 || $_GET['CI_Type'] == 4){
            $staff_id = $_COOKIE['userid'];
            $result = yii::app()->db->createCommand("select * from case_info where CI_ID in ( select CI_ID from case_bind where CB_Type=4 ) and CI_Show=1 order by CI_Sort Desc");
            $list = $result->queryAll();
            foreach($list as  $key => $val){
                if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                    $t = explode(".", $val['CI_Pic']);
                    if(isset($t[0]) && isset($t[1])){
                        $list[$key]["CI_Pic"]=$url.$t[0]."_sm.".$t[1];
                    }else{
                        $list[$key]["CI_Pic"]="images/cover.jpg";
                    }
                    
                };
            };
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id']
                        )
                ));
            /*print_r($tap);die;*/
            $this->render("index",array(
                    'case_data' => $list,
                    'tap' => $tap,
                ));
        };
        if($_GET['CI_Type'] == 2 || $_GET['CI_Type'] == 16){
            $staff_id = $_COOKIE['userid'];
            $result = yii::app()->db->createCommand("select * from case_info where ".
                "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                    "(select account_id from staff where id=".$staff_id.") ) ".

                " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
                "(select hotel_list from staff where id=".$staff_id.") ) ".
                " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".
                " and CI_Show=1 order by CI_Sort Desc");
            $list = $result->queryAll();
            $list_data = array();
            foreach($list as  $key => $val){
                // if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                //     $t = explode(".", $val['CI_Pic']);
                //     if(isset($t[0]) && isset($t[1])){
                //         $list[$key]["CI_Pic"]=$url.$t[0]."_sm.".$t[1];
                //     }else{
                //         $list[$key]["CI_Pic"]="images/cover.jpg";
                //     }
                // };
                $cr = CaseResources::model()->find(array(
                        'condition' => 'CI_ID=:CI_ID && CR_Type=:CR_Type && CR_Show=:CR_Show',
                        'params' => array(
                                ':CI_ID' => $val['CI_ID'],
                                ':CR_Type' => 1,
                                ':CR_Show' => 1
                            )
                    ));

                $item = array();
                $item['CI_ID'] = $val['CI_ID'];
                $item['CI_Name'] = $val['CI_Name'];
                if(!empty($cr)){
                    $item['CI_Pic'] = $cr['CR_Path'];
                }else{
                    $item['CI_Pic'] = "images/cover.jpg";
                };
                $item['CI_Type'] = $val['CI_Type'];
                $item['CT_ID'] = $val['CT_ID'];
                $item['CI_Remarks'] = $val['CI_Remarks'];
                $list_data[] = $item;
            };
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id']
                        )
                ));
            // echo json_encode($list_data);die;
            $this->render("index",array(
                    'case_data' => $list_data,
                    'tap' => $tap,
                ));
        }else if($_GET['CI_Type'] == 5){
            $order_model = OrderModel::model()->findAll(array(
                    'condition' => 'account_id=:account_id && model_show=:model_show && is_empty=:is_empty && is_menu=:is_menu',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':model_show' => 1,
                            ':is_empty' => 0,
                            ':is_menu' => 0
                        ),
                    'order' => 'update_time DESC'
                ));
            foreach ($order_model as $key => $value) {
                $t = explode('/', $value['poster_img']);
                if(isset($t[0])){
                    if($t[0] == 'upload'){
                        $order_model[$key]['poster_img'] = 'http://file.cike360.com/'.$value['poster_img'];
                    }else if($t[0] == ''){
                        $order_model[$key]['poster_img'] = 'http://file.cike360.com'.$value['poster_img'];
                    };
                };
            };

            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id']
                        )
                ));
            $this->render("index",array(
                    'case_data' => $order_model,
                    'tap' => $tap,
                ));
        }else if($_GET['CI_Type'] == 7){
            $product = SupplierProduct::model()->findAll(array(
                    'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id && product_show=:product_show',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':standard_type' => 0,
                            ':supplier_type_id' => 20, 
                            ':product_show' => 1,
                        ),
                    'order' => 'update_time DESC'
                ));
            foreach($product as  $key => $val){
                $t = explode(".", $val['ref_pic_url']);
                if(isset($t[0]) && $t[1]){
                        $product[$key]["ref_pic_url"]=$url.$t[0]."_sm.".$t[1];
                    }else{
                        $product[$key]["ref_pic_url"]="images/cover.jpg";
                    }
                
            };
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id || account_id=:ai',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':ai' => 0
                        )
                ));
            /*print_r($product);die;*/
            $this->render("index",array(
                    'case_data' => $product,
                    'tap' => $tap,
                ));
        }else if($_GET['CI_Type']== 6 || $_GET['CI_Type']== 13 || $_GET['CI_Type']== 14 || $_GET['CI_Type']== 15){
            /*$tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id']
                        )
                ));*/
            $case = CaseInfo::model()->find(array(
                    'condition' => 'CI_Type=:CI_Type && CT_ID=:CT_ID',
                    'params' => array(
                            ':CI_Type' => $_GET['CI_Type'],
                            ':CT_ID' => $_COOKIE['userid']
                        )
                ));
            $service_person = ServicePerson::model()->find(array(
                    'condition' => 'staff_id=:staff_id',
                    'params' => array(
                            ':staff_id' => $case['CT_ID'],
                        ),
                ));
            // print_r($case);die;
            $this->render('index',array(
                    /*'tap'=>$tap,*/
                    'case' => $case,
                    'service_person' => $service_person,
                ));
        }else if($_GET['CI_Type'] == 8){
            $criteria = new CDbCriteria;
            $criteria->addInCondition('supplier_type_id', array(8,9,23));
            $criteria->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria->params[':account_id']=$_COOKIE['account_id'];
            $criteria->params[':product_show']=1;
            $supplier_product = SupplierProduct::model()->findAll($criteria); 

            foreach ($supplier_product as $key => $value) {
                $t = explode(".", $value['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $supplier_product[$key]['ref_pic_url'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                };
            };

            $this->render("index",array(
                    'supplier_product' => $supplier_product,
                ));
        }else if($_GET['CI_Type'] == 61){
            $userid = $_COOKIE['userid'];
            $account_id = $_COOKIE['account_id'];
            $company = StaffCompany::model()->findByPk($account_id);
            //找同一城市，所有服务人员
            // $service_person = yii::app()->db->createCommand("select s.id,s.service_type,s.name,s.telephone,supplier_type.name as type_name ".
            //     " from service_person s left join supplier_type on s.service_type=supplier_type.id".
            //     " where s.service_type in (select id from supplier_type where role=2) and `show`=1 and s.staff_id in (select id from staff where city_id=".$company['city_id'].") ".
            //     " order by service_type");
            // $service_person = $service_person->queryAll();

            //找本公司服务人员
            // $service_person = yii::app()->db->createCommand("select id from supplier_type where role=2");
            // $service_person =  $service_person->queryAll();
            // print_r($service_person);die;

            $service_person = yii::app()->db->createCommand("select s.id,s.service_type,s.name,s.telephone,supplier_type.name as type_name ".
                " from service_person s left join supplier_type on s.service_type=supplier_type.id".
                " where s.show=1 and s.staff_id in (select staff_id from supplier where account_id=".$account_id." ) ".
                " and service_type in (select id from supplier_type where role=2)");
            $service_person =  $service_person->queryAll();
            // $service_person = yii::app()->db->createCommand("select sp.id,sp.service_type,sp.name,sp.telephone,sp.staff_id,sub.id as subarea,supplier_type.name as type_name  ".
            //     " from service_person sp left join order_show_area_subarea sub on sp.service_type=sub.supplier_type ".
            //     " left join supplier_type on sp.service_type=supplier_type.id".
            //     " where sp.show=1 and service_type in (select supplier_type from order_show_area_subarea where father_area=6)");
            // $service_person = $service_person->queryAll();

            //构造返回信息
            $person_data = array();
            foreach ($service_person as $key => $value) {
                $img = ServicePersonImg::model()->find(array(
                        'condition' => 'service_person_id=:service_person_id',
                        'params' => array(
                                ':service_person_id' => $value['id']
                            )
                    ));
                if(!empty($img)){
                    $url = 'http://file.cike360.com'.$this->add_string_to_url($img['img_url'], 'sm');
                    $t = explode('/', $img['img_url']);
                    if(isset($t[0])){
                        if($t[0] == 'http:'){
                            $url = $img['img_url'];
                        };
                    };
                }else{
                    $url = "images/cover.jpg";
                };

                $item = array();
                $item['service_person_id'] = $value['id'];
                $item['type_id'] = $value['service_type'];
                $item['type_name'] = $value['type_name'];
                $item['name'] = $value['name'];
                $item['telephone'] = $value['telephone'];
                $item['img'] = $url;
                $person_data[] = $item;
                    
            };

/**************************************＊*****************************************************/
/************************************* CI版本 ************************************************/
/****************************************＊***************************************************/
            // $supplier = yii::app()->db->createCommand("select s.id,staff.id as staff_id,staff.name,staff.telephone,st.name as type_name,st.id as type_id,sp.id as service_person_id".
            //     // " from ((supplier s left join supplier_product sp on s.id=sp.supplier_id) ".
            //     " from (supplier s left join staff on s.staff_id=staff.id".
            //     " left join supplier_type st on s.type_id=st.id".
            //     " left join service_person sp on sp.staff_id=staff.id)".
            //     " where sp.status=0 and s.account_id=".$_COOKIE['account_id']." and s.type_id in (3,4,5,6,7) ".
            //     " group by s.id order by s.update_time");
            // $supplier = $supplier->queryAll();
            // // print_r(json_encode($supplier));die;
            // $supplier_data = array();
            // foreach ($supplier as $key => $val) {
            //     $item['id'] = $val['id'];
            //     $item['name'] = $val['name'];
            //     $item['telephone'] = $val['telephone'];
            //     $item['type_name'] = $val['type_name'];
            //     $item['type_id'] = $val['type_id'];
            //     $item['service_person_id'] = $val['service_person_id'];
            //     $item['CI_Type'] = 0;
            //     if($val['type_id'] == 3){
            //         $item['CI_Type'] = 6;
            //     }else if($val['type_id'] == 4){
            //         $item['CI_Type'] = 13;
            //     }else if($val['type_id'] == 5){
            //         $item['CI_Type'] = 14;
            //     }else if($val['type_id'] == 6){
            //         $item['CI_Type'] = 15;
            //     }else if($val['type_id'] == 7){
            //         $item['CI_Type'] = 21;
            //     }
            //     $case = CaseInfo::model()->find(array(
            //             'condition' => 'CI_Type=:CI_Type && CT_ID=:CT_ID',
            //             'params' => array(
            //                     ':CI_Type' => $item['CI_Type'],
            //                     ':CT_ID' => $val['staff_id'],
            //                 )
            //         ));
            //     $item['CI_ID'] = $case['CI_ID'];
            //     $item['CI_Pic'] = $case['CI_Pic'];
            //     $supplier_data[] = $item;
            // };

            // echo json_encode($supplier_data);die;
            
            //取人员类别
            $supplier_type = SupplierType::model()->findAll(array(
                    'condition' => 'role=:role',
                    'params' => array(
                            ':role' => 2
                        )
                ));
            
            $this->render("index",array(
                'person_data' => $person_data,
                'supplier_type' => $supplier_type
            ));
        }else if($_GET['CI_Type'] == 9){
            //查询菜品
            $criteria = new CDbCriteria;
            $criteria->addCondition("account_id = :account_id && product_show=:product_show && supplier_type_id=:supplier_type_id");    
            $criteria->params[':account_id']=$_COOKIE['account_id'];
            $criteria->params[':product_show']=1;
            $criteria->params[':supplier_type_id']=2;
            $criteria->order = 'update_time DESC';
            $supplier_product = SupplierProduct::model()->findAll($criteria); 

            foreach ($supplier_product as $key => $value) {
                $t = explode(".", $value['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $supplier_product[$key]['ref_pic_url'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                };
            };

            //查询菜单
            // $result = yii::app()->db->createCommand("select wedding_set.id as CT_ID,case_info.CI_ID as CI_ID,case_info.CI_Pic,wedding_set.`name`,wedding_set.final_price from wedding_set left join staff_hotel on staff_hotel_id=staff_hotel.id left join case_info on wedding_set.id=case_info.CT_ID where case_info.CI_Type in (9,11) and account_id=".$_COOKIE['account_id']." and category in (3,4) and CI_Show=1");
            // $menu = $result->queryAll();
            // foreach ($menu as $key => $value) {
            //     $t = explode(".", $value['CI_Pic']);
            //     if(isset($t[0]) && isset($t[1])){
            //         $menu[$key]['CI_Pic'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
            //     };
            // };
            $menu = array();
            $staff = Staff::model()->findByPk($_COOKIE['userid']);
            $order_model =  yii::app()->db->createCommand("select * from order_model ".
                " where model_show=1 and is_menu=1 and account_id=".$staff['account_id']);
            $order_model =  $order_model->queryAll();
            foreach ($order_model as $key => $value) {
                $url = "";
                $t = explode('/', $value['poster_img']);
                if(isset($t[0])){
                    if($t[0] == "http:"){
                        $url = $value['poster_img'];
                    }else if($t[0] == 'upload'){
                        $url = 'http://file.cike360.com/'.$value['poster_img'];
                    }else if($t[0] == ''){
                        $url = 'http://file.cike360.com'.$value['poster_img'];
                    };
                };
                $item = array();
                $item['id'] = $value['id'];
                $item['poster'] = $url;
                $item['name'] = $value['name'];
                $item['model_order'] = $value['model_order'];
                $item['img_amount'] = 0;
                $menu[] = $item;
            };
            $this->render("index",array(
                    'supplier_product' => $supplier_product,
                    'menu' => $menu,
                ));
        }else if($_GET['CI_Type']== 17 || $_GET['CI_Type']== 18 || $_GET['CI_Type']== 19 || $_GET['CI_Type']== 20){
            $case = CaseInfo::model()->find(array(
                    'condition' => 'CI_Type=:CI_Type && CT_ID=:CT_ID',
                    'params' => array(
                            ':CI_Type' => $_GET['CI_Type'],
                            ':CT_ID' => $_COOKIE['userid']
                        )
                ));
            $service_person = ServicePerson::model()->find(array(
                    'condition' => 'staff_id=:staff_id',
                    'params' => array(
                            ':staff_id' => $case['CT_ID'],
                        ),
                ));
            // print_r($case);die;
            $this->render('index',array(
                    /*'tap'=>$tap,*/
                    'case' => $case,
                    'service_person' => $service_person,
                ));
        }else if($_GET['CI_Type'] == 101){
            // $folder = LibraryStaffFolder::model()->findAll(array(
            //         'condition' => 'Staff_ID=:Staff_ID',
            //         'params' => array(
            //                 ':Staff_ID' => $_COOKIE['userid']
            //             )
            //     ));

            $folder = yii::app()->db->createCommand('select * from library_staff_folder where Staff_ID='.$_COOKIE['userid'].' and Folder_ID not in (select folder_id from library_share_folder)');
            $folder = $folder->queryAll();

            $folder_data = array();
            foreach ($folder as $key => $value) {
                $result =  yii::app()->db->createCommand("select * from library_web_case_img ".
                    " where Img_ID in (select Img_ID from library_folder_bind where Folder_ID=".$value['Folder_ID'].")");
                $result =  $result->queryAll();
                $item = array();
                $item['id'] = $value['Folder_ID'];
                $item['name'] = $value['Folder_Name'];
                if(!empty($result)){
                    $t = explode('/', $result[0]['local_URL']);
                    if($t[0] == "http:"){
                        $item['img'] = $result[0]['local_URL'];    
                    }else{
                        $item['img'] = 'http://file.cike360.com'.ltrim($result[0]['local_URL'], '.'); 
                    };
                }else{
                    $item['img'] = "images/empty_folder.png";
                };
                    
                
                $item['img_amount'] = count($result);
                $folder_data[] = $item;
            };
            $this->render('index',array(
                    'folder' => $folder_data
                ));
        }else if($_GET['CI_Type'] == 'order'){
            //取订单
            $token = $_COOKIE['userid'];

            $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,staff.name as designer_name,order_status from `order` o ".
                " left join staff on designer_id=staff.id ".
                " where designer_id=".$token." or planner_id=".$token);
            $tt = $tt->queryAll();
            $order_data = $this->get_order_doing_and_done($tt);
            $this->render('index',array(
                    'order_doing' => $order_data['doing']
                ));
        }else if($_GET['CI_Type'] == 'share_case'){
            // $folder = LibraryStaffFolder::model()->findAll(array(
            //         'condition' => 'Staff_ID=:Staff_ID',
            //         'params' => array(
            //                 ':Staff_ID' => $_COOKIE['userid']
            //             )
            //     ));

            $folder = yii::app()->db->createCommand('select * from library_staff_folder where Folder_ID in (select folder_id from library_share_folder)');
            $folder = $folder->queryAll();

            $folder_data = array();
            foreach ($folder as $key => $value) {
                $result =  yii::app()->db->createCommand("select * from library_web_case_img ".
                    " where Img_ID in (select Img_ID from library_folder_bind where Folder_ID=".$value['Folder_ID'].")");
                $result =  $result->queryAll();
                $item = array();
                $item['id'] = $value['Folder_ID'];
                $item['name'] = $value['Folder_Name'];
                if(!empty($result)){
                    $t = explode('/', $result[0]['local_URL']);
                    if($t[0] == "http:"){
                        $item['img'] = $result[0]['local_URL'];    
                    }else{
                        $item['img'] = 'http://file.cike360.com'.ltrim($result[0]['local_URL'], '.'); 
                    };
                }else{
                    $item['img'] = "images/empty_folder.png";
                };
                    
                
                $item['img_amount'] = count($result);
                $folder_data[] = $item;
            };
            $this->render('index',array(
                    'folder' => $folder_data
                ));
        }
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
                    'designer_name' => $value['designer_name'],
                    'order_date' => $value['order_date'],
                    'model_id' => 0,
                    'to_date' => 0,
                    'order_status' => $value['order_status']
                );
            if($item['order_status'] == 0){$item['order_status'] = '待定';};
            if($item['order_status'] == 1){$item['order_status'] = '预定';};
            if($item['order_status'] == 2){$item['order_status'] = '已付定金';};
            if($item['order_status'] == 3){$item['order_status'] = '付中期款';};
            if($item['order_status'] == 4){$item['order_status'] = '已付尾款';};
            if($item['order_status'] == 5){$item['order_status'] = '结算中';};
            if($item['order_status'] == 6){$item['order_status'] = '已结算';};

            // if($value['model_id'] != null){
            //     $item['model_id'] = $value['model_id'];
            // };
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
                // if($item['model_id'] == 0){
                //     $order_non_model[]=$item;
                // };
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

    public function actionExample2()
    {
        //参数表
        $order_id = $_GET['order_id'];
        $token = $_GET['token'];

        //预备信息
        $data = new OrderForm;
        $result = $data->get_order_detail($order_id, $token);

        foreach ($result['order_show'] as $key => $value) {
            if($value['area_id'] != 1 && $value['area_id'] != 2 && $value['area_id'] != 8){
                foreach ($value['subarea'] as $key1 => $value1) {
                    foreach ($value1['data'] as $key2 => $value2) {
                        if($value2['show_data'] != ''){
                            $t0 = explode('/', ltrim($value2['show_data'], 'http://file.cike360.com'));  
                            if(isset($t0[0])){
                                $xs = '';
                                $sm = '';
                                $md = '';
                                if($t0[0] == 'upload'){
                                    $sm = str_replace("_xs", "_sm", $value2['show_data']);
                                    $md = str_replace("_sm", "_md", $value2['show_data']);
                                }else{
                                    $sm = $value2['show_data'];
                                    $xs = $value2['show_data'];
                                    $md = $value2['show_data'];                 
                                };
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2]['show_data'] = $sm;
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_xs' => $sm) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_md' => $md) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                            };  
                        };  
                    };
                };
            };
        };

        foreach ($result['area_product'] as $key => $value) {
            foreach ($value['product_list'] as $key1 => $value1) {
                if($value1['ref_pic_url'] != ''){
                    $t0 = explode('/', ltrim($value1['ref_pic_url'], 'http://file.cike360.com') );  
                    if(isset($t0[0])){
                        $sm = '';
                        $md = '';
                        if($t0[0] == 'upload'){
                            $sm = str_replace("_xs", "_sm", $value1['ref_pic_url']);
                            $md = str_replace("_xs", "_md", $value1['ref_pic_url']);
                        }else{
                            $sm = $value1['ref_pic_url'];
                            $md = $value1['ref_pic_url'];                 
                        };
                        $result['area_product'][$key]['product_list'][$key1] = array('sm' => $sm) + $result['area_product'][$key]['product_list'][$key1];
                        $result['area_product'][$key]['product_list'][$key1] = array('md' => $md) + $result['area_product'][$key]['product_list'][$key1];
                    };  
                };  
            }
        }

        //取订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,oms.model_id,staff.name as designer_name,order_status from `order` o ".
            "left join order_model_select oms on o.id = oms.order_id ".
            "left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done($tt);

        //查询哪些订单已经选择套系

        return $this->render('example2', array(
                'result' => $result,
                'order' => $order_data['doing']
            ));
    }


    public function actionExample1()
    {
        $this->render('example1');
    }

    public function actionGet_example_data()
    {
        //参数表
        $order_id = $_GET['order_id'];
        $token = $_GET['token'];

        //预备信息
        $data = new OrderForm;
        $result = $data->get_order_detail($order_id, $token);

        foreach ($result['order_show'] as $key => $value) {
            if($value['area_id'] != 1 && $value['area_id'] != 2 && $value['area_id'] != 8){
                foreach ($value['subarea'] as $key1 => $value1) {
                    foreach ($value1['data'] as $key2 => $value2) {
                        if($value2['show_data'] != ''){
                            $t0 = explode('/', ltrim($value2['show_data'], 'http://file.cike360.com'));  
                            if(isset($t0[0])){
                                $xs = '';
                                $sm = '';
                                $md = '';
                                if($t0[0] == 'upload'){
                                    $sm = str_replace("_xs", "_sm", $value2['show_data']);
                                    $md = str_replace("_sm", "_md", $value2['show_data']);
                                }else{
                                    $sm = $value2['show_data'];
                                    $xs = $value2['show_data'];
                                    $md = $value2['show_data'];                 
                                };
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2]['show_data'] = $sm;
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_xs' => $sm) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_md' => $md) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                            };  
                        };  
                    };
                };
            };
        };

        foreach ($result['area_product'] as $key => $value) {
            foreach ($value['product_list'] as $key1 => $value1) {
                if($value1['ref_pic_url'] != ''){
                    $t0 = explode('/', ltrim($value1['ref_pic_url'], 'http://file.cike360.com') );  
                    if(isset($t0[0])){
                        $sm = '';
                        $md = '';
                        if($t0[0] == 'upload'){
                            $sm = str_replace("_xs", "_sm", $value1['ref_pic_url']);
                            $md = str_replace("_xs", "_md", $value1['ref_pic_url']);
                        }else{
                            $sm = $value1['ref_pic_url'];
                            $md = $value1['ref_pic_url'];                 
                        };
                        $result['area_product'][$key]['product_list'][$key1] = array('sm' => $sm) + $result['area_product'][$key]['product_list'][$key1];
                        $result['area_product'][$key]['product_list'][$key1] = array('md' => $md) + $result['area_product'][$key]['product_list'][$key1];
                    };  
                };  
            }
        }

        //取订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,oms.model_id,staff.name as designer_name,order_status from `order` o ".
            "left join order_model_select oms on o.id = oms.order_id ".
            "left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done($tt);

        echo json_encode(array(
                'result' => $result,
                'order' => $order_data['doing']
            ));
    }

    public function actionPrice_list()
    {
        $this->render('price_list');
    }

    public function actionPrice_list_data()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $order_id = $post->order_id;
        $token = $post->token;
        // $order_id = 2096;
        // $token = 102;

        //预备信息 
        $data = new OrderForm;
        $result = $data->get_order_detail($order_id, $token);
        $staff = Staff::model()->findByPk($token);

        foreach ($result['order_show'] as $key => $value) {
            if($value['area_id'] != 1 && $value['area_id'] != 2 && $value['area_id'] != 8){
                foreach ($value['subarea'] as $key1 => $value1) {
                    foreach ($value1['data'] as $key2 => $value2) {
                        if($value2['show_data'] != ''){
                            $t0 = explode('/', ltrim($value2['show_data'], 'http://file.cike360.com'));  
                            if(isset($t0[0])){
                                $xs = '';
                                $md = '';
                                if($t0[0] == 'upload'){
                                    $xs = str_replace("_sm", "_xs", $value2['show_data']);
                                    $md = str_replace("_sm", "_md", $value2['show_data']);
                                }else{
                                    $xs = $value2['show_data'];
                                    $md = $value2['show_data'];                 
                                };
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_xs' => $xs) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_md' => $md) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                            };  
                        };  
                    };
                };
            }
            // if($value['area_id'] == 15){
            //     unset($result['order_show'][$key]);
            // };
        };

        foreach ($result['area_product'] as $key => $value) {
            foreach ($value['product_list'] as $key1 => $value1) {
                if($value1['ref_pic_url'] != ''){
                    $t0 = explode('/', ltrim($value1['ref_pic_url'], 'http://file.cike360.com') );  
                    if(isset($t0[0])){
                        $sm = '';
                        $md = '';
                        if($t0[0] == 'upload'){
                            $sm = str_replace("_xs", "_sm", $value1['ref_pic_url']);
                            $md = str_replace("_xs", "_md", $value1['ref_pic_url']);
                        }else{
                            $sm = $value1['ref_pic_url'];
                            $md = $value1['ref_pic_url'];                 
                        };
                        $result['area_product'][$key]['product_list'][$key1] = array('sm' => $sm) + $result['area_product'][$key]['product_list'][$key1];
                        $result['area_product'][$key]['product_list'][$key1] = array('md' => $md) + $result['area_product'][$key]['product_list'][$key1];
                    };  
                }; 
            };
            // if($value['area_id'] == 15){
            //     unset($result['area_product'][$key]);
            // };
        };

        //取门店列表
        $hotel = yii::app()->db->createCommand('select id,name from staff_hotel where account_id='.$staff['account_id']);
        $hotel = $hotel->queryAll();

        //取文字主题列表
        $words_list = yii::app()->db->createCommand('select * from order_show_idea_words where `show`=1 and staff_id in (0,'.$token.')');
        $words_list = $words_list->queryAll();
        $words_show = OrderShow::model()->find(array(
                'condition' => 'subarea=:subarea && order_id=:order_id',
                'params' => array(
                        ':subarea' => 1,
                        ':order_id' => $order_id
                    )
            ));
        $words_data = array();
        foreach ($words_list as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['words'] = $value['words'];
            $item['remark'] = $value['remark'];
            $item['subarea_id'] = $value['subarea_id'];
            $item['staff_id'] = $value['staff_id'];
            if(!empty($words_show)){
                if($value['id'] == $words_show['words']){
                    $item['selected'] = true;
                }else{
                    $item['selected'] = false;
                };
            }else{
                $item['selected'] = false;
            };
            $words_data[] = $item;
        };

        //取主题配色列表
        $color_list = yii::app()->db->createCommand('select * from order_show_idea_color where `show`=1 and staff_id in (0,'.$token.')');
        $color_list = $color_list->queryAll();
        $color_show = OrderShow::model()->find(array(
                'condition' => 'subarea=:subarea && order_id=:order_id',
                'params' => array(
                        ':subarea' => 3,
                        ':order_id' => $order_id
                    )
            ));
        $color_data = array();
        foreach ($color_list as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['remark'] = $value['remark'];
            $item['main_color'] = $value['main_color'];
            $item['second_color'] = $value['second_color'];
            $item['third_color'] = $value['third_color'];
            $item['staff_id'] = $value['staff_id'];
            if(!empty($color_show)){
                if($value['id'] == $color_show['wed_color']){
                    $item['selected'] = true;
                }else{
                    $item['selected'] = false;
                };
            }else{
                $item['selected'] = false;
            };
            $color_data[] = $item;
        };

        //我购买的效果图
        $img_buy = yii::app()->db->createCommand('select serp_img.id as service_product_img_id,service_product_id,serp_img.img_url,serp.description,serp.subarea,osi.id as order_show_img_id,osi.description '.
            ' from service_product_img serp_img '.
            ' left join service_product serp on serp_img.service_product_id=serp.id '.
            ' left join order_show_img osi on serp_img.id=osi.service_product_img_id'.
            ' where serp_img.img_show=1 and serp.product_show=1 and service_product_id in '.
            ' (select service_product_id from supplier_order_shopping sos '.
                ' left join service_product serp on sos.service_product_id=serp.id '.
                ' where serp.service_type=30 and sos.staff_id in (select id from staff where account_id='.$staff['account_id'].') and status=1)');
        $img_buy = $img_buy->queryAll();

        //我上传的效果图
        $img_upload = yii::app()->db->createCommand('select id,img_url,description,subarea_id from order_show_img where service_product_img_id=0 and staff_id='.$token);
        $img_upload = $img_upload->queryAll();

        //已选择的效果图
        $img_show = yii::app()->db->createCommand('select * from order_show where order_id='.$order_id.' and subarea in (4,15)');
        $img_show = $img_show->queryAll();

        //合并购买和上传
        $show_img = array();
        foreach ($img_buy as $key => $value) {
            $item = array();
            $item['order_show_img_id'] = $value['order_show_img_id'];
            $item['service_product_img_id'] = $value['service_product_img_id'];
            $item['service_product_id'] = $value['service_product_id'];
            $item['img_url'] = $value['img_url'];
            $item['description'] = $value['description'];
            $item['subarea'] = $value['subarea'];
            $item['selected'] = false;
            $show_img[] = $item;
        };
        foreach ($img_upload as $key => $value) {
            $item = array();
            $item['order_show_img_id'] = $value['id'];
            $item['service_product_img_id'] = 0;
            $item['service_product_id'] = 0;
            $item['img_url'] = $value['img_url'];
            $item['description'] = $value['description'];
            $item['subarea'] = $value['subarea_id'];
            $item['selected'] = false;
            $show_img[] = $item;
        };        

        foreach ($show_img as $key => $value) {
            foreach ($img_show as $key1 => $value1) {
                if($value1['img_id'] == $value['order_show_img_id']){
                    $show_img[$key]['selected'] = true;
                };
            };
        };

        //取area_data
        $area = yii::app()->db->createCommand('select * from order_show_area where type in (0,1,2,3,4,5,6,7) order by sort');
        $area = $area->queryAll();
        $area_data = array();
        foreach ($area as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $subarea = yii::app()->db->createCommand('select id,name from order_show_area_subarea where father_area='.$value['id']);
            $item['subarea'] = $subarea->queryAll();
            $area_data[] = $item;
        };

        $result_data = array(
                'result' => $result,
                'hotel_list' => $hotel,
                'words_list' => $words_data,
                'color_list' => $color_data,
                'show_img' => $show_img,
                'area_data' => $area_data
            );

        echo json_encode($result_data);
    }

    public function actionNew_email()
    {
        //参数表
        $staff_id = $_POST['staff_id'];
        $email = $_POST['email'];

        $data = new StaffEmail;
        $data->staff_id = $staff_id;
        $data->email = $email;
        $data->update_time = date('y-m-d h:i:s',time());
        $data->save();
        $id = $data->attributes['id'];

        echo $id;
    }

    public function actionDel_email()
    {
        //参数表
        $email_id = $_POST['email_id'];
        StaffEmail::model()->deleteByPk($email_id);
    }

    public function actionSelect_email()
    {
        //参数表
        $order_id = $_POST['order_id'];
        $email = $_POST['email'];
        $token = $_POST['token'];
        // $order_id = 1203;
        // $email = '80962715@qq.com';
        // $token = 100;

        $print = new PrintForm;
        $print->same_to_page_print($order_id, $email, $token);
    }

    public function actionShare_store_view()
    {
        $this->render('share_store_view');
    }

    public function actionMy_collection()
    {
        $this->render('my_collection');
    }

    public function actionNew_order()
    {
        //参数表
        $token = $_COOKIE['userid'];

        //预备信息
        $hotel = yii::app()->db->createCommand('select id,name from staff_hotel where account_id in (select account_id from staff where id='.$token.')');
        $hotel = $hotel->queryAll();

        $this->render('new_order', array(
                'hotel' => $hotel
            ));
    }

    public function actionShare_store()
    {
        //参数表
        $token = $_GET['token'];
        $order_id = $_GET['order_id'];
        $area_id = $_GET['area_id'];
        $service_person_list = $_GET['service_person_list'];
        $low_price = $_GET['low_price'];
        $high_price = $_GET['high_price'];
        $keyword = '';
        $subarea_id = $_GET['subarea_id'];
        $page = $_GET['page'];


        //预备信息
        $staff = Staff::model()->findByPk($token);

        //构造 subarea
        $subarea = yii::app()->db->createCommand('select id,name from order_show_area_subarea where father_area='.$area_id);
        $subarea = $subarea->queryAll();

        if($subarea_id == ''){
            if(!empty($subarea)){
                $subarea_id = $subarea[0]['id'];
            };
        };

        //查找所有city
        $city_all = yii::app()->db->createCommand('select * from service_province_city where id in '.
            ' (select city_id from staff where id in '.
                ' (select staff_id from service_person where id in '.
                    ' (select service_person_id from service_product where product_show=1 and subarea='.$subarea_id.'))) ');
        $city_all = $city_all->queryAll();

        //构造city_data
        $city_data = array();
        foreach ($city_all as $key => $value) {
            if($value['id'] == $staff['city_id']){
                $item = array(
                        'id' => $value['id'],
                        'name' => $value['city_name'],
                        'service_person_list' => array()
                    );
                $city_data[] = $item;
            };
        };
        foreach ($city_all as $key => $value) {
            if($value['id'] != $staff['city_id']){
                $item = array(
                        'id' => $value['id'],
                        'name' => $value['city_name'],
                        'service_person_list' => array()
                    );
                $city_data[] = $item;
            };
        };
        foreach ($city_data as $key => $value) {
            $spl = yii::app()->db->createCommand('select * from service_person where id in '.
                ' (select service_person_id from service_product where product_show=1 and service_type in '.
                    ' (select supplier_type from order_show_area_subarea where id='.$subarea_id.') '.
                    ' and service_person_id in (select id from service_person where staff_id in (select id from staff where city_id='.$value['id'].')))');
            $spl = $spl->queryAll();
            foreach ($spl as $key1 => $value1) {
                $item = array();
                $item['id'] = $value1['id'];
                $item['name'] = $value1['name'];
                $s = yii::app()->db->createCommand('select id,name from order_show_area_subarea where id='.$subarea_id);
                $item['subarea'] = $s->queryAll();
                $city_data[$key]['service_person_list'][] = $item;
            };
        };

        foreach ($city_data as $key => $value) {
            if(empty($value['service_person_list'])){
                unset($city_data[$key]);
            };
        };

        if($subarea == ''){
            if(!empty($city_data)){
                if(!empty($city_data[0]['service_person_list'])){
                    if(!empty($city_data[0]['service_person_list'][0]['subarea'])){
                        $subarea = $city_data[0]['service_person_list'][0]['subarea'][0]['id'];
                    };
                };
            };
        };
        if($service_person_list == ''){
            if(!empty($city_data)){
                if(!empty($city_data[0]['service_person_list'])){
                    $service_person_list = $city_data[0]['service_person_list'][0]['id'];
                };
            };
        };

        foreach ($city_data as $key => $value) {
            $t = 0;
            foreach ($value['service_person_list'] as $key1 => $value1) {
                if(!empty($value1['subarea'])){
                    $t = 1;
                };
            };
            if($t == 0){
                unset($city_data[$key]);
            };
        };

        

        //查询产品
        $product_data = new ProductForm;
        $product_data = $product_data->get_share_store_data($area_id, $subarea_id, $token, $order_id, $service_person_list, $low_price, $high_price, $keyword, $page);
            

        

        $return_data = array(
                'product' => $product_data,
                'city' => $city_data,
                'subarea' => $subarea
            );
        echo json_encode($return_data);
    }

    public function actionMy_folders()
    {
        //参数表
        $token = $_GET['token'];
        $folder_id = $_GET['folder_id'];
        $area_id = $_GET['area_id'];
        $subarea_id = $_GET['subarea_id'];
        $page = $_GET['page'];

        //查找 folder_type
        $type = yii::app()->db->createCommand('select * from library_staff_folder_tab where id in '.
            ' (select tab_id from library_staff_folder where staff_id='.$token.' and Folder_ID not in (select folder_id from library_share_folder))');
        $type = $type->queryAll();

        $folder_type = array();
        foreach ($type as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['folder_list'] = array();
            $folder_type[] = $item;
        };
        $t = array('id' => 0, 'name' => '其他', 'folder_list' => array());
        $folder_type[] = $t;

        foreach ($folder_type as $key => $value) {
            $folder = LibraryStaffFolder::model()->findAll(array(
                    'condition' => 'tab_id=:tab_id && Staff_ID=:staff_id',
                    'params' => array(
                            ':tab_id' => $value['id'],
                            ':staff_id' => $token
                        )
                ));
            foreach ($folder as $key1 => $value1) {
                $item = array();
                $item['id'] = $value1['Folder_ID'];
                $item['folder_name'] = $value1['Folder_Name'];
                $folder_type[$key]['folder_list'][] = $item;
            };
        };

        $start = ($page-1)*20;

        if($folder_id == ''){
            if(!empty($folder_type)){
                if(!empty($folder_type[0]['folder_list'])){
                    $folder_id = $folder_type[0]['folder_list'][0]['id'];    
                }else{
                    $folder_id = 0;   
                };
            }else{
                $folder_id = 0;
            };
        };

        $folder_img = yii::app()->db->createCommand('select Img_ID,local_URL from library_web_case_img where Img_ID in '.
            '(select Img_ID from library_folder_bind where Folder_ID='.$folder_id.') limit '.$start.',20');
        $folder_img = $folder_img->queryAll();

        //取subarea
        $subarea = yii::app()->db->createCommand('select id,name from order_show_area_subarea where id='.$subarea_id);
        $subarea = $subarea->queryAll();

        $result = array(
                'folder_type' =>$folder_type,
                'folder_img' => $folder_img,
                'subarea' => $subarea
            );
        echo json_encode($result);
    }

    public function actionBatch_product_insert()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $token = $post->token;
        $order_id = $post->order_id;
        $product_list = $post->product_list;  //json格式的字符串  ：  service_product_id|price|amount,

        // $token = '102';
        // $order_id = '2096';
        // $product_list = "666|700|1|,664|800|1|";  //json格式的字符串  ：  service_product_id|price|amount,

        $list = explode(',', $product_list);
        foreach ($list as $key => $value) {
            $t = explode('|', $value);
            if(!empty($t)){
                $product = new ProductForm;
                $product->product_insert($order_id, $t[0], $t[1], $t[2], $t[3], $token);
            };
        };
    }


    public function actionGet_area_data()
    {
        //取subarea
        $area_data = array();
        $area = OrderShowArea::model()->findAll();
        foreach ($area as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['subarea'] = array();
            $subarea = OrderShowAreaSubarea::model()->findAll(array(
                    'condition' => 'father_area=:father_area',
                    'params' => array(
                            ':father_area' => $value['id']
                        ),
                    'order' => 'sort'
                ));
            foreach ($subarea as $key1 => $value1) {
                $t = array();
                $t['id'] = $value1['id'];
                $t['name'] = $value1['name'];
                $item['subarea'][] = $t;
            };
            $area_data[] = $item;
        };

        echo json_encode($area_data);
    }

    public function actionDesign_list()
    {
        $area_data = array();
        $area = OrderShowArea::model()->findAll();
        foreach ($area as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['data'] = array();
            if($value['type'] == 1){
                //共享库房
                $tem = array();
                $tem['id'] = 1;
                $tem['name'] = '共享库房［道具租赁］';
                $tem['data'] = array();
                $subarea = OrderShowAreaSubarea::model()->findAll(array(
                        'condition' => 'father_area=:father_area',
                        'params' => array(
                                ':father_area' => $value['id']
                            ),
                        'order' => 'sort'
                    ));
                foreach ($subarea as $key1 => $value1) {
                    $t = array();
                    $t['id'] = $value1['id'];
                    $t['name'] = $value1['name'];
                    $tem['data'][] = $t;
                };
                $item['data'][] = $tem;

                //灵感库
                $tem = array();
                $tem['id'] = 2;
                $tem['name'] = '灵感库［参考图片］';
                $tem['data'] = array();
                $subarea = LibraryWebTab::model()->findAll(array(
                        'condition' => 'area_id=:area_id',
                        'params' => array(
                                ':area_id' => $value['id']
                            )
                    ));
                foreach ($subarea as $key1 => $value1) {
                    $t = array();
                    $t['id'] = $value1['Tab_ID'];
                    $t['name'] = $value1['Tab_Name'];
                    $tem['data'][] = $t;
                };
                $item['data'][] = $tem;

                //道具厂家
                $tem = array();
                $tem['id'] = 1;
                $tem['name'] = '共享库房［道具租赁］';
                $tem['data'] = array();
                $subarea = OrderShowAreaSubarea::model()->findAll(array(
                        'condition' => 'father_area=:father_area',
                        'params' => array(
                                ':father_area' => 16
                            ),
                        'order' => 'sort'
                    ));
                foreach ($subarea as $key1 => $value1) {
                    $t = array();
                    $t['id'] = $value1['id'];
                    $t['name'] = $value1['name'];
                    $tem['data'][] = $t;
                };
                $item['data'][] = $tem;
            }else{
                $tem = array();
                $tem['id'] = 1;
                $tem['name'] = $value['name'];
                $tem['data'] = array();
                $subarea = OrderShowAreaSubarea::model()->findAll(array(
                        'condition' => 'father_area=:father_area',
                        'params' => array(
                                ':father_area' => $value['id']
                            ),
                        'order' => 'sort'
                    ));
                foreach ($subarea as $key1 => $value1) {
                    $t = array();
                    $t['id'] = $value1['id'];
                    $t['name'] = $value1['name'];
                    $tem['data'][] = $t;
                };
                $item['data'][] = $tem;
            };
            $area_data[] = $item;
        };
        echo json_encode($area_data);
    }

    public function actionUpload_folder()
    {
        $tab = LibraryStaffFolderTab::model()->findAll(array(
                'condition' => 'staff_id=:staff_id',
                'params' => array(
                        ':staff_id' => $_COOKIE['userid']
                    )
            ));
        $folder = array();
        if(isset($_GET['folder_id'])){
            $data = yii::app()->db->createCommand('select lsf.Folder_Name,tab.name,tab.id from library_staff_folder lsf '.
                ' left join library_staff_folder_tab tab on lsf.tab_id=tab.id '.
                ' where lsf.Folder_ID='.$_GET['folder_id']);
            $data = $data->queryAll();
            if(!empty($data)){
                $folder = $data[0];
            };
        };

        $this->render('upload_folder',array(
            'tab' => $tab,
            'data' => $folder
        ));
    }

    public function actionUpload_folder_poster()
    {
        //参数表
        $userid = $_COOKIE['userid'];

        //预备信息
        $staff = Staff::model()->findByPk($userid);

        //查找所有场地
        $space = yii::app()->db->createCommand('select * from case_info_space where account_id='.$staff['account_id'].' or (account_id!='.$staff['account_id'].' and city_id='.$staff['city_id'].')');
        $space = $space->queryAll();

        //查找所有颜色
        $color = yii::app()->db->createCommand('select * from case_info_color where account_id='.$staff['account_id']);
        $color = $color->queryAll();

        //查找所有风格
        $style = yii::app()->db->createCommand('select * from case_info_style where account_id='.$staff['account_id']);
        $style = $style->queryAll();

        if(isset($_GET['ci_id'])){
            //参数表
            $ci_id = $_GET['ci_id'];

            //预备信息
            $case = yii::app()->db->createCommand('select * from case_info where CI_ID='.$ci_id);
            $case = $case->queryAll();

            //构造CI_Pic
            if(!empty($case)){
                $case = $case[0];    
            };
            
            $t = explode('/', $case['CI_Pic']);
            if(isset($t[0])){
                if($t[0] == 'upload'){
                    $case['CI_Pic'] = 'http://file.cike360.com/'.$this->add_string_to_url($case['CI_Pic'], 'sm');
                }else if($t[0] == ''){
                    $case['CI_Pic'] = 'http://file.cike360.com'.$this->add_string_to_url($case['CI_Pic'], 'sm');
                };
            };

            $this->render("upload_folder_poster", array(
                    'case' => $case,
                    'color' => $color,
                    'style' => $style,
                    'space' => $space
                ));
        }else{
            $this->render("upload_folder_poster", array(
                    'color' => $color,
                    'style' => $style,
                    'space' => $space
                ));    
        };
    }

    public function actionUpload_case_detail()
    {
        $token = $_GET['token'];

        $cr = array();

        if(isset($_GET['ci_id'])){
            //参数表
            $ci_id = $_GET['ci_id'];

            $case_resource = CaseResources::model()->findAll(array(
                    'condition' => 'CI_ID=:CI_ID && CR_Show=:CR_Show',
                    'params' => array(
                            ':CI_ID' => $ci_id,
                            ':CR_Show' => 1
                        )
                ));
            foreach ($case_resource as $key => $value) {

                $url;
                $url_lg;
                $t = explode('/', $value['CR_Path']);
                if(isset($t[0])){
                    if($value['CR_Type'] == 1){
                        if($t[0] == 'http:'){
                            $url = $value['CR_Path'].'?x-oss-process=image/resize,m_lfit,h_300,w_300';
                            $url_lg = $value['CR_Path'].'?x-oss-process=image/resize,m_lfit,h_1000,w_1000';
                        }else{
                            $url = 'http://file.cike360.com'.$value['CR_Path'];
                            $url_lg = 'http://file.cike360.com'.$value['CR_Path'];
                        };
                    }else{
                        $url = $value['CR_Path'];
                        $url_lg = 'images/video.jpg';
                    };
                };
                $item = array();
                $item['CR_ID'] = $value['CR_ID'];
                $item['CR_Path'] = $url;
                $item['CR_Path_lg'] = $url_lg;
                $item['CR_Type'] = $value['CR_Type'];
                $cr[] = $item;
            };
            $case_info = CaseInfo::model()->findByPk($ci_id);
        }else if(isset($_GET['service_person_id'])){
            //参数表
            $service_person_id = $_GET['service_person_id'];

            $service_person_img = ServicePersonImg::model()->findAll(array(
                    'condition' => 'service_person_id=:service_person_id && img_show=:img_show',
                    'params' => array(
                            ':service_person_id' => $service_person_id,
                            ':img_show' => 1
                        )
                ));
            $service_person_video = ServicePersonVideo::model()->findAll(array(
                    'condition' => 'service_person_id=:service_person_id && video_show=:video_show',
                    'params' => array(
                            ':service_person_id' => $service_person_id,
                            ':video_show' => 1
                        )
                ));
            $service_person = ServicePerson::model()->findByPk($service_person_id);
            
            //构造返回数据
            $case_info = array(
                    'CI_Name' => $service_person['name']
                );
            foreach ($service_person_video as $key => $value) {
                $item = array();
                $item['CR_ID'] = $value['id'];
                $item['CR_Path'] = $value['video_url'];
                $item['CR_Path_lg'] = 'images/video.jpg';
                $item['CR_Type'] = 2;
                $cr[] = $item;
            };

            foreach ($service_person_img as $key => $value) {
                $t = explode('/', $value['img_url']);
                $item = array();
                $item['CR_ID'] = $value['id'];
                if(isset($t[0])){
                    if($t[0] == ''){
                        $item['CR_Path'] = 'http://file.cike360.com'.$this->add_string_to_url($value['img_url'], 'sm');
                        $item['CR_Path_lg'] = 'http://file.cike360.com'.$this->add_string_to_url($value['img_url'], 'md');
                    }else if($t[0] == 'upload'){
                        $item['CR_Path'] = 'http://file.cike360.com/'.$this->add_string_to_url($value['img_url'], 'sm');
                        $item['CR_Path_lg'] = 'http://file.cike360.com/'.$this->add_string_to_url($value['img_url'], 'md');
                    }else{
                        $item['CR_Path'] = $value['img_url'].'?x-oss-process=image/resize,m_lfit,h_300,w_300';
                        $item['CR_Path_lg'] = $value['img_url'].'?x-oss-process=image/resize,m_lfit,h_1000,w_1000';
                    };
                };
                $item['CR_Type'] = 1;
                $cr[] = $item;
            };
        };
// echo json_encode($cr);die;
        //查找待执行订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,staff.name as designer_name,order_status from `order` o ".
            " left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done($tt);

        //取subarea
        $area_data = array();
        $area = OrderShowArea::model()->findAll(array(
                'order' => 'sort'
            ));
        foreach ($area as $key => $value) {
            if($value['type'] == 1 || $value['type'] == 11 || $value['type'] == 7){
                $item = array();
                $item['id'] = $value['id'];
                $item['name'] = $value['name'];
                $item['subarea'] = array();
                $subarea = OrderShowAreaSubarea::model()->findAll(array(
                        'condition' => 'father_area=:father_area',
                        'params' => array(
                                ':father_area' => $value['id']
                            ),
                        'order' => 'sort'
                    ));
                foreach ($subarea as $key1 => $value1) {
                    $t = array();
                    $t['id'] = $value1['id'];
                    $t['name'] = $value1['name'];
                    $item['subarea'][] = $t;
                };
                $area_data[] = $item;
            };
        };

        $this->render("upload_case_detail", array(
                'ci' => $case_info,
                'cr' => $cr,
                'order_data' => $order_data['doing'],
                'area_data' => $area_data
            ));
    }

    public function actionInsert_person_img_and_video()
    {
        //参数表
        $cr_list = $_POST['cr_list'];
        $service_person_id = $_POST['service_person_id'];

        // $cr_list = "http://inspitation-img-store.oss-cn-beijing.aliyuncs.com/case/M5ChBFcSQJ.mp4";
        // $service_person_id = "2";

        //预备信息
        $img = ServicePersonImg::model()->find(array(
                'condition' => 'service_person_id=:service_person_id',
                'params' => array(
                        ':service_person_id' => $service_person_id
                    ),
                'order' => 'sort DESC'
            ));
        $video = ServicePersonVideo::model()->find(array(
                'condition' => 'service_person_id=:service_person_id',
                'params' => array(
                        ':service_person_id' => $service_person_id
                    ),
                'order' => 'sort DESC'
            ));


        $t = explode(",",$cr_list);
        $resources = array();
        foreach ($t as $key => $value) {
            $t1 = explode(".", $value);
            $item = array();
            if(isset($t1[count($t1)-1])){
                if($t1[count($t1)-1] == "jpg" || $t1[count($t1)-1] == "png" || $t1[count($t1)-1] == "jpeg" || $t1[count($t1)-1] == "JPEG" || $t1[count($t1)-1] == "gif" || $t1[count($t1)-1] == "bmp" ){
                    $item['Cr_Type'] = 1 ;
                }else{
                    $item['Cr_Type'] = 2 ;
                };
            }else{
                $item['Cr_Type'] = 1;
            };
                
            $item['Cr_Path'] = $value;
            $resources[]=$item;
        };

        $i = 1;
        if(!empty($cr)){
            $i = $cr['CR_Sort'] + 1;    
        };
        
        foreach ($resources as $key => $value) {
            if($value['Cr_Type'] == 1){
                $admin = new ServicePersonImg;
                $admin->service_person_id = $service_person_id;
                $admin->img_url = $value['Cr_Path'];
                $admin->sort = $i;
                $admin->img_show = 1;
                $admin->update_time = date('y-m-d h:i:s',time());
                $admin->save();
            }else{
                $admin = new ServicePersonVideo;
                $admin->service_person_id = $service_person_id;
                $admin->video_url = $value['Cr_Path'];
                $admin->sort = $i;
                $admin->video_show = 1;
                $admin->update_time = date('y-m-d h:i:s',time());
                $admin->save();
            };
        };
    }

    public function actionDel_service_person_img_and_video()
    {
        //参数表
        $service_person_id = $_POST['service_person_id'];
        $img_list = $_POST['img_list'];


        //预备信息
        $img = explode(',', $img_list);

        //删除图片
        foreach ($img as $key => $value) {
            $t = explode('|', $value);
            if(isset($t[1])){
                if($t[1] == 1){
                    ServicePersonImg::model()->updateByPk($t[0], array('img_show' => 0));
                }else{
                    ServicePersonVideo::model()->updateByPk($t[0], array('video_show' => 0));
                };
            };
        };
    }

    public function actionDel_folder_img()
    {
        //参数表
        $folder_id = $_POST['folder_id'];
        $img_list = $_POST['img_list'];


        //预备信息
        $img = explode(',', $img_list);

        //删除图片
        foreach ($img as $key => $value) {
            echo LibraryFolderBind::model()->deleteAll('Folder_ID=:Folder_ID && Img_ID=:Img_ID', array(':Folder_ID' => $folder_id, ':Img_ID' => $value));
        };
    }
    
    public function actionNew_folder()
    {
        //参数表
        $folder_name = $_POST['folder_name'];
        $tab_id = $_POST['tab_id'];
        $tab_name = $_POST['tab_name'];
        $token = $_COOKIE['userid'];

        //新增tab
        if($tab_id == 0){
            $admin = new LibraryStaffFolderTab;
            $admin->staff_id = $token;
            $admin->name = $tab_name;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $tab_id = $admin->attributes['id'];
        };

        //新增收藏夹
        $admin = new LibraryStaffFolder;
        $admin->Folder_Name = $folder_name;
        $admin->Staff_ID = $token;
        $admin->tab_id = $tab_id;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
    }

    public function actionUpload_collection()
    {
        //参数表
        $folder_id = $_GET['folder_id'];
        $token = $_GET['token'];

        //取图片
        $folder = LibraryStaffFolder::model()->findByPk($folder_id);
        $result =  yii::app()->db->createCommand("select * from library_web_case_img ".
            " where Img_ID in (select Img_ID from library_folder_bind where Folder_ID=".$folder_id.")");
        $result =  $result->queryAll();

        $cr = array();
        foreach ($result as $key => $value) {
            $item = array();
            $item['Img_ID'] = $value['Img_ID'];
            $url;
            $url_lg;
            $i = explode('?x-oss-process=image/resize,m_lfit,h_200,w_200', $value['local_URL']);
            if($this->isImage($i[0])){
                $item['type'] = 1;
                $t = explode('/', $value['local_URL']);
                if($t[0] == '.'){
                    $url = "http://file.cike360.com" . ltrim($value['local_URL'],".");
                    $url_lg = "http://file.cike360.com" . ltrim($value['local_URL'],".");
                }else if($t[0] == ""){
                    $url = "http://file.cike360.com" . $this->add_string_to_url($value['local_URL'], 'xs');
                    $url_lg = "http://file.cike360.com" . $this->add_string_to_url($value['local_URL'], 'md');
                }else if($t[0] == 'http:'){
                    $url = $i[0].'?x-oss-process=image/resize,m_lfit,h_300,w_300';
                    $url_lg = $i[0].'?x-oss-process=image/resize,m_lfit,h_1000,w_1000';
                };
            }else{ 
                $item['type'] = 2;
                $url = 'images/video.png';
                $url_lg = $i[0];
            };
            $item['local_URL'] = $url;
            $item['local_URL_lg'] = $url_lg;
            $cr[] = $item;
        };

        //查找待执行订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,staff.name as designer_name,order_status from `order` o ".
            " left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done($tt);

        //取subarea
        $area_data = array();
        $area = OrderShowArea::model()->findAll(array(
                'order' => 'sort'
            ));
        foreach ($area as $key => $value) {
            if($value['type'] == 1 || $value['type'] == 11 || $value['type'] == 7){
                $item = array();
                $item['id'] = $value['id'];
                $item['name'] = $value['name'];
                $item['subarea'] = array();
                $subarea = OrderShowAreaSubarea::model()->findAll(array(
                        'condition' => 'father_area=:father_area',
                        'params' => array(
                                ':father_area' => $value['id']
                            ),
                        'order' => 'sort'
                    ));
                foreach ($subarea as $key1 => $value1) {
                    $t = array();
                    $t['id'] = $value1['id'];
                    $t['name'] = $value1['name'];
                    $item['subarea'][] = $t;
                };
                $area_data[] = $item;
            };
        };

        $this->render('upload_collection',array(
                'folder_name' => $folder['Folder_Name'],
                'img' => $cr,
                'order_data' => $order_data['doing'],
                'area_data' => $area_data
            ));
    }

    function isImage($filename){
        $t = explode('.', $filename);
        $m = strtolower($t[count($t)-1]);
        if($m == 'gif' || $m == 'jpeg' || $m == 'png' || $m == 'bmp' || $m == 'jpg' || $m == 'psd' || $m == 'ai' || $m == 'icon'){
            return true;
        }else{
            return false;
        };
    }
 


    public function actionUpload_case()
    {
        $this->render('upload_case');
    }

    public function actionEdit_case()
    {
        $url = "http://file.cike360.com";

        //取资源信息
        $data = CaseResources::model()->findAll(array(
                'condition' => 'CI_ID=:CI_ID',
                'params' => array(
                        ':CI_ID' => $_GET['ci_id'],
                    ),
                'order' => 'CR_Sort',
            ));
        $resources = array();
        foreach ($data as $key => $value) {
            $t = explode('/', $value['CR_Path']);
            $result = yii::app()->db->createCommand("select case_resources_product.id as bind_id,name,unit,unit_price from case_resources_product left join supplier_product on supplier_product_id=supplier_product.id where case_resources_product.CR_ID=".$value['CR_ID']);
            $result = $result->queryAll();
            $item = array();
            $item['product'] = $result;
            if(isset($t[0])){
                if($t[0] == 'http:'){
                    $item['CR_Path'] = $value['CR_Path']; 
                }else{
                    $item['CR_Path'] = $url.$this->add_string_to_url($value['CR_Path'], "xs"); 
                };
            }else{
                $item['CR_Path'] = "images/cover.jpg";
            };
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];    
        }else{
            $Pic = "images/cover.jpg";
        };
        

        //取场布产品信息
        $product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0,
                        ':supplier_type_id' => 20, 

                    )
            ));
        foreach ($product as $key => $value) {
            $t = explode(".", $value['ref_pic_url']);
            if(isset($t[0]) && isset($t[1])){
                $product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];
            }else{
                $product[$key]['ref_pic_url'] = "images/cover.jpg";
            };
        };
        $tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id']
                    )
            ));
        /*print_r($product);die;*/
        $this->render("edit_case",array(
                'pic' => $Pic,
                'resources' => $resources,
                'case' => $case,
                'case_data' => $product,
                'tap' => $tap,
            ));
    }

    public function actionUpload_oss_img()
    {
        //参数表
        $img_url = $_POST['img_url'];
        $folder_id = $_POST['folder_id'];

        //定义小图比例
        $bucket_url = 'http://inspitation-img-store.oss-cn-beijing.aliyuncs.com/';
        $xs = '?x-oss-process=image/resize,m_lfit,h_200,w_200';
        $md = '?x-oss-process=image/resize,m_lfit,h_600,w_600';

        //新增 library_web_case_img
        $admin = new LibraryWebCaseImg;
        $admin->local_URL = $bucket_url.$img_url.$xs;
        $admin->local_URL_lg = $bucket_url.$img_url.$md;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $img_id = $admin->attributes['Img_ID'];

        //新增 library_folder_bind
        $admin = new LibraryFolderBind;
        $admin->Folder_ID = $folder_id;
        $admin->Img_ID = $img_id;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
    }

    public function actionBatch_upload_oss_img()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $img_url_list = $post->img_url_list;   //  img_url,img_url,img_url
        $folder_id = $post->folder_id;

        // $img_url_list = '123,456';   //  img_url,img_url,img_url
        // $folder_id = 1;

        //定义小图比例
        $bucket_url = 'http://inspitation-img-store.oss-cn-beijing.aliyuncs.com/';
        $xs = '?x-oss-process=image/resize,m_lfit,h_200,w_200';
        $md = '?x-oss-process=image/resize,m_lfit,h_600,w_600';


        $t = explode(',', $img_url_list);
        foreach ($t as $key => $value) {
            $tt = explode('/', $value);
            if(isset($tt[0])){
                if($tt[0] == 'http:'){
                    $bucket_url = '';
                };
            };
            //新增 library_web_case_img
            $admin = new LibraryWebCaseImg;
            $admin->local_URL = $bucket_url.$value;
            $admin->local_URL_lg = $bucket_url.$value;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
            $img_id = $admin->attributes['Img_ID'];

            //新增 library_folder_bind
            $admin = new LibraryFolderBind;
            $admin->Folder_ID = $folder_id;
            $admin->Img_ID = $img_id;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
        };
    }

    public function actionUpload_set1()
    {
        $account_id = $_COOKIE['account_id'];
        if(!isset($_GET['type'])){
            $product_list = array();
            $final_price = 0;
            $cut_price = 0;
            if (isset($_GET['model_id'])) {
                if (!empty($_GET['model_order'])) {
                    $order = Order::model()->findByPk($_GET['model_order']);
                    $cut_price = $order['cut_price'];

                    $product = yii::app()->db->createCommand("select sp.id,op.actual_price,op.actual_unit_cost,op.unit,sp.name,sp.ref_pic_url,serp.id as service_product_id ".
                        " from order_product op left join supplier_product sp on op.product_id=sp.id ".
                        " left join service_product serp on sp.service_product_id=serp.id".
                        " where op.order_id=".$_GET['model_order']);
                    $product = $product->queryAll();

                    foreach ($product as $key => $value) {
                        $img = ServiceProductImg::model()->find(array(
                                'condition' => 'service_product_id=:service_product_id',
                                'params' => array(
                                        ':service_product_id' => $value['service_product_id']
                                    )
                            ));
                        $url = 'http://file.cike360.com' . $this->add_string_to_url($img['img_url'], 'sm');
                        $t = explode('/', $img['img_url']);
                        if(isset($t[0])){
                            if($t[0] == 'http:'){
                                $url = $img['img_url'];
                            };
                        };
                        $item = array();
                        $item['product_id'] = $value['id'];
                        $item['name'] = $value['name'];
                        $item['price'] = $value['actual_price'];
                        $item['amount'] = $value['unit'];
                        $item['cost'] = $value['actual_unit_cost'];
                        $item['ref_pic_url'] = $url;
                        $product_list[] = $item;
                    };
                }
            }
            $decoration_subarea =  yii::app()->db->createCommand("select * from order_show_area_subarea sub ".
                " where father_area in (select id from order_show_area where type=1) order by father_area");
            $decoration_subarea =  $decoration_subarea->queryAll();

            $person_type = SupplierType::model()->findAll(array(
                    'condition' => 'role=:role',
                    'params' => array(
                            ':role' => 2
                        ),
                    'order' => 'sort'
                ));
            $lss_type = SupplierType::model()->findAll(array(
                    'condition' => 'role=:role',
                    'params' => array(
                            ':role' => 3
                        ),
                    'order' => 'sort'
                ));
            $goods_type = SupplierType::model()->findAll(array(
                    'condition' => 'role=:role',
                    'params' => array(
                            ':role' => 5
                        ),
                    'order' => 'sort'
                ));
            
            

/******************** 构造商品 *********************/
            $all_product = array();
            
            
            $staff_company = StaffCompany::model()->findByPk($account_id);

            //中央库房
            $service_product = $this->select_product($staff_company['city_id'], 4);
            $profit_rate = $this->get_profit_rate($account_id, 1);
            $all_product = $this->add_product_list($service_product, $all_product, $profit_rate); 

            //婚礼设备 
            $service_product = $this->select_product($staff_company['city_id'], 3);
            $profit_rate = $this->get_profit_rate($account_id, 4);
            $all_product = $this->add_product_list($service_product, $all_product, $profit_rate);

            //人员
            $service_product = $this->select_person_product($account_id);
            $profit_rate = $this->get_profit_rate($account_id, 3);
            $all_product = $this->add_product_list($service_product, $all_product, $profit_rate);


            //商品
            $service_product = $this->select_product($staff_company['city_id'], 5);
            $profit_rate = $this->get_profit_rate($account_id, 2);
            $all_product = $this->add_product_list($service_product, $all_product, $profit_rate);





            $area = OrderShowArea::model()->findAll(array(
                    'condition' => 'id != :id',
                    'params' => array(
                            ':id' => 1
                        )
                ));
            // print_r($area);die;
            // print_r($decoration_tap);die;
            // print_r($supplier_product);die;
            $this -> render("upload_set1",array(
                'decoration_tap'    => $decoration_subarea,
                'person_type' => $person_type,
                'lss_type' => $lss_type,
                'goods_type' => $goods_type,
                'supplier_product'  => $all_product,
                'product_list'      => $product_list,
                'final_price'      => $final_price,
                'area' => $area,
                'cut_price' => $cut_price
            ));
        }else if($_GET['type'] == 'theme'){
            $result = yii::app()->db->createCommand("select product_name,price,unit,service_product.id as product_id,service_product.service_type as service_type,cost,case_info.CI_Pic,ref_pic_url from service_product left join service_person on service_person_id=service_person.id left join case_info on service_person.staff_id=case_info.CT_ID where service_product.product_show=1 and CI_Type in (6,13,14,15)");
            $service_person = $result->queryAll();
            $data = array();
            foreach ($service_person as $key => $value) {
                $pic = "";
                if($value['service_type'] == 3 || $value['service_type'] == 4 || $value['service_type'] == 5 || $value['service_type'] == 6){
                    $pic = $value['CI_Pic'];
                }else{
                    $pic = $value['ref_pic_url'];
                };

                $item = array(
                    'name' => $value['product_name'],
                    'unit_price' => $value['price'],
                    'unit' => $value['unit'],
                    'id' => $value['product_id'],
                    'supplier_type_id' => $value['service_type'],
                    'unit_cost' => $value['cost'],
                    'ref_pic_url' => $pic,
                );
                $data[] = $item;
            };
            $this -> render("upload_set1",array(
                'supplier_product' => $data,
            ));
        }else if($_GET['type'] == 'menu'){
            $account_id = $_COOKIE['account_id'];

            $product_list = array();
            $final_price = 0;
            if (isset($_GET['model_id'])) {
                if (!empty($_GET['model_order'])) {
                    $product = yii::app()->db->createCommand("select sp.id,op.actual_price,op.actual_unit_cost,op.unit,sp.name,sp.ref_pic_url ".
                        " from order_product op left join supplier_product sp on op.product_id=sp.id ".
                        " where op.order_id=".$_GET['model_order']);
                    $product = $product->queryAll();

                    foreach ($product as $key => $value) {
                        $item = array();
                        $item['product_id'] = $value['id'];
                        $item['name'] = $value['name'];
                        $item['price'] = $value['actual_price'];
                        $item['amount'] = $value['unit'];
                        $item['cost'] = $value['actual_unit_cost'];
                        $item['ref_pic_url'] = 'http://file.cike360.com' . $this->add_string_to_url($value['ref_pic_url'], 'sm');
                        $product_list[] = $item;
                    };
                }
            }

            $dish_type = DishType::model()->findAll();
            $supplier_product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && standard_type=:standard_type && product_show=:product_show',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':standard_type' => 0,
                            ':product_show' => 1,
                        )));
            $dish=array();
            foreach ($supplier_product as $key => $value) {
                $t=explode('.', $value['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $supplier_product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];    
                };
                if($value['supplier_type_id']==2){
                    $dish[]=$value;
                }
            };
            $this -> render("upload_set1",array(
                'dish_type' => $dish_type,
                'supplier_product' => $dish,
                'product_list' => $product_list
            ));
        };
    }

    public function select_product($city_id, $role)
    {
        // $service_product = yii::app()->db->createCommand("select * ".
        //     " from service_product ".
        //     " where service_person_id in (select id from service_person where staff_id in (select id from staff where city_id=".$city_id.")) ".
        //     " and service_type in (select id from supplier_type where role=".$role.") ".
        //     " and product_show=1 ");
        // $service_product = $service_product->queryAll();
        $service_product = yii::app()->db->createCommand("select * ".
            " from service_product ".
            " where subarea in ".
            " (select id from order_show_area_subarea where supplier_type in ".
                " (select id from supplier_type where role=".$role.")) ".
            " and product_show=1 and service_person_id!=0");
        $service_product = $service_product->queryAll();
        return $service_product;
    }

    public function select_person_product($account_id)
    {
        // $service_product = yii::app()->db->createCommand("select * ".
        //     " from service_product ".
        //     " where id in (select service_product_id from supplier_product where account_id=".$account_id." and supplier_type_id in (select id from supplier_type where role=2)) ".
        //     " and product_show=1 ");
        // $service_product = $service_product->queryAll();
        $service_product = yii::app()->db->createCommand("select * from service_product where product_show=1 and service_type in (select id from supplier_type where role=2)");
        $service_product = $service_product->queryAll();

        return $service_product;
    }

    public function get_profit_rate($account_id, $area_type)
    {
        $profit_rate = StaffCompanyProfitRate::model()->find(array(
                'condition' => 'account_id=:account_id && area_type=:area_type',
                'params' => array(
                        ':account_id' => $account_id,
                        ':area_type' => $area_type
                    )
            ));
        return $profit_rate['profit_rate'];
    }

    public function add_product_list($service_product, $all_product, $profit_rate)
    {
        foreach ($service_product as $key => $value) {
            $url = '';
            $service_product_img = ServiceProductImg::model()->find(array(
                    'condition' => 'service_product_id=:service_product_id',
                    'params' => array(
                            ':service_product_id' => $value['id']
                        )
                ));
            $t = explode('/', $service_product_img['img_url']);
            if(isset($t[0])){
                if($t[0] == 'http:'){
                    $url = $service_product_img['img_url'];
                }else{
                    $url = "http://file.cike360.com".$this->add_string_to_url($service_product_img['img_url'], 'sm');
                };
            };
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['product_name'];
            $item['unit_price'] = round($value['cost']/(1-$profit_rate));
            $item['unit_cost'] = $value['cost'];
            $item['decoration_tap'] = $value['decoration_tap'];
            $item['supplier_type_id'] = $value['service_type'];
            $item['ref_pic_url'] = $url;
            $item['type'] = 'service_product'; 
            $all_product[] = $item;                
        };
        return $all_product;
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

    public function actionUpload_set2()
    {
        $case = array();
        $resources = array();
        $pic="";

        if($_GET['type'] == 'theme' && isset($_GET['ci_id'])){
            $case = CaseInfo::model()->findByPk($_GET['ci_id']);
            if(!empty($case)){
                $t=explode('.', $case['CI_Pic']);
                $pic = $case['CI_Pic'];
                $case['CI_Pic'] = "http://file.cike360.com" .$t[0]. "_sm." .$t[1];
            };
            $resources = CaseResources::model()->findAll(array(
                    'condition' => 'CI_ID=:CI_ID && CR_Type=:CR_Type',
                    'params' => array(
                            ':CI_ID' => $_GET['ci_id'],
                            ':CR_Type' => 1
                        )
                ));
            // var_dump($resources);die;
            foreach ($resources as $key => $value) {
                $t=explode('.', $value['CR_Path']);
                $resources[$key]['CR_Path'] = "http://file.cike360.com" .$t[0]. "_sm." .$t[1];
            }
        }


        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        // foreach ($resources as $key => $value) {
        //     echo $value['CR_Path'];die;
        // };
        $this->render("upload_set2",array(
                'hotel' => $hotel,
                'case' => $case,
                'resources' => $resources,
                'pic' => $pic
            ));
    }

    public function actionEdit_set1()
    {
        $account_id = $_COOKIE['account_id'];
        $decoration_tap = array();
        if(!isset($_GET['type']) || $_GET['type'] == 'theme'){
            $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
                "condition" => "account_id = :account_id",
                "params"    => array(
                    ":account_id" => $account_id,
                    )));
        }else if($_GET['type'] == 'menu'){
            $decoration_tap = DishType::model()->findAll();
        };
            
        // $supplier_product = SupplierProduct::model()->findAll(array(
        //     'condition' => 'account_id=:account_id && standard_type=:standard_type && product_show=:product_show',
        //         'params' => array(
        //                 ':account_id' => $_COOKIE['account_id'],
        //                 ':standard_type' => 0,
        //                 ':product_show' => 1,
        //             )));
        // foreach ($supplier_product as $key => $value) {
        //     $t=explode('.', $value['ref_pic_url']);
        //     if(isset($t[0]) && isset($t[1])){
        //         $supplier_product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];    
        //     };
        // };
        $decoration_tap = array();
        $supplier_product = array();
        $product_list = array();
        $Wedding_set = array();
        if(!isset($_GET['type']) || $_GET['type'] == 'menu'){
            $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
                "condition" => "account_id = :account_id",
                "params"    => array(
                    ":account_id" => $account_id,
                    )));
            $supplier_product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && standard_type=:standard_type && product_show=:product_show',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':standard_type' => 0,
                            ':product_show' => 1,
                        )));
            foreach ($supplier_product as $key => $value) {
                $t=explode('.', $value['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $supplier_product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];    
                };
            };

            $Wedding_set = Wedding_set::model()->findByPk($_GET['ct_id']);
            if($Wedding_set['product_list']!=""){
                $t = explode(",", $Wedding_set['product_list']);
                foreach ($t as $key => $value) {
                    $item = array();
                    $t1 = explode("|", $value);
                    $item['product_id'] = $t1[0];
                    $item['price'] = $t1[1];
                    $item['amount'] = $t1[2];
                    $item['cost'] = $t1[3];
                    $product_list[]=$item;
                };
            };
            // print_r($decoration_tap);die;
            // print_r($supplier_product);die;
        }else if($_GET['type'] == 'theme'){
            $result = yii::app()->db->createCommand("select product_name,price,unit,service_product.id as product_id,service_product.service_type as service_type,cost,case_info.CI_Pic,ref_pic_url from service_product left join service_person on service_person_id=service_person.id left join case_info on service_person.staff_id=case_info.CT_ID where service_product.product_show=1 and CI_Type in (6,13,14,15)");
            $service_person = $result->queryAll();
            $data = array();
            foreach ($service_person as $key => $value) {
                $pic = "";
                if($value['service_type'] == 3 || $value['service_type'] == 4 || $value['service_type'] == 5 || $value['service_type'] == 6){
                    $pic = $value['CI_Pic'];
                }else{
                    $pic = $value['ref_pic_url'];
                };

                $item = array(
                    'name' => $value['product_name'],
                    'unit_price' => $value['price'],
                    'unit' => $value['unit'],
                    'id' => $value['product_id'],
                    'supplier_type_id' => $value['service_type'],
                    'unit_cost' => $value['cost'],
                    'ref_pic_url' => $pic,
                );
                $data[] = $item;
            };
            $supplier_product = $data;

            $Wedding_set = Wedding_set_theme::model()->findByPk($_GET['ct_id']);
            if($Wedding_set['service_product_list']!=""){
                $t = explode(",", $Wedding_set['service_product_list']);
                foreach ($t as $key => $value) {
                    $item = array();
                    $t1 = explode("|", $value);
                    $item['product_id'] = $t1[0];
                    $item['price'] = $t1[1];
                    $item['amount'] = $t1[2];
                    $item['cost'] = $t1[3];
                    $product_list[]=$item;
                };
            };
        };

        
        // print_r($decoration_tap);die;
        $this->render("edit_set1",array(
            'wedding_set' => $Wedding_set,
            'decoration_tap' => $decoration_tap,
            'supplier_product' => $supplier_product,
            'product_list' => $product_list,
            ));
    }

    public function actionEdit_set2()
    {
        $order_model = array();
        if($_GET['model_id'] != "" || $_GET['model_order'] != ""){
            $model = OrderModel::model()->findByPk($_GET['model_id']);
            $t = explode('/', $model['poster_img']);
            $url = '';
            if(isset($t[0])){
                if($t[0] == 'http:'){
                    $url = $model['poster_img'];
                }else if($t[0] == ''){
                    $url = 'http://file.cike360.com' . $model['poster_img'];
                }else if($t[0] == 'upload'){
                    $url = 'http://file.cike360.com/' . $model['poster_img'];
                };
            };
            $order_model['id'] = $model['id'];
            $order_model['name'] = $model['name'];
            $order_model['poster_img'] = $url;
            $order_model['poster_img_data'] = $model['poster_img'];
            $order_model['type_id'] = $model['type_id'];
        }else{
            $order_model['id'] = '';
            $order_model['name'] = '';
            $order_model['poster_img'] = '';
            $order_model['poster_img_data'] = '';
            $order_model['type_id'] = '';
        }
        //取 medel_type
        $model_type = OrderModelType::model()->findAll();
        $this->render("edit_set2",array(
                'model' => $order_model,
                'model_type' => $model_type
            ));
    }

    public function actionUpload_product()
    {
        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=20");
        $supplier = $result->queryAll();
        $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        /*print_r($supplier);die;*/
        $this->render("upload_product",array(
                'supplier' => $supplier,
                'decoration_tap' => $decoration_tap,
                'supplier_type' => $supplier_type,
            ));
    }

    public function actionUpload_product_lss()
    {
        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id in (8,9,23)");
        $supplier = $result->queryAll();
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        /*print_r($supplier);die;*/
        $this->render("upload_product_lss",array(
                'supplier' => $supplier,
                'supplier_type' => $supplier_type,
            ));
    }

    function startwith($str,$pattern) {
        if(strpos($str,$pattern) === 0)
              return true;
        else
              return false;
    }

    public function actionEdit_product()
    {
        //取产品数据
        /*$result = yii::app()->db->createCommand("select * from supplier_product left join supplier on supplier_id=supplier.id left join staff on supplier.staff_id=staff.id left join service_person on staff.id=service_person.staff_id where service_person.id=".$_GET['service_person_id']);
        $product = $result->queryAll();*/
        if(!isset($_GET['type'])){
            $product = ServiceProduct::model()->findAll(array(
                    'condition' => 'service_person_id=:service_person_id && product_show=:product_show',
                    'params' => array(
                            ':service_person_id' => $_GET['service_person_id'],
                            ':product_show' => 1,
                        ),
                ));

            // print_r($product);die;
            $this->render('edit_product',array(
                    'product' => $product,
                ));
        }else{
            $result = yii::app()->db->createCommand("select * ".
                " from supplier_product sp1 left join service_product sp2 on sp1.service_product_id=sp2.id ".
                " where sp1.account_id=".$_COOKIE['account_id']." and sp2.product_show=1 and sp2.service_person_id=".$_GET['service_person_id']);
            $result = $result->queryAll();
            $this->render('edit_product',array(
                    'product' => $result,
                ));
        }
    }

    public function actionEdit_product_detail()
    {
        if(!isset($_GET['service_product_id'])){
            $this->render('edit_product_detail');
        }else if(!isset($_GET['type'])){
            $service_product = ServiceProduct::model()->findByPk($_GET['service_product_id']);
            if(isset($service_product['ref_pic_url'])){
                $t=explode(".",$service_product['ref_pic_url']);
                //print_r($service_product['ref_pic_url']);die;
                $service_product['ref_pic_url'] = "http://file.cike360.com".$t[0]."_sm.".$t[1];
            };
            // print_r($service_product);die;
            $this->render('edit_product_detail',array(
                'product' => $service_product
            ));
        }else if(isset($_GET['type'])){
            $supplier_product = SupplierProduct::model()->find(array(
                    'condition' => 'account_id=:account_id && service_product_id=:service_product_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':service_product_id' => $_GET['service_product_id']
                        )
                ));
            if(isset($supplier_product['ref_pic_url'])){
                $t=explode(".",$supplier_product['ref_pic_url']);
                //print_r($service_product['ref_pic_url']);die;
                $supplier_product['ref_pic_url'] = "http://file.cike360.com".$t[0]."_sm.".$t[1];
            };
            // print_r($supplier_product);die;
            $this->render('edit_product_detail',array(
                'product' => $supplier_product
            ));
        }
    }

    public function actionCase_upload()
    {
        /*$_POST['CI_Name'] = 333;
        $_POST['CI_Pic'] = 333;
        $_POST['CI_Remarks'] = 333;*/
        $color = 0;
        $style = 0;
        $space = 0;
        if(isset($_POST['color_id'])){
            $color = $_POST['color_id'];
        };
        if(isset($_POST['style_id'])){
            $style = $_POST['style_id'];
        };
        if(isset($_POST['space_id'])){
            $space = $_POST['space_id'];
        };


        $data = new CaseInfo;
        $data->CI_Name = $_POST['CI_Name'];
        $data->CI_Place = "";
        $data->color = $color;
        $data->style = $style;
        $data->space = $space;
        // $data->CI_Pic = $_POST['CI_Pic'];
        // $data ->CI_Time = $_POST['CI_Time'];
        $data->CI_Sort = 1;
        $data->CI_Show = $_POST['CI_Show'];
        $data->CI_Remarks = "";
        if(!isset($_POST['CI_Type'])){
            $data->CI_Type = 2;    
            if(isset($_POST['account_id'])){
                if($_POST['account_id'] == 0){
                    $data->CT_ID=1;
                };
            };
        }else{
            $data->CI_Type = $_POST['CI_Type'];
            if(isset($_POST['account_id'])){
                if($_POST['account_id'] == 0){
                    $data->CT_ID=1;
                };
            };
        };
        $data->save();

        $CI_ID = $data->attributes['CI_ID'];
        
        $data = new CaseBind;
        if($_POST['CI_Type'] != 1 && $_POST['CI_Type'] != 4){
            $data->CB_Type = 1;
            $data->TypeID = $_POST['account_id'];
        }else{
            $data->CB_Type = 4;
            $data->TypeID = 0;
        };
        $data->CI_ID = $CI_ID;
        $data->save();


        //resource 处理
        //$_POST['resource']= '/upload/wutai0120160515094855.jpg,/upload/wutai0220160515094857.png,/upload/wutai0320160515094859.png,/upload/wutai0420160515094900.jpg,/upload/wutai0520160515094901.jpg,/upload/wutai0620160515094902.jpg,/upload/wutai0720160515094903.jpg,/upload/wutai0820160515094905.jpg';
        $t = explode(",",$_POST['case_resource']);
        $resources = array();
        foreach ($t as $key => $value) {
            $t1 = explode(".", $value);
            $item = array();
            if(isset($t[1])){
                if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                    $item['Cr_Type'] = 1 ;
                }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                    $item['Cr_Type'] = 2 ;
                };
            }else{
                $item['Cr_Type'] = 1;
            };
                
            $item['Cr_Path'] = $value;
            $resources[]=$item;
        };
        /*print_r($resources);die;*/


        $i = 1;
        foreach ($resources as $key => $value) {
            // $data = new CaseResources;
            // $data ->CI_ID = $CI_ID;
            // $data ->CR_Show = 1;
            // $data ->CR_Type = $value['Cr_Type'];
            // $data ->CR_Name = "";
            // $data ->CR_Path = $value['Cr_Path'];
            // $data ->CR_Remarks = "";
            // $data ->CR_Sort = $i++;
            // $data->save();
        };
    }

    public function actionCase_detail_upload()
    {
        //参数表
        $cr_list = $_POST['cr_list'];
        $ci_id = $_POST['ci_id'];

        //预备信息
        $cr = CaseResources::model()->find(array(
                'condition' => 'CI_ID=:CI_ID',
                'params' => array(
                        ':CI_ID' => $ci_id
                    ),
                'order' => 'CR_Sort DESC'
            ));


        $t = explode(",",$cr_list);
        $resources = array();
        foreach ($t as $key => $value) {
            $t1 = explode(".", $value);
            $item = array();
            if(isset($t1[count($t1)-1])){
                if($t1[count($t1)-1] == "jpg" || $t1[count($t1)-1] == "png" || $t1[count($t1)-1] == "jpeg" || $t1[count($t1)-1] == "JPEG" || $t1[count($t1)-1] == "gif" || $t1[count($t1)-1] == "bmp" ){
                    $item['Cr_Type'] = 1 ;
                }else {
                    $item['Cr_Type'] = 2 ;
                };
            }else{
                $item['Cr_Type'] = 1;
            };
                
            $item['Cr_Path'] = $value;
            $resources[]=$item;
        };

        $i = 1;
        if(!empty($cr)){
            $i = $cr['CR_Sort'] + 1;    
        };
        
        foreach ($resources as $key => $value) {
            $data = new CaseResources;
            $data ->CI_ID = $ci_id;
            $data ->CR_Show = 1;
            $data ->CR_Type = $value['Cr_Type'];
            $data ->CR_Name = "";
            $data ->CR_Path = $value['Cr_Path'];
            $data ->CR_Remarks = "";
            $data ->CR_Sort = $i++;
            $data->save();
        };
    }

    public function actionDecoration_tap()
    {
        $data = new SupplierProductDecorationTap;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->name = $_POST['name'];
        $data ->pic = $_POST['pic'];
        $data ->update_time = $_POST['update_time'];
        $data->save();
    }

    public function actionProduct_upload()
    {
        $data = new SupplierProduct;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->supplier_id = (int)$_POST['supplier_id']; 
        $data ->supplier_type_id = $_POST['supplier_type_id'];
        $data ->decoration_tap = $_POST['decoration_tap'];
        $data ->dish_type = $_POST['dish_type'];
        $data ->standard_type = $_POST['standard_type'];
        $data ->name = $_POST['name'];
        $data ->category = $_POST['category'];
        $data ->unit_price = $_POST['unit_price'];
        $data ->unit_cost = $_POST['unit_cost'];
        $data ->unit = $_POST['unit'];
        $data ->service_charge_ratio = $_POST['service_charge_ratio'];
        $data ->ref_pic_url = $_POST['ref_pic_url'];
        $data ->description = $_POST['description'];
        $data ->update_time = date('y-m-d h:i:s',time());
        $data->save();
    }

    public function actionProduct_edit()
    {
        SupplierProduct::model()->updateByPk($_POST['product_id'],array(
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'supplier_id' => $_POST['supplier_id'],
            'supplier_type_id' => $_POST['supplier_type_id'],
            'decoration_tap' => $_POST['decoration_tap'],
            'unit' => $_POST['unit'],
            'unit_price' => $_POST['unit_price'],
            'unit_cost' => $_POST['unit_cost'],
            'ref_pic_url' => $_POST['ref_pic_url'],
        ));
    }

    public function actionSupplier_add()
    {
        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:telephone',
                'params' => array(
                        ':telephone' => $_POST['telephone']
                    )
            ));
        $id="";
        if(empty($staff)){
            $data = new Staff;
            $data ->account_id = $_COOKIE['account_id'];
            $data ->name = $_POST['name'];
            $data ->telephone = $_POST['telephone'];
            $data ->department_list = "[4]";
            $data ->update_time = $_POST['update_time'];
            $data ->save();
            //查找新增的员工ID
            $id = $data->attributes['id'];
        }else{
            $id = $staff['id'];
        };  

        //新增供应商
        $data = new Supplier;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->type_id = $_POST['supplier_type'];
        $data ->staff_id = $id;
        $data ->contract_url = "";
        $data ->update_time = $_POST['update_time'];
        $data ->save();
    }

    public function actionTap_add()
    {
        $data = new SupplierProductDecorationTap;
        $data ->account_id = $_POST['account_id'];
        $data ->name = $_POST['name'];
        $data ->pic = $_POST['pic'];
        $data ->update_time = $_POST['update_time'];
        $data ->save();
    }

    public function actionDel_resource()
    {
        ServicePersonImg::model()->deleteByPk($_POST['CR_ID']); 
    }

    public function actionBind_product()
    {
        $data = new CaseResourcesProduct;
        $data ->CR_ID = $_POST['CR_ID'];
        $data ->supplier_product_id = $_POST['supplier_product_id'];
        $data ->update_time = date('y-m-d h:i:s',time());
        $data ->save();
    }

    public function actionDel_bind()
    {
        CaseResourcesProduct::model()->deleteByPk($_POST['bind_id']); 
    }

    public function actionCase_edit()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Name'=>$_POST['CI_Name'],'CI_Show'=>$_POST['CI_Show'],'CI_Pic'=>$_POST['CI_Pic']));
        if($_POST['case_resource'] != ""){
            $t = explode(",",$_POST['case_resource']);
            $resources = array();
            foreach ($t as $key => $value) {
                $t1 = explode(".", $value);
                $item = array();
                if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                    $item['Cr_Type'] = 1 ;
                }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                    $item['Cr_Type'] = 2 ;
                }
                $item['Cr_Path'] = $value;
                $resources[]=$item;
            };
            /*print_r($resources);die;*/
            $i = $_POST['CR_Sort']+1;
            foreach ($resources as $key => $value) {
                $data = new CaseResources;
                $data ->CI_ID = $_POST['CI_ID'];
                $data ->CR_Show = 1;
                $data ->CR_Type = $value['Cr_Type'];
                $data ->CR_Name = "";
                $data ->CR_Path = $value['Cr_Path'];
                $data ->CR_Remarks = "";
                $data ->CR_Sort = $i++;
                $data->save();
            };
        };
    }

    public function actionCase_data_edit()
    {
        //参数表
        $ci_id = $_POST['ci_id'];
        $CI_Name = $_POST['CI_Name'];
        // $CI_Pic = $_POST['CI_Pic'];
        $color = 0;
        $style = 0;
        $space = 0;
        if(isset($_POST['color_id'])){
            $color = $_POST['color_id'];
        };
        if(isset($_POST['style_id'])){
            $style = $_POST['style_id'];
        };
        if(isset($_POST['space_id'])){
            $space = $_POST['space_id'];
        };

        //修改数据
        CaseInfo::model()->updateByPk($ci_id, array('CI_Name' => $CI_Name, 'color' => $color_id, 'style' => $style_id, 'space' => $space_id));
    }

    public function actionSet_upload()
    {
        $id = "";
        if(isset($_POST['category'])){
            if($_POST['category'] != 5 && !isset($_POST['CI_ID'])){//如果不是主题婚礼（category= 5）的新增（!isset($_POST['CI_ID']）
                $data = new Wedding_set;
                $data ->staff_hotel_id = $_POST['staff_hotel_id'];
                $data ->name = $_POST['CI_Name'];
                $data ->category = $_POST['category'];
                $data ->final_price = $_POST['final_price'];
                $data ->feast_discount = $_POST['feast_discount'];
                $data ->other_discount = $_POST['other_discount'];
                $data ->product_list = $_POST['product_list'];
                $data ->set_show = 1;
                $data ->update_time = date('y-m-d h:i:s',time());
                $data->save();
                $id = $data->attributes['id'];
            }else{
                $data = new Wedding_set_theme;
                $data ->staff_hotel_id = $_POST['staff_hotel_id'];
                $data ->name = $_POST['CI_Name'];
                $data ->category = $_POST['category'];
                $data ->final_price = $_POST['final_price'];
                $data ->feast_discount = $_POST['feast_discount'];
                $data ->other_discount = $_POST['other_discount'];
                $data ->service_product_list = $_POST['product_list'];
                $data ->set_show = 1;
                $data ->update_time = date('y-m-d h:i:s',time());
                $data->save();
                $id = $data->attributes['id'];

                $staff_hotel = StaffHotel::model()->findAll(array(
                        'condition' => 'account_id = :account_id',
                        'params' => array(
                                ':account_id' => $_POST['account_id']
                            )
                    ));
                $t = explode(',', $_POST['product_list']);
                $service_product_list = "";
                foreach ($t as $key => $value) {
                    $tem = explode('|', $value);
                    $supplier_product = SupplierProduct::model()->findAll(array(
                            'condition' => 'account_id=:account_id && service_product_id=:service_product_id',
                            'params' => array(
                                    ':account_id' => $_POST['account_id'],
                                    ':service_product_id' => $tem[0]
                                )
                        ));
                    $service_product_list .= $supplier_product[0]['id']."|".$tem[1]."|".$tem[2]."|".$tem[3].",";
                };

                $service_product_list = substr($service_product_list,0,strlen($service_product_list)-1);

                foreach ($staff_hotel as $key => $value) {
                    $data = new Wedding_set;
                    $data ->staff_hotel_id = $value['id'];
                    $data ->name = $_POST['CI_Name'];
                    $data ->category = $_POST['category'];
                    $data ->final_price = $_POST['final_price'];
                    $data ->feast_discount = $_POST['feast_discount'];
                    $data ->other_discount = $_POST['other_discount'];
                    $data ->product_list = $service_product_list;
                    $data ->set_show = 1;
                    $data ->theme_id = $id;
                    $data ->update_time = date('y-m-d h:i:s',time());
                    $data->save();
                };
            };
        }else{
            $data = new Wedding_set;
            $data ->staff_hotel_id = $_POST['staff_hotel_id'];
            $data ->name = $_POST['CI_Name'];
            $data ->category = 2;
            $data ->final_price = $_POST['final_price'];
            $data ->feast_discount = $_POST['feast_discount'];
            $data ->other_discount = $_POST['other_discount'];
            $data ->product_list = $_POST['product_list'];
            $data ->set_show = 1;
            $data ->update_time = date('y-m-d h:i:s',time());
            $data->save();
            $id = $data->attributes['id'];
        };

        $data = new CaseInfo;
        $data ->CI_Name = $_POST['CI_Name'];
        $data ->CI_Pic = $_POST['CI_Pic'];
        $data ->CI_Show = 1;
        $data ->CI_Type = $_POST['CI_Type'];
        $data ->CT_ID = $id;
        $data->save();
        $CI_ID = $data->attributes['CI_ID'];

        $data = new CaseBind;
        if($_POST['CI_Type'] != 4){
            $data ->CB_Type = 1;
            $data ->TypeID = $_POST['account_id'];    
        }else{
            $data ->CB_Type = 4;
            $data ->TypeID = 0;  
        }
        $data ->CI_ID = $CI_ID;
        $data->save();
        // $id = $data->attributes['id'];

        

        //复制于Case_edit()，改Cr_Type为CR_Type    ////////////////
        $t = explode(",",$_POST['case_resource']);
        $resources = array();
        foreach ($t as $key => $value) {
            $t1 = explode(".", $value);
            $item = array();
            if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                $item['CR_Type'] = 1 ;
            }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                $item['CR_Type'] = 2 ;
            }
            $item['CR_Path'] = $value;
            $resources[]=$item;
        }
        foreach ($resources as $key => $value) {
            $data = new CaseResources;
            $data ->CI_ID = $CI_ID;
            $data ->CR_Show = 1;
            $data ->CR_Type = $value['CR_Type'];
            $data ->CR_Path = $value['CR_Path'];
            $data->save();
        };
        $data->save();
    }

    public function actionSet_edit()
    {
        //参数表
        $name = $_POST['name'];
        $poster_img = $_POST['poster_img'];
        $account_id = $_POST['account_id'];
        $product_list = $_POST['product_list'];
        $model_type = $_POST['model_type'];
        $model_id = $_POST['model_id'];
        $model_order_id = $_POST['model_order'];
        $is_menu = $_POST['is_menu'];
        $cut_price = $_POST['cut_price'];

        // $name = "ceshi";
        // $poster_img = "/upload/IMG_474020161201103112.jpg";
        // $account_id = "95";
        // $product_list = "202|400|1|400|service_product,199|600|1|600|service_product";
        // $model_type = "1";
        // $model_id = "";
        // $model_order_id = "";

        if($model_order_id == "" && $model_id ==""){
            //新增订单
            $admin = new Order;
            $admin->account_id = 2;
            $admin->designer_id = 0;
            $admin->planner_id = 0;
            $admin->adder_id = 0;
            $admin->staff_hotel_id = 0;
            $admin->order_name = $name;
            $admin->order_type = 2;
            $admin->cut_price = $cut_price;
            $admin->update_time = date('Y-m-d h:i:s',time());
            $admin->save();
            $model_order_id = $admin->attributes['id'];

            //新增套系模版
            $admin = new OrderModel;
            $admin->account_id = $account_id;
            $admin->is_menu = $is_menu;
            $admin->is_empty = 0;
            $admin->name = $name;
            $admin->model_order = $model_order_id;
            $admin->type_id = $model_type;
            $admin->poster_img = $poster_img;
            $admin->model_show = 1;
            $admin->update_time = date('Y-m-d h:i:s',time());
            $admin->save();
            $model_id = $admin->attributes['id'];
        }else{
            //修改 order
            Order::model()->updateByPk($model_order_id, array('cut_price' => $cut_price));

            //修改 model 信息
            OrderModel::model()->updateByPk($model_id, array('poster_img' => $poster_img, 'name' => $name, 'type_id' => $model_type));

            //删除所有 order_product
            OrderProduct::model()->deleteAll('order_id=:order_id', array(':order_id' => $model_order_id));

            //删除所有 order_show
            OrderShow::model()->deleteAll('order_id=:order_id', array(':order_id' => $model_order_id));
        };
            

        //为订单 增加产品
        $product = explode(',', $product_list);
        foreach ($product as $key => $value) {
            $t = explode('|', $value);
            $supplier_product_id = $t[0];
            if($t[4] == 'service_product'){
                $p = ServiceProduct::model()->findByPk($t[0]);
                //查找当前公司，是否有对应的 supplier_product
                $sp = SupplierProduct::model()->find(array(
                        'condition' => 'account_id=:account_id && service_product_id=:service_product_id',
                        'params' => array(
                                ':account_id' => $account_id,
                                ':service_product_id' => $t[0]
                            )
                    ));
                if(empty($sp)){ //没有 supplier_product
                    //查找是否有对应的supplier
                    $service_person = ServicePerson::model()->findByPk($p['service_person_id']);
                    $supplier = Supplier::model()->find(array(
                            'condition' => 'staff_id=:staff_id && account_id=:account_id && type_id=:type_id',
                            'params' => array(
                                    ':staff_id' => $service_person['staff_id'],
                                    ':account_id' => $account_id,
                                    ':type_id' => $service_person['service_type']
                                )
                        ));
                    $supplier_id = 0;
                    if(empty($supplier)){
                        $admin=new Supplier;
                        $admin->account_id=$account_id;
                        $admin->type_id=$service_person['service_type'];
                        $admin->staff_id=$service_person['staff_id'];
                        $admin->update_time=date('y-m-d h:i:s',time());
                        $admin->save();
                        $supplier_id = $admin->attributes['id'];
                    }else{
                        $supplier_id = $supplier['id'];
                    };

                    //新增supplier_product
                    $admin=new SupplierProduct;
                    $admin->account_id=$account_id;
                    $admin->supplier_id=$supplier_id;
                    $admin->service_product_id=$t[0];
                    $admin->supplier_type_id=$service_person['service_type'];
                    $admin->dish_type=0;
                    $admin->decoration_tap=$p['decoration_tap'];
                    $admin->standard_type=0;
                    $admin->name=$p['product_name'];
                    $admin->category=2;
                    $admin->unit_price=$p['price'];
                    $admin->unit_cost=$p['cost'];
                    $admin->unit=$p['unit'];
                    $admin->service_charge_ratio=0;
                    $admin->ref_pic_url=$p['ref_pic_url'];
                    $admin->description=$p['description'];
                    $admin->product_show=$p['product_show'];
                    $admin->update_time=date('y-m-d h:i:s',time());
                    $admin->save();
                    $supplier_product_id = $admin->attributes['id'];
                }else{
                    $supplier_product_id = $sp['id'];
                };
            }else{

            };

            //新增 order_product
            $admin = new OrderProduct;
            $admin->account_id = 2;
            $admin->order_id = $model_order_id;
            $admin->product_type = 0;
            $admin->product_id = $supplier_product_id;
            $admin->order_set_id = 0;
            $admin->actual_price = $t[1];
            $admin->unit = $t[2];
            $admin->actual_unit_cost = $t[3];
            $admin->update_time = date('Y-m-d h:i:s',time());
            $admin->save();
            $order_product_id = $admin->attributes['id'];


            //新增 order_show
            $service_product = yii::app()->db->createCommand('select * from service_product where id in '.
                ' (select service_product_id from supplier_product where id='.$supplier_product_id.')');
            $service_product = $service_product->queryAll();

            $order_show = OrderShow::model()->find(array(
                    'condition' => 'order_id=:order_id && subarea=:subarea',
                    'params' => array(
                            ':order_id' => $model_order_id,
                            ':subarea' => $service_product[0]['subarea']
                        ),
                    'order' => 'area_sort DESC'
                ));

            $admin = new OrderShow;
            $admin->type = 2;
            $admin->img_id = 0;
            $admin->order_product_id = $order_product_id;
            $admin->words = 0;
            $admin->order_id = $model_order_id;
            $admin->subarea = $service_product[0]['subarea'];
            $admin->area_sort = $order_show['area_sort'] + 1;
            $admin->update_time = date('Y-m-d h:i:s',time());
            $admin->save();
        };

/**********************************************************/
/*****************    wedding_set 代码    *****************/
/**********************************************************/

        // CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Name'=>$_POST['CI_Name'],'CI_Show'=>$_POST['CI_Show'],'CI_Pic'=>$_POST['CI_Pic']));
        // Wedding_set::model()->updateByPk($_POST['CT_ID'],array('staff_hotel_id'=>$_POST['staff_hotel_id'],'name'=>$_POST['CI_Name'],'final_price'=>$_POST['final_price'],'feast_discount'=>$_POST['feast_discount'],'product_list'=>$_POST['product_list']));
        // if($_POST['case_resource'] != ""){
        //     $t = explode(",",$_POST['case_resource']);
        //     $resources = array();
        //     foreach ($t as $key => $value) {
        //         $t1 = explode(".", $value);
        //         $item = array();
        //         if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
        //             $item['Cr_Type'] = 1 ;
        //         }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
        //             $item['Cr_Type'] = 2 ;
        //         }
        //         $item['Cr_Path'] = $value;
        //         $resources[]=$item;
        //     };
        //     /*print_r($resources);die;*/
        //     $i = $_POST['CR_Sort']+1;
        //     foreach ($resources as $key => $value) {
        //         $data = new CaseResources;
        //         $data ->CI_ID = $_POST['CI_ID'];
        //         $data ->CR_Show = 1;
        //         $data ->CR_Type = $value['Cr_Type'];
        //         $data ->CR_Name = "";
        //         $data ->CR_Path = $value['Cr_Path'];
        //         $data ->CR_Remarks = "";
        //         $data ->CR_Sort = $i++;
        //         $data->save();
        //     };
        // };
    }

    public function actionTheme_edit()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Name'=>$_POST['CI_Name'],'CI_Show'=>$_POST['CI_Show'],'CI_Pic'=>$_POST['CI_Pic']));
        Wedding_set_theme::model()->updateByPk($_POST['CT_ID'],array('name'=>$_POST['CI_Name'],'final_price'=>$_POST['final_price'],'feast_discount'=>$_POST['feast_discount'],'product_list'=>$_POST['product_list']));
        if($_POST['case_resource'] != ""){
            $t = explode(",",$_POST['case_resource']);
            $resources = array();
            foreach ($t as $key => $value) {
                $t1 = explode(".", $value);
                $item = array();
                if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                    $item['Cr_Type'] = 1 ;
                }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                    $item['Cr_Type'] = 2 ;
                }
                $item['Cr_Path'] = $value;
                $resources[]=$item;
            };
            /*print_r($resources);die;*/
            $i = $_POST['CR_Sort']+1;
            foreach ($resources as $key => $value) {
                $data = new CaseResources;
                $data ->CI_ID = $_POST['CI_ID'];
                $data ->CR_Show = 1;
                $data ->CR_Type = $value['Cr_Type'];
                $data ->CR_Name = "";
                $data ->CR_Path = $value['Cr_Path'];
                $data ->CR_Remarks = "";
                $data ->CR_Sort = $i++;
                $data->save();
            };
        };
    }

    public function actionEdit_host_video()
    {
        $url = "http://file.cike360.com";

        //取资源信息
        $data = CaseResources::model()->findAll(array(
                'condition' => 'CI_ID=:CI_ID && CR_Type=:CR_Type',
                'params' => array(
                        ':CI_ID' => $_GET['ci_id'],
                        ':CR_Type' => 2,
                    ),
                'order' => 'CR_Sort',
            ));
        $resources = array();
        foreach ($data as $key => $value) {
            $t = explode('.', $value['CR_Path']);
            $result = yii::app()->db->createCommand("select case_resources_product.id as bind_id,name,unit,unit_price from case_resources_product left join supplier_product on supplier_product_id=supplier_product.id where case_resources_product.CR_ID=".$value['CR_ID']);
            $result = $result->queryAll();
            $item = array();
            $item['product'] = $result;
            if(isset($t[0]) && isset($t[1])){
                $item['CR_Path'] = $url.$t[0].'_sm.'.$t[1];
            }else{
                $item['CR_Path'] = "images/cover.jpg";
            };
            
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];
        }else{
            $Pic = "images/cover.jpg";
        };
        
        //$Pic = $url.$t[0].'_sm.'.$t[1];

        //取场布产品信息
        /*$product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0,
                        ':supplier_type_id' => 20, 

                    )
            ));
        $tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id']
                    )
            ));*/
        /*print_r($product);die;*/
        $this->render("edit_host_video",array(
                'pic' => $Pic,
                'resources' => $resources,
                'case' => $case,
                /*'case_data' => $product,
                'tap' => $tap,*/
            ));
    }

    public function actionEdit_host_img()
    {
        $url = "http://file.cike360.com";

        //取资源信息
        $data = CaseResources::model()->findAll(array(
                'condition' => 'CI_ID=:CI_ID && CR_Type=:CR_Type',
                'params' => array(
                        ':CI_ID' => $_GET['ci_id'],
                        ':CR_Type' => 1,
                    ),
                'order' => 'CR_Sort',
            ));
        $resources = array();
        foreach ($data as $key => $value) {
            $t = explode('.', $value['CR_Path']);
            $result = yii::app()->db->createCommand("select case_resources_product.id as bind_id,name,unit,unit_price from case_resources_product left join supplier_product on supplier_product_id=supplier_product.id where case_resources_product.CR_ID=".$value['CR_ID']);
            $result = $result->queryAll();
            $item = array();
            $item['product'] = $result;
            if(isset($t[0]) && isset($t[1])){
                $item['CR_Path'] = $url.$t[0].'_sm.'.$t[1];
            }else{
                $item['CR_Path'] = "images/cover.jpg";
            };
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];
        }else{
            $Pic = "images/cover.jpg";
        };

        //取场布产品信息
        /*$product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0,
                        ':supplier_type_id' => 20, 

                    )
            ));
        $tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id']
                    )
            ));*/
        /*print_r($product);die;*/
        $this->render("edit_host_img",array(
                'pic' => $Pic,
                'resources' => $resources,
                'case' => $case,
                /*'case_data' => $product,
                'tap' => $tap,*/
            ));
    }

    public function actionEdit_host_self_info()
    {
        $url = "http://file.cike360.com";

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);$Pic="";
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];    
        }else{
            $Pic = "images/cover.jpg";
        };
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];
        };

        $staff = Staff::model()->findByPk($case['CT_ID']);
        /*print_r($product);die;*/
        $this->render("edit_host_self_info",array(
                'pic' => $Pic,
                'case' => $case,
                'staff' => $staff,
            ));
    }

    public function actionHost_video_edit()
    {
        if($_POST['case_resource'] != ""){
            $t = explode(",",$_POST['case_resource']);
            $resources = array();
            foreach ($t as $key => $value) {
                $t1 = explode(".", $value);
                $item = array();
                if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                    $item['Cr_Type'] = 1 ;
                }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                    $item['Cr_Type'] = 2 ;
                }
                $item['Cr_Path'] = $value;
                $resources[]=$item;
            };
            /*print_r($resources);die;*/
            $i = $_POST['CR_Sort']+1;
            foreach ($resources as $key => $value) {
                $data = new CaseResources;
                $data ->CI_ID = $_POST['CI_ID'];
                $data ->CR_Show = 1;
                $data ->CR_Type = $value['Cr_Type'];
                $data ->CR_Name = "";
                $data ->CR_Path = $value['Cr_Path'];
                $data ->CR_Remarks = "";
                $data ->CR_Sort = $i++;
                $data->save();
            };
        };
    }

    public function actionHost_self_info_edit()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Name'=>$_POST['CI_Name'],'CI_Pic'=>$_POST['CI_Pic']));
        $case = CaseInfo::model()->findByPk($_POST['CI_ID']);
        Staff::model()->updateByPk($case['CT_ID'],array('name' => $_POST['CI_Name'],'telephone'=>$_POST['phone']));
    }

    public function actionHost_product_edit()
    {
        //新建service_product，并返回service_product_id
        $data = new ServiceProduct;
        $data ->service_person_id = $_POST['service_person_id'];
        $data ->service_type = $_POST['service_type'];
        $data ->product_name = $_POST['product_name'];
        $data ->price = $_POST['price'];
        $data ->unit = $_POST['unit'];
        $data ->update_time = date('y-m-d h:i:s',time());
        $data ->description = $_POST['description'];
        $data ->product_show = 1;
        if(isset($_POST['cost']) && isset($_POST['ref_pic_url'])){
            $data ->cost = $_POST['cost'];
            $data ->ref_pic_url = $_POST['ref_pic_url'];
        };
        $data ->save();

        $service_product_id = $data->attributes['id'];

        //给所有公司新增一个supplier_product
        $case = CaseInfo::model()->findByPk($_POST['CI_ID']);
        $company = StaffCompany::model()->findAll();
        foreach ($company as $key => $value) {
            
            $supplier_id = yii::app()->db->createCommand("select supplier.id as supplier_id from supplier left join service_person on supplier.staff_id=service_person.staff_id where supplier.account_id=".$value['id']." and service_person.id=".$_POST['service_person_id']);
            $supplier_id = $supplier_id->queryAll();

            $data = new SupplierProduct;
            $data ->account_id = $value['id'];
            $data ->supplier_id = $supplier_id[0]['supplier_id'];
            $data ->service_product_id = $service_product_id;
            $data ->supplier_type_id = $_POST['service_type'];
            $data ->decoration_tap = 0;
            $data ->standard_type = 0;
            $data ->name = $_POST['product_name'];
            $data ->category = 2;
            $data ->unit_price = $_POST['price']*2;
            if(!isset($_POST['cost']) && !isset($_POST['ref_pic_url'])){
                $data ->unit_cost = $_POST['price'];    
                $data ->ref_pic_url = $case['CI_Pic'];
            }else{
                $data ->unit_cost = $_POST['cost'];    
                $data ->ref_pic_url = $_POST['ref_pic_url'];
            };
            $data ->unit = $_POST['unit'];
            $data ->service_charge_ratio = 0;
            $data ->description = $_POST['description'];
            $data ->update_time = date('y-m-d h:i:s',time());
            $data ->save();
        };
    }

    public function actionEdit_supplier_product()
    {
        $supplier = array();
        if(!isset($_GET['type'])){
            $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=20");
            $supplier = $result->queryAll();
        }else if($_GET['type'] == "lss"){
            $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id in (8,9,23)");
            $supplier = $result->queryAll();
        }else if($_GET['type'] == "dish"){
            $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=2");
            $supplier = $result->queryAll();
        }
        $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $product = SupplierProduct::model()->findByPk($_GET['product_id']);
        $t = explode(".", $product['ref_pic_url']);
        $picture="";
        if(isset($t[0]) && isset($t[1])){
            $picture = "http://file.cike360.com".$t[0]."_sm.".$t[1];
        }else{
            $picture = "images/cover.jpg";
        };  
        /*print_r($supplier);die;*/
        $this->render("edit_supplier_product",array(
                'picture' => $picture,
                'product' => $product,
                'supplier' => $supplier,
                'decoration_tap' => $decoration_tap,
                'supplier_type' => $supplier_type,
            ));
    }

    public function actionDel_model(){
        //参数表
        $model_id = $_POST['model_id'];

        OrderModel::model()->updateByPk($model_id, array('model_show' => 0));
    }

    public function actionDel_case()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Show'=>0));
        if($_POST['CI_Type'] == 5 || $_POST['CI_Type'] == 9 || $_POST['CI_Type'] == 11 || $_POST['CI_Type'] == 12){
            $case = CaseInfo::model()->findByPk($_POST['CI_ID']);
            Wedding_set::model()->updateByPk($case['CT_ID'],array('set_show'=>0));
        };
        if($_POST['CI_Type'] == 4){
            $case = CaseInfo::model()->findByPk($_POST['CI_ID']);
            Wedding_set_theme::model()->updateByPk($case['CT_ID'],array('set_show'=>0));
            Wedding_set::model()->updateAll(array('set_show'=>0),'theme_id=:theme_id',array(':theme_id'=>$case['CT_ID']));
        };
    }

    public function actionDel_case_resource()
    {
        //参数表
        $ci_id = $_POST['ci_id'];
        $img_list = $_POST['img_list'];


        //预备信息
        $img = explode(',', $img_list);

        //删除图片
        foreach ($img as $key => $value) {
            CaseResources::model()->updateByPk($value, array('CR_Show' => 0));
        };
    }

    public function actionDel_product()
    {
        SupplierProduct::model()->updateByPk($_POST['product_id'],array('product_show'=>0));
    }

    public function actionUpload_dish()
    {
        $dish_type = DishType::model()->findAll();
        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=2");
        $supplier = $result->queryAll();
        $this->render("upload_dish",array(
                'dish_type' => $dish_type,
                'supplier' => $supplier,
            ));
    }

    public function actionUpload_menu1()
    {
        $account_id = $_COOKIE['account_id'];

        $dish_type = DishType::model()->findAll();
        $supplier_product = SupplierProduct::model()->findAll(array(
            'condition' => 'account_id=:account_id && standard_type=:standard_type && product_show=:product_show',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0,
                        ':product_show' => 1,
                    )));
        $dish=array();
        foreach ($supplier_product as $key => $value) {
            $t=explode('.', $value['ref_pic_url']);
            if(isset($t[0]) && isset($t[1])){
                $supplier_product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];    
            };
            if($value['supplier_type_id']==2){
                $dish[]=$value;
            }
        };
        //print_r($dish);die;
        $this -> render("upload_menu1",array(
            'dish_type' => $dish_type,
            'supplier_product' => $supplier_product,
            ));
    }

    public function actionUpload_menu2()
    {
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $this->render("upload_menu2",array(
                'hotel' => $hotel,
            ));
    } 

    public function actionEdit_host_product()
    {
        if(isset($_POST['cost']) && isset($_POST['ref_pic_url'])){
            ServiceProduct::model()->updateByPk($_POST['id'],array(
                    'product_name' => $_POST['product_name'],
                    'price' => $_POST['price'],
                    'cost' => $_POST['cost'],
                    'ref_pic_url' => $_POST['ref_pic_url'],
                    'unit' => $_POST['unit'],
                    'description' => $_POST['description'],
                ));
        }else{
            ServiceProduct::model()->updateByPk($_POST['id'],array(
                    'product_name' => $_POST['product_name'],
                    'price' => $_POST['price'],
                    'unit' => $_POST['unit'],
                    'description' => $_POST['description'],
                ));
        };

        $company = StaffCompany::model()->findAll();
        foreach ($company as $key => $value) {
            if(isset($_POST['cost']) && isset($_POST['ref_pic_url'])){
                SupplierProduct::model()->updateAll(array(
                        'name' => $_POST['product_name'],
                        'unit_price' => $_POST['price'],
                        'unit_cost' => $_POST['cost'],
                        'ref_pic_url' => $_POST['ref_pic_url'],
                        'unit' => $_POST['unit'],
                        'description' => $_POST['description'],
                    ),'account_id=:account_id && service_product_id=:service_product_id',array(':account_id' => $value['id'],':service_product_id' => $_POST['id']));
            }else{
                SupplierProduct::model()->updateAll(array(
                        'name' => $_POST['product_name'],
                        'unit_price' => $_POST['price'],
                        'unit' => $_POST['unit'],
                        'description' => $_POST['description'],
                    ),'account_id=:account_id && service_product_id=:service_product_id',array(':account_id' => $value['id'],':service_product_id' => $_POST['id']));
            };
                
        };
    }

    public function actionDel_service_person()
    {
        //参数表
        $service_person_id = $_POST['service_person_id'];

        //隐藏人员
        ServicePerson::model()->updateByPk($service_person_id, array('show' => 0));

        //隐藏产品
        ServiceProduct::model()->updateAll(array('product_show' => 0), 'service_person_id=:spi', array(':spi' => $service_person_id));
    }

    public function actionDel_service_product()
    {
        ServiceProduct::model()->updateByPk($_POST['id'],array(
                'product_show' => 0,
            ));

        $company = StaffCompany::model()->findAll();
        $t = 0;
        foreach ($company as $key => $value) {
            $t = SupplierProduct::model()->updateAll(array(
                    'product_show' => 0,
                ),'account_id=:account_id && service_product_id=:service_product_id',array(':account_id' => $value['id'],':service_product_id' => $_POST['id']));
        };
        echo json_encode(array('result' => $t));
    }

    public function actionUpload_service_person()
    {
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'role=:role',
                'params' => array(
                        ':role' => 2
                    )
            ));
        if(!isset($_GET['service_person_id'])){ //新增
            $this->render('upload_service_person', array(
                    'supplier_type' => $supplier_type
                ));
        }else{  //编辑
            $service_person = ServicePerson::model()->findByPk($_GET['service_person_id']);
            $service_person_img = ServicePersonImg::model()->findAll(array(
                    'condition' => 'service_person_id=:service_person_id',
                    'params' => array(
                            ':service_person_id' => $_GET['service_person_id']
                        )
                ));
            $img = array();
            foreach ($service_person_img as $key => $value) {
                $item = array();
                $item['id'] = $value['id'];
                $item['img_url'] = $value['img_url'];
                $item['url'] = "http://file.cike360.com" . $this->add_string_to_url($value['img_url'], 'sm');
                $img[] = $item;
            };
            $service_person_video = ServicePersonVideo::model()->find(array(
                    'condition' => 'service_person_id=:service_person_id',
                    'params' => array(
                            ':service_person_id' => $_GET['service_person_id']
                        )
                ));

            $url = "";
            if(!empty($service_person_img)){
                $url = 'http://file.cike360.com' . $this->add_string_to_url($service_person_img[0]['img_url'], 'sm');
            };
            $this->render('upload_service_person',array(
                    'service_person' => $service_person,
                    'img' => $img,
                    'video_url' => $service_person_video['video_url'],
                    'supplier_type' => $supplier_type,
                    'poster_img_url' => $url
                ));
/***************************  case_info  版本  *******************************/
            // $supplier = Supplier::model()->findByPk($_GET['supplier_id']);
            // $staff = Staff::model()->findByPk($supplier['staff_id']);
            // $case = CaseInfo::model()->findByPk($_GET['ci_id']);
            // $resources = CaseResources::model()->findAll(array(
            //         'condition' => 'CI_ID=:CI_ID',
            //         'params' => array(
            //                 ':CI_ID' => $_GET['ci_id']
            //             )
            //     ));
            // foreach ($resources as $key => $value) {
            //     $t = explode('.', $value['CR_Path']);
            //     if(isset($t[0]) && isset($t[1])){
            //         $resources[$key]['CR_Path'] = 'http://file.cike360.com'.$t[0].'_sm.'.$t[1];
            //     };
            // };

            // $this->render('upload_service_person',array(
            //         'staff' => $staff,
            //         'case' => $case,
            //         'resources' => $resources
            //     ));
        };
    }

    public function actionUpload_sp()
    {
        /***************************************/
        /*************** Staff表 ***************/
        /***************************************/
        // $_POST = array(
        //         'name' => "小柯", 
        //         'telephone' => "13810249821", 
        //         'img' => "/upload/xiaoke20160713130544.jpg", 
        //         'case_resource' => "/upload/20160620113442_9348620160713130551.jpg,/up…160713130552.jpg,/upload/xiaoke20160713130651.mp4", 
        //         'supplier_type' => "3",
        //         'account_id' => "0"
        //     );
        $user = Staff::model()->findByPk($_COOKIE['userid']);

        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:tele',
                'params' => array(
                        ':tele' => $_POST['telephone'],
                    )
            ));
        $staff_id = 0;
        if(empty($staff)){
            $data = new Staff;
            $data->account_id=$_COOKIE['account_id'];
            $data->name=$_POST['name'];
            $data->city_id=$user['city_id'];
            $data->telephone=$_POST['telephone'];
            $data->department_list='[4]';
            $data->update_time=date('y-m-d h:i:s',time());
            $data->save();
            $staff_id = $data->attributes['id'];
        }else{
            $staff_id = $staff['id'];
        };


        /***************************************/
        /*********** Service_Person表 **********/
        /***************************************/
        $service_person_id = 0;

        $service_person = ServicePerson::model()->find(array(
            'condition' => 'staff_id=:staff_id && service_type=:service_type',
            'params' => array(
                    ':staff_id' => $staff_id,
                    ':service_type' => $_POST['supplier_type']
                )
        ));
        if(empty($staff) || empty($service_person)){
            $data1 = new ServicePerson;
            $data1->team_id = 2;
            $data1->name = $_POST['name'];
            $data1->status = 0;
            $data1->telephone = $_POST['telephone'];
            $data1->update_time = date('y-m-d h:i:s',time());
            $data1->staff_id = $staff_id;
            $data1->service_type = $_POST['supplier_type'];
            $data1->team_id = 2;
            $data1->save();
            $service_person_id = $data1->attributes['id'];
        }else{
            $service_person_id = $service_person['id'];
        };

        /*******************************************/
        /*********** Service_Person_Img表 **********/
        /*******************************************/
        $img = ServicePersonImg::model()->find(array(
                'condition' => 'service_person_id=:spi',
                'params' => array(
                        ':spi' => $service_person_id
                    ),
                'order' => 'sort DESC'
            ));
        $sort = 1;
        if (!empty($img)) {
            $sort = $img['sort'];
        };
        $t = explode(",",$_POST['case_resource']);

        foreach ($t as $key => $value) {
            $admin = new ServicePersonImg;
            $admin->service_person_id = $service_person_id;
            $admin->img_url = $value;
            $admin->sort = $sort++;
            $admin->img_show = 1;
            $admin->update_time = date('y-m-d h:i:s',time());
            $admin->save();
        };

        /*********************************************/
        /*********** Service_Person_Video表 **********/
        /*********************************************/
        $video = ServicePersonVideo::model()->find(array(
                'condition' => 'service_person_id=:spi',
                'params' => array(
                        ':spi' => $service_person_id
                    ),
                'order' => 'sort DESC'
            ));
        $sort = 1;
        if(!empty($video)){
            $sort = $video['sort'];
        };

        $admin = new ServicePersonVideo;
        $admin->service_person_id = $service_person_id;
        $admin->video_url = $_POST['video_url'];
        $admin->sort = $sort++;
        $admin->video_show = 1;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();


/*****************************************************************************/
/************************  case_info 版本  ***********************************/
/*****************************************************************************/

        // /***************************************/
        // /***************  Case_Info  ***********/
        // /***************************************/
        // $CI_Type = 0;
        // if($_POST['supplier_type'] == 3){ $CI_Type = 6; };
        // if($_POST['supplier_type'] == 4){ $CI_Type = 13; };
        // if($_POST['supplier_type'] == 5){ $CI_Type = 14; };
        // if($_POST['supplier_type'] == 6){ $CI_Type = 15; };
        // if($_POST['supplier_type'] == 7){ $CI_Type = 21; };

        // $case_info = CaseInfo::model()->find(array(
        //         'condition' => 'CI_Type=:CI_Type && CT_ID=:CT_ID',
        //         'params' => array(
        //                 ':CI_Type' => $CI_Type,
        //                 ':CT_ID' => $staff_id
        //             )
        //     ));

        // $CI_ID = 0;
        // // print_r($case_info);die;
        // if(empty($case_info)){
        //     $data2 = new CaseInfo;
        //     $data2->CI_Name = $_POST['name'];
        //     $data2->CI_Pic = $_POST['img'];
        //     $data2->CI_CreateTime = date('y-m-d h:i:s',time());
        //     $data2->CI_Sort = 1;
        //     $data2->CI_Show = 1;
        //     $data2->CI_Type = $CI_Type;
        //     $data2->CT_ID = $staff_id;
        //     $data2->save();
        //     $CI_ID = $data2->attributes['CI_ID'];
        // }else{
        //     $CI_ID = $case_info['CI_ID'];
        // };
        // // echo $CI_ID;die;
        // *************************************
        // /*************  Case_Resource  *********/
        // /***************************************/

        // $t = explode(",",$_POST['case_resource']);
        // $resources = array();
        // foreach ($t as $key => $value) {
        //     $t1 = explode(".", $value);
        // // echo json_encode($t1);die;

        //     $item = array();
        //     if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
        //         $item['Cr_Type'] = 1 ;
        //     }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
        //         $item['Cr_Type'] = 2 ;
        //     }
        //     $item['Cr_Path'] = $value;
        //     $resources[]=$item;
        // };
        // // print_r($resources);die;
        // // echo $CI_ID;die;


        // $i = 1;
        // foreach ($resources as $key => $value) {
        //     // echo json_encode($value).",".$CI_ID."||";
        //     $data3 = new CaseResources;
        //     $data3->CI_ID = $CI_ID;
        //     $data3->CR_Show = 1;
        //     $data3->CR_Type = $value['Cr_Type'];
        //     $data3->CR_Name = "";
        //     $data3->CR_Path = $value['Cr_Path'];
        //     $data3->CR_Remarks = "";
        //     $data3->CR_Sort = $i++;
        //     $data3->save();
        // };


        /***************************************/
        /**************  Supplier  *************/
        /***************************************/
        $supplier = Supplier::model()->find(array(
                'condition' => 'staff_id=:staff_id && type_id=:type_id && account_id=:account_id',
                'params' => array(
                        ':staff_id' => $staff_id,
                        ':type_id' => $_POST['supplier_type'],
                        ':account_id' => $_POST['account_id']
                    )
            ));
        // print_r($supplier);die;
        if(empty($staff) || empty($supplier)){
            $data4 = new Supplier;
            $data4->account_id = $_POST['account_id'];
            $data4->type_id = $_POST['supplier_type'];
            $data4->staff_id = $staff_id;
            $data4->update_time = date('y-m-d h:i:s',time());
            $data4->save();
            echo 'success';
        }else{
            echo 'exist';
        }
    }

    public function actionInsert_sp()
    {
        //参数表
        $account_id = $_POST['account_id'];
        $name = $_POST['name'];
        $telephone = $_POST['telephone'];
        $supplier_type = $_POST['supplier_type'];
        // $account_id = '1';
        // $name = 'ceshi';
        // $telephone = 123;
        // $supplier_type = 3;


        /***************************************/
        /*************** Staff表 ***************/
        /***************************************/
        $company = StaffCompany::model()->findByPk($account_id);

        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:tele',
                'params' => array(
                        ':tele' => $telephone,
                    )
            ));
        $staff_id = 0;
        if(empty($staff)){
            $data = new Staff;
            $data->account_id=$account_id;
            $data->name=$name;
            $data->city_id=$company['city_id'];
            $data->telephone=$telephone;
            $data->department_list='[4]';
            $data->update_time=date('y-m-d h:i:s',time());
            $data->save();
            $staff_id = $data->attributes['id'];
        }else{
            $staff_id = $staff['id'];
        };


        /***************************************/
        /*********** Service_Person表 **********/
        /***************************************/
        $service_person_id = 0;

        $service_person = ServicePerson::model()->find(array(
            'condition' => 'staff_id=:staff_id && service_type=:service_type',
            'params' => array(
                    ':staff_id' => $staff_id,
                    ':service_type' => $supplier_type
                )
        ));
        if(empty($staff) || empty($service_person)){
            $data1 = new ServicePerson;
            $data1->team_id = 2;
            $data1->name = $name;
            $data1->status = 0;
            $data1->telephone = $telephone;
            $data1->update_time = date('y-m-d h:i:s',time());
            $data1->staff_id = $staff_id;
            $data1->service_type = $supplier_type;
            $data1->team_id = 2;
            $data1->save();
            $service_person_id = $data1->attributes['id'];
        }else{
            ServicePerson::model()->updateByPk($service_person['id'], array('show' => 1));
            $service_person_id = $service_person['id'];
        };


        /***************************************/
        /**************  Supplier  *************/
        /***************************************/
        $supplier = Supplier::model()->find(array(
                'condition' => 'staff_id=:staff_id && type_id=:type_id && account_id=:account_id',
                'params' => array(
                        ':staff_id' => $staff_id,
                        ':type_id' => $supplier_type,
                        ':account_id' => $account_id
                    )
            ));
        // print_r($supplier);die;
        if(empty($staff) || empty($supplier)){
            $data4 = new Supplier;
            $data4->account_id = $account_id;
            $data4->type_id = $supplier_type;
            $data4->staff_id = $staff_id;
            $data4->update_time = date('y-m-d h:i:s',time());
            $data4->save();
        };
    }

    public function actionDel_supplier()
    {
        Supplier::model()->deleteByPk($_POST['supplier_id']);
        SupplierProduct::model()->updateAll(array('product_show'=>0),'account_id=:account_id && supplier_id=:supplier_id',array(':account_id'=>$_COOKIE['account_id'],':supplier_id'=>$_POST['supplier_id']));
    }

    public function actionDesigner_add_service_p()
    {
        $service_person = ServicePerson::model()->findByPk($_POST['service_person_id']);

        $supplier = Supplier::model()->find(array(
                'condition' => 'staff_id=:staff_id && type_id=:type_id',
                'params' => array(
                        ':staff_id' => $service_person['staff_id'],
                        ':type_id' => $_POST['service_type']
                    )
            ));

        $service_person_img = ServicePersonImg::model()->find(array(
                'condition' => 'service_person_id=:spi',
                'params' => array(
                        ':spi' => $_POST['service_person_id']
                    ),
                'order' => 'sort'
            ));

        $supplier_id = 0;
        if(empty($supplier)){
            $admin = new Supplier;
            $admin->account_id= $_POST['account_id'];
            $admin->type_id= $service_person['service_type'];
            $admin->staff_id= $service_person['staff_id'];
            $admin->update_time= date('y-m-d h:i:s',time());
            $admin->save();
            $supplier_id = $admin->attributes['id'];
        }else{
            $supplier_id = $supplier['id'];
        };

        $subarea = OrderShowAreaSubarea::model()->find(array(
                'condition' => 'supplier_type=:supplier_type',
                'params' => array(
                        ':supplier_type' => $_POST['service_type']
                    )
            ));

        /**********************************************/
        /**************  service_product  *************/
        /**********************************************/
        $data = new ServiceProduct;
        $data->service_person_id = $_POST['service_person_id'];
        $data->service_type = $service_person['service_type'];
        $data->product_name = $_POST['product_name'];
        $data->price = 0;
        $data->cost = $_POST['cost'];
        $data->unit = $_POST['unit'];
        $data->update_time = date('y-m-d h:i:s',time());
        $data->description = $_POST['description'];
        $data->product_show = 1;
        $data->subarea = $subarea['id'];
        $data->save();
        $service_product_id=$data->attributes['id'];

        /**************************************************/
        /**************  service_product_img  *************/
        /**************************************************/
        $admin = new ServiceProductImg;
        $admin->service_product_id = $service_product_id;
        $admin->img_url = $service_person_img['img_url'];
        $admin->sort = 1;
        $admin->img_show = 1;
        $admin->update_time = date('Y-m-d h:i:s',time());
        $admin->save();

        /**********************************************/
        /**************  supplier_product  *************/
        /**********************************************/

        $data = new SupplierProduct;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->supplier_id = $supplier_id;
        $data ->service_product_id = $service_product_id;
        $data ->supplier_type_id = $service_person['service_type'];
        $data ->decoration_tap = 0;
        $data ->standard_type = 0;
        $data ->name = $_POST['product_name'];
        $data ->category = 2;
        $data ->unit_price = $_POST['price'];
        if(!isset($_POST['cost']) && !isset($_POST['ref_pic_url'])){
            $data ->unit_cost = $_POST['cost'];    
            $data ->ref_pic_url = $service_person_img['img_url'];
        }else{
            $data ->unit_cost = $_POST['cost'];    
            $data ->ref_pic_url = $service_person_img['img_url'];
        };
        $data ->unit = $_POST['unit'];
        $data ->service_charge_ratio = 0;
        $data ->description = $_POST['description'];
        $data ->update_time = date('y-m-d h:i:s',time());
        $data ->save();
    }

    public function actionDesigner_edit_service_p()
    {

        ServiceProduct::model()->updateByPk($_POST['id'],array(
                'product_name' => $_POST['product_name'],
                'price' => $_POST['cost'],
                'unit' => $_POST['unit'],
                'description' => $_POST['description'],
            ));

        SupplierProduct::model()->updateAll(array(
                'name' => $_POST['product_name'],
                'unit_price' => $_POST['price'],
                'unit_cost' => $_POST['cost'],
                // 'ref_pic_url' => $_POST['ref_pic_url'],
                'unit' => $_POST['unit'],
                'description' => $_POST['description'],
            ),'account_id=:account_id && service_product_id=:service_product_id',array(':account_id' => $_COOKIE['account_id'],':service_product_id' => $_POST['service_product_id']));

    }

    public function actionDesigner_del_service_product()
    {
        ServiceProduct::model()->updateByPk($_POST['id'],array(
                'product_show' => 0,
            ));


        SupplierProduct::model()->updateAll(array(
                'product_show' => 0,
            ),'account_id=:account_id && service_product_id=:service_product_id',array(':account_id' => $_COOKIE['account_id'],':service_product_id' => $_POST['id']));
    }

    public function actionEdit_sp(){
        //修改service_person、staff
        ServicePerson::model()->updateByPk($_POST['service_person_id'], array('name' => $_POST['name'], 'telephone' => $_POST['telephone'], 'service_type' => $_POST['supplier_type']));
        $service_person = ServicePerson::model()->findByPk($_POST['service_person_id']);
        Staff::model()->updateByPk($service_person['staff_id'], array('name' => $_POST['name'], 'telephone' => $_POST['telephone']));

        //新增图片
        $img = ServicePersonImg::model()->find(array(
                'condition' => 'service_person_id=:service_person_id',
                'params' => array(
                        ':service_person_id' => $_POST['service_person_id']
                    ),
                'order' => 'sort DESC'
            ));
        $sort = 1;
        if(!empty($img)){
            $sort = $img['sort'];
        };
        if($_POST['case_resource'] != ""){
            $t = explode(",",$_POST['case_resource']);
            foreach ($t as $key => $value) {
                $admin = new ServicePersonImg;
                $admin->service_person_id = $_POST['service_person_id'];
                $admin->img_url = $value;
                $admin->sort = $sort++;
                $admin->img_show = 1;
                $admin->update_time = date('y-m-d h:i:s',time());
                $admin->save();
            };
        };

        //修改视频地址
        ServicePersonVideo::model()->updateAll(array('video_url' => $_POST['video_url']), 'service_person_id=:service_person_id', array(':service_person_id' => $_POST['service_person_id']));

/****************************************** case_info 版本 ********************************************/
        // $supplier=Supplier::model()->findByPk($_POST['supplier_id']);
        // Staff::model()->updateByPk($supplier['staff_id'],array('name'=>$_POST['name'],'telephone'=>$_POST['telephone']));
        // // Supplier::model()->updateByPk($_GET['supplier_id'],array('type'=>$_POST['supplier_type']));
        // // ServicePerson::model()->updateByPk($_GET['service_person_id'],array('service_type'=>$_POST['supplier_type']));
        // CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Pic' => $_POST['img']));

        // if($_POST['case_resource'] != ""){
        //     $t = explode(",",$_POST['case_resource']);
        //     $resources = array();
        //     foreach ($t as $key => $value) {
        //         $t1 = explode(".", $value);
        //         $item = array();
        //         if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
        //             $item['Cr_Type'] = 1 ;
        //         }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
        //             $item['Cr_Type'] = 2 ;
        //         }
        //         $item['Cr_Path'] = $value;
        //         $resources[]=$item;
        //     };
        //     /*print_r($resources);die;*/


        //     $i = 1;
        //     foreach ($resources as $key => $value) {
        //         $data3 = new CaseResources;
        //         $data3->CI_ID = $_POST['CI_ID'];
        //         $data3->CR_Show = 1;
        //         $data3->CR_Type = $value['Cr_Type'];
        //         $data3->CR_Name = "";
        //         $data3->CR_Path = $value['Cr_Path'];
        //         $data3->CR_Remarks = "";
        //         $data3->CR_Sort = $i++;
        //         $data3->save();
        //     };
        // };
    }

    public function actionEdit_order_info()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $order_id = $post->orderId;
        $order_place = $post->orderplace;
        $order_date = $post->orderdate;
        $order_type = $post->order_type;
        $hotelid = $post->hotelid;
        $groom_name = $post->groomname;
        $groom_phone = $post->groomtelephone;
        $bride_name = $post->bridename;
        $bride_phone = $post->bridetelephone;
        $company_name = $post->company_name;
        $company_id = $post->company_id;
        $contact_id = $post->contact_id;
        $contact_name = $post->contact_name;
        $contact_phone = $post->contact_phone;
        $remark = $post->remark;
        $guest_amount = 0;
        if(isset($post->guest_amount)){
            $guest_amount = $post->guest_amount;
        };
        // $order_id = $_POST['orderId'];
        // $order_place = $_POST['orderplace'];
        // $order_date = $_POST['orderdate'];
        // $hotelid = $_POST['hotelid'];
        // $groom_name = $_POST['groomname'];
        // $groom_phone = $_POST['groomtelephone'];
        // $bride_name = $_POST['bridename'];
        // $bride_phone = $_POST['bridetelephone'];
        // $contact_name = $_POST['linkmanname'];
        // $contact_phone = $_POST['linkmantelephone'];
        // $remark = $_POST['remark'];
        // $order_id = 1203;
        // $order_place = '花乡桥';
        // $order_date = '2016-12-31';
        // $hotelid = '1';
        // $groom_name = '韩方路';
        // $groom_phone = '13233456789';
        // $bride_name = '张斯琪';
        // $bride_phone = '';
        // $contact_name = '';
        // $contact_phone = '';
        // $remark = '';
        $result = array();
        if($order_type == 2){
            $order_name = $groom_name."&".$bride_name;

            //修改
            $t1 = Order::model()->updateByPk($order_id, array('order_name' => $order_name ,'order_place' => $order_place, 'order_date' => $order_date, 'staff_hotel_id' => $hotelid, 'guest_amount' => $guest_amount));
            $t2 = OrderWedding::model()->updateAll(array('groom_name' => $groom_name, 'groom_phone' => $groom_phone, 'bride_name' => $bride_name, 'bride_phone' => $bride_phone, 'contact_name' => $contact_name, 'contact_phone' => $contact_phone, 'remark' => $remark), 'order_id=:order_id', array(':order_id' => $order_id));
            
            $result = array(
                    'order' => $t1,
                    'order_wedding' => $t2
                );
        }else{
            $order_name = $company_name;

            //修改
            $t1 = Order::model()->updateByPk($order_id, array('order_name' => $order_name ,'order_place' => $order_place, 'order_date' => $order_date, 'staff_hotel_id' => $hotelid, 'guest_amount' => $guest_amount));
            $t11 = OrderMeeting::model()->updateAll(array('remark' => $remark), 'order_id=:order_id', array(':order_id' => $order_id));
            $t2 = OrderMeetingCompany::model()->updateByPk($company_id, array('company_name' => $company_name));
            $t3 = OrderMeetingCompanyLinkman::model()->updateByPk($contact_id, array('name' => $contact_name, 'telephone' => $contact_phone));

            $result = array(
                    'order' => $t1,
                    'order_meeting' => $t2
                );
        };
            
        echo json_encode($result);
    }

    public function actionSelect_word_theme()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $word_theme_id = $post->word_theme_id;
        $order_id = $post->order_id;
        // $word_theme_id = $_POST['word_theme_id'];
        // $order_id = $_POST['order_id'];
        // $word_theme_id = 4;
        // $order_id = 123;

        //预备信息
        $word_theme = OrderShowIdeaWords::model()->findByPk($word_theme_id);

        //删除所有已选的words
        OrderShow::model()->deleteAll('order_id=:order_id && type=:type', array(':order_id' => $order_id, ':type' => 0));

        //新增order_show
        $admin = new OrderShow;
        $admin->type = 0;
        $admin->words = $word_theme_id;
        $admin->theme_words = $word_theme['words'];
        $admin->theme_remark = $word_theme['remark'];
        $admin->order_id = $order_id;
        $admin->subarea = 1;
        $admin->area_sort = 1;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array('result' => $t));
    }

    public function actionDel_word_theme_order_show()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $show_id = $post->show_id;
        // $show_id = $_POST['show_id'];
        // $show_id = 6;

        //删除
        $t = OrderShow::model()->deleteByPk($show_id);

        echo json_encode(array('result' => $t));
    }

    public function actionInsert_word_theme()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $words = $post->words;
        $remark = $post->remark;
        $token = $post->token;
        // $words = $_POST['words'];
        // $remark = $_POST['remark'];
        // $token = $_POST['token'];
        // $words = 'ceshi文字';
        // $remark = 'ceshi备注';
        // $token = 100;

        //插入
        $admin = new OrderShowIdeaWords;
        $admin->words = $words;
        $admin->remark = $remark;
        $admin->subarea_id = 1;
        $admin->staff_id = $token;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array('id' => $t));
    }

    public function actionEdit_word_theme()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $words = $post->words;
        $remark = $post->remark;
        $word_id = $post->word_id;
        // $words = $_POST['words'];
        // $remark = $_POST['remark'];
        // $word_id = $_POST['word_id'];
        // $words = '123';
        // $remark = '123';
        // $word_id = '60';

        //修改
        $t = OrderShowIdeaWords::model()->updateByPk($word_id, array('words' => $words, 'remark' => $remark));

        echo json_encode(array('result' => $t));
    }

    public function actionDel_word_theme()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $word_id = $post->word_id;
        // $word_id = $_POST['word_id'];
        // $word_id = 6;

        //删除
        $t = OrderShowIdeaWords::model()->updateByPk($word_id, array('show' => 0));

        echo json_encode(array('result' => $t));
    }

    public function actionSelect_theme_color()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $theme_color_id = $post->theme_color_id;
        $order_id = $post->order_id;
        // $theme_color_id = $_POST['theme_color_id'];
        // $order_id = $_POST['order_id'];
        // $theme_color_id = 1;
        // $order_id = 722;

        //预备信息
        $theme_color = OrderShowIdeaColor::model()->findByPk($theme_color_id);

        //删除所有已选的words
        OrderShow::model()->deleteAll('order_id=:order_id && type=:type', array(':order_id' => $order_id, ':type' => 3));

        //新增order_show
        $admin = new OrderShow;
        $admin->type = 3;
        $admin->wed_color = $theme_color_id;
        $admin->order_id = $order_id;
        $admin->subarea = 3;
        $admin->area_sort = 1;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array('result' => $t));
    }

    public function actionDel_idea_color()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $color_id = $post->color_id;
        // $color_id = $_POST['color_id'];
        // $color_id = 3;

        //删除
        $t = OrderShowIdeaColor::model()->updateByPk($color_id, array('show' => 0));

        echo json_encode(array('result' => $t));
    }

    public function actionInsert_idea_color()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $name = $post->name;
        $remark = $post->remark;
        $main_color = $post->main_color;
        $second_color = $post->second_color;
        $third_color = $post->third_color;
        $token = $post->token;
        // $name = $_POST['name'];
        // $remark = $_POST['remark'];
        // $main_color = $_POST['main_color'];
        // $second_color = $_POST['second_color'];
        // $third_color = $_POST['third_color'];
        // $token = $_POST['token'];
        // $name = 'ceshi';
        // $remark = 'remark_ceshi';
        // $main_color = "#888888";
        // $second_color = "#999999";
        // $third_color = "#777777";
        // $token = '100';

        //插入
        $admin = new OrderShowIdeaColor;
        $admin->name = $name;
        $admin->remark = $remark;
        $admin->main_color = $main_color;
        $admin->second_color = $second_color;
        $admin->third_color = $third_color;
        $admin->staff_id = $token;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array('id' => $t));
    }

    public function actionEdit_idea_color()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $name = $post->name;
        $remark = $post->remark;
        $main_color = $post->main_color;
        $second_color = $post->second_color;
        $third_color = $post->third_color;
        $color_id = $post->color_id;
        // $name = $_POST['name'];
        // $remark = $_POST['remark'];
        // $main_color = $_POST['main_color'];
        // $second_color = $_POST['second_color'];
        // $third_color = $_POST['third_color'];
        // $color_id = $_POST['color_id'];
        // $name = '123';
        // $remark = '123';
        // $main_color = "#888888";
        // $second_color = "#999999";
        // $third_color = "#777777";
        // $color_id = 3;

        //修改
        $t = OrderShowIdeaColor::model()->updateByPk($color_id, array('name' => $name, 'remark' => $remark, 'main_color' => $main_color, 'second_color' => $second_color, 'third_color' => $third_color));

        echo json_encode(array('result' => $t));
    }

    public function actionAdd_show_img_to_order()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $order_id = $post->order_id;
        $img_id = $post->img_id;
        $service_product_id = 0;
        $token = 0;
        if(isset($post->service_product_id)){
            $service_product_id = $post->service_product_id;
        };
        if(isset($post->token)){
            $token = $post->token;
        };
        // $order_id = $_POST['order_id'];
        // $img_id = $_POST['img_id'];
        // $service_product_id = 0;
        // $token = 0;
        // if(isset($post->service_product_id)){
        //     $service_product_id = $_POST['service_product_id'];
        // };
        // if(isset($post->token)){
        //     $token = $_POST['token'];
        // };
        
        // $order_id = 1600;
        // $img_id = 0;
        // $token = 2222586;
        // $service_product_id = 826;

        //预备信息
        $subarea_id = 0;
        if($img_id != 0){
            $img = OrderShowImg::model()->findByPk($img_id);
            $subarea_id = $img['subarea_id'];
        }else{
            $service_product = ServiceProduct::model()->findByPk($service_product_id);
            $service_product_img = ServiceProductImg::model()->find(array(
                    'condition' => 'service_product_id=:spi',
                    'params' => array(
                            ':spi' => $service_product_id
                        )
                ));
            $img = OrderShowImg::model()->find(array(
                    'condition' => 'service_product_img_id=:spii',
                    'params' => array(
                            ':spii' => $service_product_img['id']
                        )
                ));
            if(empty($img)){
                $admin = new OrderShowImg;
                $admin->service_product_img_id = $service_product_img['id'];
                $admin->recommend = 0;
                $admin->type = 1;
                $admin->style_id = $service_product_img['order_show_img_style'];
                $admin->subarea_id = $service_product['subarea'];
                $admin->img_url = $service_product_img['img_url'];
                $admin->staff_id = $token;
                $admin->update_time = date('y-m-d h:i:s',time());
                $admin->save();
                $img_id = $admin->attributes['id'];
            }else{
                $img_id = $img['id'];
            };
            $subarea_id = $service_product['subarea'];
        };

        $sort = 1;
        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id && subarea=:subarea',
                'params' => array(
                        ':order_id' => $order_id,
                        ':subarea' => $img['subarea_id']
                    ),
                'order' => 'area_sort'
            ));
        if(!empty($order_show)){
            $sort = $order_show[0]['area_sort'];
        };

        $img = OrderShowImg::model()->findByPk($img_id);

        //存order_show
        $admin = new OrderShow;
        $admin->type = 1;
        $admin->img_id = $img_id;
        $admin->img_description = $img['description'];
        $admin->order_product_id = 0;
        $admin->words = 0;
        $admin->order_id = $order_id;
        $admin->subarea = $subarea_id;
        $admin->area_sort = $sort;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array('result' => $t));
    }

    public function actionDel_op()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $op_id = $post->op_id;

        OrderProduct::model()->deleteByPk($op_id);
        $t = OrderShow::model()->deleteAll('order_product_id=:op_id',array(':op_id'=>$op_id));

        echo json_encode(array('result' => $t));
    }

    public function actionUpdate_op()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $op_id = $post->op_id;
        $actual_price = $post->actual_price;
        $amount = $post->amount;
        $actual_unit_cost = $post->actual_unit_cost;

        $remark = '';
        if(isset($post->remark)){
            $remark = $post->remark;
        };
        // $post = array(
        //         'op_id' => 4037,
        //         'actual_price' => 109,
        //         'amount' => 109,
        //         'actual_unit_cost' => 109
        //     );

        OrderProduct::model()->updateByPk($op_id, array('actual_price'=>$actual_price, 'unit'=>$amount,'actual_unit_cost'=>$actual_unit_cost, 'remark'=>$remark));
    }

    public function actionInsert_order_show_img()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $subarea_id = $post->subarea_id;
        $img_url = $post->img_url;
        $staff_id = $post->staff_id;
        $description = $post->description;

        //新增show_img
        $admin = new OrderShowImg;
        $admin->type = 3;
        $admin->service_product_img_id = 0;
        $admin->subarea_id = $subarea_id;
        $admin->img_url = $img_url;
        $admin->staff_id = $staff_id;
        $admin->description = $description;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array('id' => $t));
    }

    public function actionDel_order_show_img()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $show_img_id = $post->show_img_id;

        $t = OrderShowImg::model()->deleteByPk($show_img_id);

        echo json_encode(array('result' => $t));
    }

    public function actionEdit_order_show_img()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $show_img_id = $post->show_img_id;
        $description = $post->description;

        $t = OrderShowImg::model()->updateByPk($show_img_id, array('description' => $description));

        echo json_encode(array('result' => $t));
    }

    public function actionBatch_no_supplier_product_insert()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $order_id = $post->order_id;
        $token = $post->token;
        $product_list = $post->product_list;   //结构： img_id|img_type|subarea_id|name|price|cost|unit|amount|description|remark,

        $list = explode(',', $product_list);
        foreach ($list as $key => $value) {
            $t = explode('|', $value);
            if(!empty($t)){
                $product = new ProductForm;
                $product->no_supplier_product_insert_to_my_product($token, $order_id, $t[0], $t[1], $t[2], $t[3], $t[4], $t[5], $t[6], $t[7], $t[8], $t[9]);
            };
        };
    }

    public function actionBatch_new_product_insert()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $order_id = $post->order_id;
        $token = $post->token;
        $product_list = $post->product_list;   //结构： img_id|img_type|subarea_id|name|price|cost|unit|amount|description|remark,

        $list = explode(',', $product_list);
        foreach ($list as $key => $value) {
            $t = explode('|', $value);
            if(!empty($t)){
                $product = new ProductForm;
                $product->own_account_new_product_insert($token, $order_id, $t[0], $t[1], $t[2], $t[3], $t[4], $t[5], $t[6], $t[7], $t[8], $t[9]);
            };
        };
    }

    public function actionDel_idea_color_order_show()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $show_id = $post->show_id;
        // $color_id = 3;

        //删除
        $t = OrderShow::model()->deleteByPk($show_id);

        echo json_encode(array('result' => $t));

    }

    public function actionDel_img_order_show()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $show_id = $post->show_img_id;
        $order_id = $post->order_id;

        $t = OrderShow::model()->deleteByPk($show_id);
        echo json_encode(array('result' => $t));
    }

    public function actionLibrary()
    {
        $this->render('library');
    }

    public function actionImg_filter()
    {
        //参数表
        $tab_list = $_GET['tab_list'];
        $page = $_GET['page'];
        $token = $_GET['token'];

        //预备信息
        $arr_tab = explode(',', $tab_list);
        $query = "";
        foreach ($arr_tab as $key => $value) {
            if($key != count($arr_tab)-1){
                $query .= "Case_ID in (select Resources_ID from library_tab_bind where Tab_ID=".$value.")"." and ";    
            }else{
                $query .= "Case_ID in (select Resources_ID from library_tab_bind where Tab_ID=".$value.")";
            };
        };

        $query_t = LibraryQuery::model()->find(array(
                'condition' => 'web_id=:web_id',
                'params' => array(
                        ':web_id' => 0
                    )
            ));
    
        $start = $query_t['start_id'];

        // $query .= " limit ".($page-1)*12).",12";

        //查询总页数
        $result = yii::app()->db->createCommand("select count(*) from  library_web_case_img where Img_ID>".$query_t['start_id']);
        $row = $result->queryAll();
        $total_page = ceil($row[0]['count(*)']/60);

        //查询订单
        $result = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date from `order` o ".
            // "left join order_model_select oms on o.id = oms.order_id ".
            " where designer_id=".$token." or planner_id=".$token);
        $result = $result->queryAll();
        $order_data = $this->get_order_doing_and_done2($result);

        //查询tab
        $result =  yii::app()->db->createCommand("select * from library_web_tab_type");
        $result =  $result->queryAll();
        $tab_data = array();
        foreach ($result as $key => $value) {
            $tab = yii::app()->db->createCommand("select * from library_web_tab where Type_ID=".$value['Type_ID']);
            $tab = $tab->queryAll();
            $item = array();
            $item['id'] = $value['Type_ID'];
            $item['name'] = $value['Type_Name'];
            $item['tab_list'] = array();
            foreach ($tab as $key1 => $value1) {
                $tem = array();
                $tem['id'] = $value1['Tab_ID'];
                $tem['name'] = $value1['Tab_Name'];
                $item['tab_list'][] = $tem;
            };
            $tab_data[] = $item;
        };

        //取img
        $return_data = array(
                'img' => array(),
                'page' => $total_page,
                'folder' => $this->Get_staff_folder($token),
                'order_doing' =>$order_data['doing'],
                'tab_data' => $tab_data,
                'area' => $this->get_area()
            );
        $result =  yii::app()->db->createCommand("select * from library_web_case_img img ".
            " where ".$query." limit ".(($page-1)*60).",60");
        $result =  $result->queryAll();
        foreach ($result as $key => $value) {
            $t = explode('/', $value['local_URL']);
            $item = array();
            $item['id'] = $value['Img_ID'];
            $item['name'] = '';
            $item['company'] = '';
            if(isset($t[0])){
                if($t[0] == 'http:'){
                    $item['cover_img'] = $value['local_URL'].'?x-oss-process=image/resize,m_lfit,h_800,w_800';
                    $item['cover_img_lg'] = $value['local_URL'];
                }else{
                    $item['cover_img'] = "http://file.cike360.com".ltrim($value['local_URL'], ".");
                    $item['cover_img_lg'] = "http://file.cike360.com".ltrim($value['local_URL'], ".");
                };
            };
            $item['page'] = '';
            $item['case_name'] = '';
            $item['case_description'] = '';
            $item['case_poster'] = '';
            $return_data['img'][] = $item;
        };

        echo json_encode($return_data);
    }

    public function get_area()
    {
        $area = array();
        $area_data = OrderShowArea::model()->findAll(array(
                'order' => 'sort'
            ));
        foreach ($area_data as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['total_price'] = 0;
            $item['proportion'] = 0;
            $item['empty'] = true;
            if($value['id'] == 1){
                $item['theme_words'] = '';    
            };
            if($value['id'] == 2){
                $item['theme_color'] = '';
                $item['main_color'] = '';
                $item['second_color'] = '';
                $item['third_color'] = '';
            };
            $item['subarea'] = array();
            $subarea = OrderShowAreaSubarea::model()->findAll(array(
                    'condition' => 'father_area=:father_area',
                    'params' => array(
                            ':father_area' => $value['id']
                        )
                ));
            foreach ($subarea as $key1 => $value1) {
                $tem = array();
                $tem['id'] = $value1['id'];
                $tem['name'] = $value1['name'];
                $item['subarea'][] = $tem;
            };
            $area[] = $item;
        };
        return $area;
    }

    public function Get_staff_folder($token)
    {
        $Folder = LibraryStaffFolder::model()->findAll(array(
                'condition' => 'Staff_ID=:Staff_ID',
                'params' => array(
                        ':Staff_ID' => $token
                    )
            ));
        $staff_folder = array();
        foreach ($Folder as $key => $value) {
            $bind = LibraryFolderBind::model()->findAll(array(
                    'condition' => 'Folder_ID=:Folder_ID',
                    'params' => array(
                            ':Folder_ID' => $value['Folder_ID']
                        )
                ));
            $item = array();
            $item['id'] = $value['Folder_ID'];
            $item['name'] = $value['Folder_Name'];
            $item['img_amount'] = count($bind);
            $staff_folder[] = $item;
        };
        return $staff_folder;
    }


    public function get_order_doing_and_done2($result)
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
            // if($value['model_id'] != null){
            //     $item['model_id'] = $value['model_id'];
            // };
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
                // if($item['model_id'] == 0){
                //     $order_non_model[]=$item;
                // };
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

    public function actionHistory_order()
    {
        //参数表
        $token = $_COOKIE['userid'];

        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,staff.name as designer_name,order_status from `order` o ".
            " left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done($tt);

        $this->render('history_order', array(
                'order_done' => $order_data['done']
            ));
    }


    public function actionBill()
    {
        //参数表
        $order_id = $_GET['order_id'];
        $token = $_GET['token'];

        //预备信息 
        $data = new OrderForm;
        $result = $data->get_order_detail($order_id, $token);

        foreach ($result['order_show'] as $key => $value) {
            if($value['area_id'] != 1 && $value['area_id'] != 2 && $value['area_id'] != 8){
                foreach ($value['subarea'] as $key1 => $value1) {
                    foreach ($value1['data'] as $key2 => $value2) {
                        if($value2['show_data'] != ''){
                            $t0 = explode('/', ltrim($value2['show_data'], 'http://file.cike360.com'));  
                            if(isset($t0[0])){
                                $xs = '';
                                $md = '';
                                if($t0[0] == 'upload'){
                                    $xs = str_replace("_sm", "_xs", $value2['show_data']);
                                    $md = str_replace("_sm", "_md", $value2['show_data']);
                                }else{
                                    $xs = $value2['show_data'];
                                    $md = $value2['show_data'];                 
                                };
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_xs' => $xs) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                                $result['order_show'][$key]['subarea'][$key1]['data'][$key2] = array('show_data_md' => $md) + $result['order_show'][$key]['subarea'][$key1]['data'][$key2];
                            };  
                        };  
                    };
                };
            }
            if($value['area_id'] == 15){
                unset($result['order_show'][$key]);
            };
        };

        foreach ($result['area_product'] as $key => $value) {
            foreach ($value['product_list'] as $key1 => $value1) {
                if($value1['ref_pic_url'] != ''){
                    $t0 = explode('/', ltrim($value1['ref_pic_url'], 'http://file.cike360.com') );  
                    if(isset($t0[0])){
                        $sm = '';
                        $md = '';
                        if($t0[0] == 'upload'){
                            $sm = str_replace("_xs", "_sm", $value1['ref_pic_url']);
                            $md = str_replace("_xs", "_md", $value1['ref_pic_url']);
                        }else{
                            $sm = $value1['ref_pic_url'];
                            $md = $value1['ref_pic_url'];                 
                        };
                        $result['area_product'][$key]['product_list'][$key1] = array('sm' => $sm) + $result['area_product'][$key]['product_list'][$key1];
                        $result['area_product'][$key]['product_list'][$key1] = array('md' => $md) + $result['area_product'][$key]['product_list'][$key1];
                    };  
                }; 
            };
            if($value['area_id'] == 15){
                unset($result['area_product'][$key]);
            };
        };

        //取邮箱
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

        $this->render('bill', array(
                'data' => $result,
                'email_list' => $email_list
            ));
    }

    public function actionGet_order_list()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $token = $post->token;
        // $token = 102;

        //获取订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,o.designer_id,staff.name as designer_name,order_status,o.order_type ".
            " from `order` o ".
            " left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done3($tt);

        $result = array(
                'wedding' => array(),
                'meeting' => array()
            );

        foreach ($order_data['doing'] as $key => $value) {
            $item = $value;
            //判断是否为自己的订单
            if($value['designer_id'] == $token){
                $item['is_mine'] = true;
            }else{
                $item['is_mine'] = false;
            };

            //判断是 婚礼／会议
            if($value['order_type'] == 2){
                $result['wedding'][] = $item;
            }else{
                $result['meeting'][] = $item;
            };
        };

        echo json_encode($result);
    }
    public function actionGet_all_order_list()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $token = $post->token;
        // $token = 102;

        //获取订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,o.designer_id,staff.name as designer_name,order_status,o.order_type ".
            " from `order` o ".
            " left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done3($tt);

        $result = array(
                'wedding' => array(),
                'meeting' => array()
            );

        foreach ($order_data['doing'] as $key => $value) {
            $item = $value;
            //判断是否为自己的订单
            if($value['designer_id'] == $token){
                $item['is_mine'] = true;
            }else{
                $item['is_mine'] = false;
            };

            //判断是 婚礼／会议
            if($value['order_type'] == 2){
                $result['wedding'][] = $item;
            }else{
                $result['meeting'][] = $item;
            };
        };

        foreach ($order_data['done'] as $key => $value) {
            $item = $value;
            //判断是否为自己的订单
            if($value['designer_id'] == $token){
                $item['is_mine'] = true;
            }else{
                $item['is_mine'] = false;
            };

            //判断是 婚礼／会议
            if($value['order_type'] == 2){
                $result['wedding'][] = $item;
            }else{
                $result['meeting'][] = $item;
            };
        };

        echo json_encode($result);
    }

    public function get_order_doing_and_done3($result)
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
                    'designer_id' => $value['designer_id'],
                    'designer_name' => $value['designer_name'],
                    'order_date' => $value['order_date'],
                    'order_type' => $value['order_type'],
                    'model_id' => 0,
                    'to_date' => 0,
                    'order_status' => $value['order_status']
                );
            if($item['order_status'] == 0){$item['order_status'] = '待定';};
            if($item['order_status'] == 1){$item['order_status'] = '预定';};
            if($item['order_status'] == 2){$item['order_status'] = '已付定金';};
            if($item['order_status'] == 3){$item['order_status'] = '付中期款';};
            if($item['order_status'] == 4){$item['order_status'] = '已付尾款';};
            if($item['order_status'] == 5){$item['order_status'] = '结算中';};
            if($item['order_status'] == 6){$item['order_status'] = '已结算';};

            // if($value['model_id'] != null){
            //     $item['model_id'] = $value['model_id'];
            // };
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
                // if($item['model_id'] == 0){
                //     $order_non_model[]=$item;
                // };
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
        foreach ($order_done as $key => $value) {
            $t = explode(' ', $value['order_date']);
            $order_done[$key]['order_date'] = $t[0];
        };

        $return_data = array(
                'doing' => $order_doing,
                'done' => $order_done,
                'non_model' => $order_non_model,
                'nearest' => $order_nearest
            );

        return $return_data;
    }

    public function actionOrder_template_list()
    {
        $this->render('order_template_list');
    }

    public function actionGet_case_list()
    {
        //参数表
        $CI_Type = $_GET['CI_Type'];
        $staff_id = $_GET['staff_id'];
        $page = $_GET['page'];
        $color_id = '';
        $style_id = '';
        $space_id = '';
        if(isset($_GET['color_id'])){
            $color_id = $_GET['color_id'];    
        };
        if(isset($_GET['style_id'])){
            $color_id = $_GET['style_id'];    
        };
        if(isset($_GET['space_id'])){
            $color_id = $_GET['space_id'];    
        };

        //预备信息
        if($page == ''){$page = 1;};
        $start = ($page-1)*9;
        $color_querry = '';
        $style_querry = '';
        $space_querry = '';
        if($color_id != ''){
            $color_querry = ' and color='.$color_id;
        };
        if($style_id != ''){
            $style_querry = ' and style='.$style_id;  
        };
        if($space_id != ''){
            $space_querry = ' and space='.$space_id;    
        };

        //查询总页数
        $result = yii::app()->db->createCommand("select * from case_info where ".
                "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                    "(select account_id from staff where id=".$staff_id.") ) ".
                " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
                    "(select hotel_list from staff where id=".$staff_id.") ) ".
                " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".
            " and CI_Show=1 and CI_Type=".$CI_Type.$space_querry.$style_querry.$color_querry);
        $list = $result->queryAll();
        $total_page = ceil(count($list)/9);

        //构造id_list
        $color_list = '(';
        $space_list = '(';
        $style_list = '(';
        foreach ($list as $key => $value) {
            $color_list .= $value['color'].',';
            $style_list .= $value['style'].',';
            $space_list .= $value['space'].',';
        };
        $color_list = rtrim($color_list, ',');
        $color_list .= ")";
        $style_list = rtrim($style_list, ',');
        $style_list .= ")";
        $space_list = rtrim($space_list, ',');
        $space_list .= ")";

        //查询筛选条件
        $color = yii::app()->db->createCommand('select id,name from case_info_color where id in '.$color_list);
        $color = $color->queryAll();
        $style = yii::app()->db->createCommand('select id,name from case_info_color where id in '.$style_list);
        $style = $style->queryAll();
        $space = yii::app()->db->createCommand('select id,name from case_info_color where id in '.$space_list);
        $space = $space->queryAll();

        

        //查询case
        $result = yii::app()->db->createCommand("select * from case_info where ".
                "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                    "(select account_id from staff where id=".$staff_id.") ) ".
                " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
                    "(select hotel_list from staff where id=".$staff_id.") ) ".
                " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".
            " and CI_Show=1 and CI_Type=".$CI_Type.$space_querry.$style_querry.$color_querry.
            " order by CI_Sort Desc ".
            " limit ".$start.",9");
        $list = $result->queryAll();

        $list_data = array();
        foreach($list as  $key => $val){
            $cr = CaseResources::model()->find(array(
                    'condition' => 'CI_ID=:CI_ID && CR_Show=:CR_Show && CR_Type=:CR_Type',
                    'params' => array(
                            ':CI_ID' => $val['CI_ID'],
                            ':CR_Type' => 1,
                            ':CR_Show' => 1
                        )
                ));
            $item = array();
            $item['CI_ID'] = $val['CI_ID'];
            $item['CI_Name'] = $val['CI_Name'];
            if(!empty($cr)){
                $item['CI_Pic'] = $cr['CR_Path'].'?x-oss-process=image/resize,m_lfit,h_500,w_500';
            }else{
                $item['CI_Pic'] = "images/cover.jpg";
            };
            $item['CI_Type'] = $val['CI_Type'];
            $item['CT_ID'] = $val['CT_ID'];
            $item['CI_Remarks'] = $val['CI_Remarks'];
            $list_data[] = $item;
        };

        echo json_encode(array(
                'list_data' => $list_data,
                'total_page' => $total_page,
                'color' => $color,
                'style' => $style,
                'space' => $space
            ));
    }

    public function actionGet_my_collection()
    {
        //参数表
        $token = $_GET['token'];
        $type_id = $_GET['type_id'];
        $page = $_GET['page'];

        //预备信息
        if($page == ""){
            $page = 1;
        };
        $start = ($page-1)*9;
        $query = '';
        if($type_id != ''){
            $query = ' and tab_id='.$type_id;    
        };
        

        //查找总页数
        $folder = yii::app()->db->createCommand('select * from library_staff_folder '.
            ' where Folder_ID not in (select folder_id from library_share_folder) and staff_id='.$token.$query);
        $folder = $folder->queryAll();
        $total_page = ceil(count($folder)/9);

        //查找folder
        $folder = yii::app()->db->createCommand('select * from library_staff_folder '.
            ' where Folder_ID not in (select folder_id from library_share_folder) '.
            ' and staff_id='.$token.$query." limit ".$start.",9");
        $folder = $folder->queryAll();
        $folder_data = array();
        foreach ($folder as $key => $value) {
            $folder_img = yii::app()->db->createCommand('select * from library_web_case_img where Img_ID in '.
                '(select Img_ID from library_folder_bind where Folder_ID='.$value['Folder_ID'].')');
            $folder_img = $folder_img->queryAll();
            if(!empty($folder_img)){
                $url = 'http://file.cike360.com'.$this->add_string_to_url($folder_img[0]['local_URL'], 'xs');
                $t = explode('/', $folder_img[0]['local_URL']);
                if(!empty($t)){
                    if($t[0] == 'http:'){
                        $url = $folder_img[0]['local_URL'].'?x-oss-process=image/resize,m_lfit,h_300,w_300';
                    };
                };
                $item = array();
                $item['id'] = $value['Folder_ID'];
                $item['name'] = $value['Folder_Name'];
                $item['img_url'] = $url;
                $folder_data[] = $item;
            };
        };

        //查找 folder_type
        $type = yii::app()->db->createCommand('select * from library_staff_folder_tab where id in '.
            ' (select tab_id from library_staff_folder where staff_id='.$token.' and Folder_ID not in (select folder_id from library_share_folder))');
        $type = $type->queryAll();

        $folder_type = array();
        $t1 = array('id' => '', 'name' => '全部');
        $folder_type[] = $t1;
        foreach ($type as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $folder_type[] = $item;
        };
        $t2 = array('id' => 0, 'name' => '其他');
        $folder_type[] = $t2;

        echo json_encode(array(
                'folder_data' => $folder_data,
                'total_page' => $total_page,
                'type' => $folder_type
            ));
    }

    public function actionGet_service_person()
    {
        //参数表
        $userid = $_GET['staff_id'];
        $type_id = $_GET['type_id'];
        $page = $_GET['page'];

        //预备信息
        $staff = Staff::model()->findByPk($userid);
        $account_id = $staff['account_id'];
        $company = StaffCompany::model()->findByPk($account_id);
        if($page == ""){
            $page = 1;
        };
        $start = ($page-1)*9;
        $query = '';
        if($type_id != ''){
            $query = 'and service_type='.$type_id;    
        }else{
            $query = " and service_type in (select id from supplier_type where role=2)";
        };

        //查询总页数
        $service_person = yii::app()->db->createCommand("select s.id,s.service_type,s.name,s.telephone,supplier_type.name as type_name ".
            " from service_person s left join supplier_type on s.service_type=supplier_type.id".
            " where s.show=1 and s.staff_id in (select staff_id from supplier where account_id=".$account_id." ) ".$query);
        $service_person =  $service_person->queryAll();
        $total_page = ceil(count($service_person)/9);

        //查询人员
        $service_person = yii::app()->db->createCommand("select s.id,s.service_type,s.name,s.telephone,supplier_type.name as type_name ".
            " from service_person s left join supplier_type on s.service_type=supplier_type.id".
            " where s.show=1 and s.staff_id in (select staff_id from supplier where account_id=".$account_id." ) ".$query." limit ".$start.",9");
        $service_person =  $service_person->queryAll();

        //构造返回信息
        $person_data = array();
        foreach ($service_person as $key => $value) {
            $img = ServicePersonImg::model()->find(array(
                    'condition' => 'service_person_id=:service_person_id',
                    'params' => array(
                            ':service_person_id' => $value['id']
                        )
                ));
            if(!empty($img)){
                $url = 'http://file.cike360.com'.$this->add_string_to_url($img['img_url'], 'sm');
                $t = explode('/', $img['img_url']);
                if(isset($t[0])){
                    if($t[0] == 'http:'){
                        $url = $img['img_url'];
                    };
                };
            }else{
                $url = "images/cover.jpg";
            };
            $product = ServiceProduct::model()->findAll(array(
                    'condition' => 'service_person_id=:service_person_id && product_show=:product_show',
                    'params' => array(
                            ':service_person_id' => $value['id'],
                            ':product_show' => 1
                        ),
                    'order' => 'price'
                ));
            $ad_description = ServiceAdData::model()->find(array(
                'condition' => 'data_type=:type && service_person_id=:spi',
                'params' => array(
                        ':type' => 0,
                        ':spi' => $value['id']
                    )
            ));
            $description = '';
            if(!empty($ad_description)){
                $description = $ad_description['data'];
            };

            $item = array();
            $item['service_person_id'] = $value['id'];
            $item['type_id'] = $value['service_type'];
            $item['type_name'] = $value['type_name'];
            $item['name'] = $value['name'];
            $item['telephone'] = $value['telephone'];
            $item['img'] = $url;
            $item['description'] = $description;
            $item['subarea_id'] = '';
            $item['price'] = array(
                    'min_price' => 0,
                    'price_list' => array()
                );
            foreach ($product as $key => $value) {
                $tem = array();
                $tem['id'] = $value['id'];
                $tem['name'] = $value['product_name'];
                $tem['price'] = $value['price'];
                $item['price']['price_list'][] = $tem;
                $item['subarea_id'] = $value['subarea'];
            };
            if(!empty($product)){
                $item['price']['min_price'] = $product[0]['price'];
            };
            $person_data[] = $item;
        };
        
        //取人员类别
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'role=:role',
                'params' => array(
                        ':role' => 2
                    )
            ));
        $type = yii::app()->db->createCommand('select * from supplier_type where role=2 order by sort');
        $type = $type->queryAll();

        //取订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,staff.name as designer_name,order_status from `order` o ".
            " left join staff on designer_id=staff.id ".
            " where designer_id=".$userid." or planner_id=".$userid);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done($tt);
        
        echo json_encode(array(
                'person' => $person_data,
                'total_page' => $total_page,
                'type' => $type,
                'order_doing' => $order_data['doing']
            ));
    }


    public function actionInvite_use()
    {
        
        // parent::init();
        // if ($this->scope === null) {
        //     $this->scope = implode(',', [
        //         'snsapi_userinfo',
        //     ]);
        // }
        $url ='http://crm.cike360.com/portal/index.php?r=dailyReport/Get_weixin_open_data&code=8888';
        $contentStr = file_get_contents($url);
        $contentArr = json_decode($contentStr);
        $appId = $contentArr->id;
        $redirectUrl = 'http://dev.msch.com/portal/index.php?r=background/weixin_app_get_code';//这个域名需要改成你们自己的
        $state = rand();
        $url= 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appId.'&redirect_uri='.$redirectUrl.'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
        Header("HTTP/1.1 303 See Other");
        Header("Location: $url");
        exit;
        //$this->render("invite_use");
    }

    public function actionweixin_app_get_code(){
        $code = $_GET['code'];
        if(empty($code)){
            //用户未授权
        }else{
            $url ='http://crm.cike360.com/portal/index.php?r=dailyReport/Get_weixin_open_data&code=8888';
            $contentStr = file_get_contents($url);
            $contentArr = json_decode($contentStr);
            $appId = $contentArr->id;
            $secret = $contentArr->secret;
            $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
            $token = file_get_contents($tokenUrl);
            $tokenObj = json_decode($token);
            $accessToken = $tokenObj->access_token;
            $openId = $tokenObj->openid;
            $userUrl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$accessToken.'&openid='.$openId;
            $userStr = file_get_contents($userUrl);
            $userObj = json_decode($userStr);
            var_dump($userObj);//此处获取用户信息
            $this->render("invite_use");
        }
        
    }

    public function buildAuthUrl(array $params = [])
    {
        $authState = $this->generateAuthState();
        $this->setState('authState', $authState);
        $defaultParams = [
            'appid' => $this->clientId,
            'redirect_uri' => $this->getReturnUrl(),
            'response_type' => 'code',
        ];
        if (!empty($this->scope)) {
            $defaultParams['scope'] = $this->scope;
        }
        $defaultParams['state'] = $authState;
        $url = $this->type == 'mp'?$this->authUrlMp:$this->authUrl;
        return $this->composeUrl($url, array_merge($defaultParams, $params));
    }
    /**
     * @inheritdoc
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        $authState = $this->getState('authState');
        if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
            throw new HttpException(400, 'Invalid auth state parameter.');
        } else {
            $this->removeState('authState');
        }
        $params['appid'] = $this->clientId;
        $params['secret'] = $this->clientSecret;
        return parent::fetchAccessToken($authCode, $params);
    }
    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $params['access_token'] = $accessToken->getToken();
        $params['openid'] = $accessToken->getParam('openid');
        $params['lang'] = 'zh_CN';
        return $this->sendRequest($method, $url, $params, $headers);
    }
    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('sns/userinfo');
//        $userAttributes['id'] = $userAttributes['unionid'];
//        return $userAttributes;
    }
    /**
     * @inheritdoc
     */
    protected function defaultReturnUrl()
    {
        $params = $_GET;
        unset($params['code']);
        unset($params['state']);
        $params[0] = Yii::$app->controller->getRoute();
        return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
    }
    /**
     * Generates the auth state value.
     * @return string auth state value.
     */
    protected function generateAuthState()
    {
        return sha1(uniqid(get_class($this), true));
    }
    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weixin';
    }
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Weixin';
    }
    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 800,
            'popupHeight' => 500,
        ];
    }

    public function actionGet_my_order_info()
    {
        //参数表
        $token = $_GET['token'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $page = $_GET['page'];


        //预备信息
        $income = ServiceCashRevenue::model()->findAll(array(
                'condition' => 'staff_id=:staff_id',
                'params' => array(
                        ':staff_id' => $token
                    )
            ));
        $expend = ServiceCashExpend::model()->findAll(array(
                'condition' => 'staff_id=:staff_id',
                'params' => array(
                        ':staff_id' => $token
                    )
            ));
        $service_type_querry = '';
        if(isset($_GET['service_type'])){
            $service_type_querry = 'serp.service_type='.$_GET['service_type'].' and ';
        };

        //构造账户基本信息
        $my_money = 0;
        $all_income = 0;
        $pre_income = 0;
        foreach ($income as $key => $value) {
            $zero1=strtotime (date('y-m-d 0:0:0'));
            $zero2=strtotime ($value['update_time']);
            if($zero2 >= $zero1){
                $pre_income += $value['price'];
            };
            $all_income += $value['price'];
            $my_money += $value['price'];
        };
        foreach ($expend as $key => $value) {
            $my_money -= $value['price'];
        };
        
        
        
        

        //构造trade_list
        $start = ($page-1)*20;
        $cur_month = strtotime(date($year."-".$month."-01"));
        $next_month = strtotime(date($year."-".($month+1)."-01"));
        $trade_list = yii::app()->db->createCommand('select sos.id as trade_id,sos.update_time as trade_time,serp.product_name as trade_name,(sos.amount*sos.actual_price) as trade_money,status as trade_status,staff_id as buyer_id,s.name as buyer_name'.
            ' from supplier_order_shopping sos '.
            ' left join service_product serp on sos.service_product_id=serp.id'.
            ' left join staff s on s.id=sos.staff_id '.
            ' where '.$service_type_querry.' serp.service_person_id in '.
                ' (select id from service_person where staff_id='.$token.')');
        $trade_list = $trade_list->queryAll();
        $i = 0;
        foreach ($trade_list as $key => $value) {
            $t = strtotime($value['trade_time']);
            if($t < $cur_month || $t >= $next_month){
                unset($trade_list[$key]);
            }else{
                $i++;
                if($i < $page*20 && $i >= ($page-1)*20){
                    if($value['trade_status'] == 0){$trade_list[$key]['trade_status'] = '待付款';};
                    if($value['trade_status'] == 1){$trade_list[$key]['trade_status'] = '付款成功';};
                    if($value['trade_status'] == 2){$trade_list[$key]['trade_status'] = '付款失败';};
                    if($value['trade_status'] == 3){$trade_list[$key]['trade_status'] = '待收货';};
                    if($value['trade_status'] == 4){$trade_list[$key]['trade_status'] = '待评价';};
                    if($value['trade_status'] == 5){$trade_list[$key]['trade_status'] = '已完成';};
                }else{
                    unset($trade_list[$key]);
                };
            };
        };

        //构造total_page
        $total_page = ceil(count($i)/20);

        $list = array();
        foreach ($trade_list as $key => $value) {
            $list[] = $value;
        };

        $result = array(
                'account' => array(
                        'my_money' => $my_money,
                        'all_income' => $all_income,
                        'pre_income' => $pre_income
                    ),
                'trade_list' => $list,
                'total_page' => $total_page,
                'cur_page' => $page
            );
        echo json_encode($result);
    }

    public function actionGet_service_product_list()
    {
        //参数表
        $type = $_GET['type'];
        $token = $_GET['token'];
        $page = $_GET['page'];

        //预备信息
        $start = ($page-1)*20;
        $total_page = 0;
        $product = array();
        $filter = array();

        switch ($type)
        {
            case 'drawing':
                $case = $_GET['case_id'];
                $color = $_GET['color_id'];
                $style = $_GET['style_id'];

                //构造查询条件
                $case_querry = '';
                $color_querry = '';
                $style_querry = '';
                if($case != ''){$case_querry = ' and order_show_img_case='.$case;};
                if($color != ''){$color_querry = ' and order_show_img_color='.$color;};
                if($style != ''){$style_querry = ' and order_show_img_style='.$style;};

                //构造 total_page
                $img = yii::app()->db->createCommand('select spi.id,spi.service_product_id,spi.img_url,serp.product_name,serp.price from service_product_img spi'.
                    ' left join service_product serp on spi.service_product_id=serp.id '.
                    ' where product_show=1 and service_product_id in '.
                    ' (select id from service_product where service_type=30 and service_person_id in '.
                        '(select id from service_person where staff_id='.$token.'))'.$case_querry.$color_querry.$style_querry);
                $img = $img->queryAll();
                $img_id_list = '(';
                foreach ($img as $key => $value) {
                    $img_id_list .= $value['id'].",";
                };
                $img_id_list = rtrim($img_id_list, ',');
                $img_id_list .= ")";
                $total_page = ceil(count($img)/20);

                //查询 product
                $product = yii::app()->db->createCommand('select spi.service_product_id,spi.img_url,serp.product_name,serp.cost as price from service_product_img spi'.
                    ' left join service_product serp on spi.service_product_id=serp.id '.
                    ' where product_show=1 and service_product_id in '.
                    ' (select id from service_product where service_type=30 and service_person_id in '.
                        '(select id from service_person where staff_id='.$token.'))'.$case_querry.$color_querry.$style_querry.' order by serp.update_time DESC limit '.$start.',20');
                $product = $product->queryAll();

                //查询条件
                $case_filter = yii::app()->db->createCommand('select * from order_show_img_case where '.
                    ' id in (select order_show_img_case from service_product_img where id in '.$img_id_list.')'.
                    ' and staff_id='.$token.' and `show`=1');
                $case_filter = $case_filter->queryAll();
                $color_filter = yii::app()->db->createCommand('select * from order_show_img_color where '.
                    ' id in (select order_show_img_color from service_product_img where id in '.$img_id_list.') and `show`=1');
                $color_filter = $color_filter->queryAll();
                $style_filter = yii::app()->db->createCommand('select * from order_show_img_style where '.
                    ' id in (select order_show_img_style from service_product_img where id in '.$img_id_list.') and `show`=1');
                $style_filter = $style_filter->queryAll();
                $filter = array(
                        0 => array(
                                'name' => '案例',
                                'list' => $case_filter
                            ),
                        1 => array(
                                'name' => '颜色',
                                'list' => $color_filter
                            ),
                        2 => array(
                                'name' => '风格',
                                'list' => $style_filter
                            )
                    );
                break;
            case 'case':
                $t = $this->get_case($token, $_GET['type_id'], $start);
                $product = $t['product'];
                $total_page = $t['total_page'];
                $tem = array(
                        'name' => '类别',
                        'list' => $t['filter']
                    );
                $filter[] = $tem;
              break;
            case 'prop':
                $t = $this->get_serp($token, 9, $_GET['service_type'], $start);
                $product = $t['product'];
                $total_page = $t['total_page'];
                $tem = array(
                        'name' => '类别',
                        'list' => $t['filter']
                    );
                $filter[] = $tem;
                break;
        };


        $result = array(
                'product' => $product,
                'filter' => $filter,
                'total_page' => $total_page,
                'cur_page' => $page
            );
        echo json_encode($result);
    }

    public function get_serp($staff_id, $supplier_type_role, $supplier_type, $start)
    {
        //构造查询条件
        $query = 'service_type in (select id from supplier_type where role='.$supplier_type_role.')';
        if($supplier_type != ''){
            $query = 'service_type='.$supplier_type;
        };
        
        //查 service_product
        $service_product = yii::app()->db->createCommand('select * from service_product where '.
            ' service_person_id in (select id from service_person where staff_id='.$staff_id.') and product_show=1 '.
            ' and '. $query .' order by id DESC');
        $service_product = $service_product->queryAll();
        $total_page = ceil(count($service_product)/20);
        $service_product = yii::app()->db->createCommand('select * from service_product where '.
            ' service_person_id in (select id from service_person where staff_id='.$staff_id.') and product_show=1 '.
            ' and '. $query .' order by id DESC limit '.$start.",20");
        $service_product = $service_product->queryAll();

        //查询 product
        $product_id_list = '(';
        foreach ($service_product as $key => $value) {
            $product_id_list .= $value['id'].",";
            $service_product_img = ServiceProductImg::model()->find(array(
                    'condition' => 'service_product_id=:service_product_id',
                    'params' => array(
                            ':service_product_id' => $value['id']
                        )
                ));
            $t = explode('/', $service_product_img['img_url']);
            $url = '';
            if(isset($t[0])){
                if($t[0] == 'http:'){
                    $url = $service_product_img['img_url'].'?x-oss-process=image/resize,m_lfit,h_500,w_500';
                }else{
                    $url = "http://file.cike360.com".$this->add_string_to_url($service_product_img['img_url'],"xs");
                };
            };

            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['product_name'];
            $item['price'] = $value['cost'];
            $item['total_inventory'] = $value['total_inventory'];
            $service_order_product = ServiceOrderProduct::model()->findAll(array(
                    'condition' => 'service_person_id=:service_product_id',
                    'params' => array(
                            ':service_product_id' => $value['id']
                        )
                ));
            $item['sales'] = count($service_order_product);
            $item['ref_pic_url'] = $url;

            $product[] = $item;
        };
        $product_id_list = rtrim($product_id_list, ',');
        $product_id_list .= ")";

        //查询 filter
        $filter = yii::app()->db->createCommand('select id,name from supplier_type where id in '.
            ' (select service_type from service_product where id in '.$product_id_list.') ');
        $filter = $filter->queryAll();

        $result = array(
                'product' => $product,
                'total_page' => $total_page,
                'filter' => $filter
            );
        return $result;
    }

    public function get_case($token, $type, $start)
    {
        //构造查询条件
        $query = '';
        if($type != ''){
            $query = ' and type_id='.$type;
        };

        //查找store_case
        $case = yii::app()->db->createCommand('select sc.id,sc.name,sp.price,sc.wedding_price,sct.name as type_name '.
            ' from store_case sc'.
            ' left join service_product sp on sc.service_product_id=sp.id '.
            ' left join store_case_type sct on sct.id=sc.type_id '.
            ' where sc.case_show=1 and staff_id='.$token.$query.' order by type_id');
        $case = $case->queryAll();
        $total_page = ceil(count($case)/20);
        $case = yii::app()->db->createCommand('select sc.id,sc.name,sp.price,sc.wedding_price,sct.name as type_name '.
            ' from store_case sc'.
            ' left join service_product sp on sc.service_product_id=sp.id '.
            ' left join store_case_type sct on sct.id=sc.type_id '.
            ' where sc.case_show=1 and staff_id='.$token.$query.' order by type_id limit '.$start.",20");
        $case = $case->queryAll();

        //构造首图
        $case_data = array();
        $case_id_list = '(';
        foreach ($case as $key => $value) {
            $case_id_list .= $value['id'].",";
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['price'] = $value['price'];
            $item['wedding_price'] = $value['wedding_price'];
            $item['type_name'] = $value['type_name'];
            $img = StoreCaseImg::model()->find(array(
                    'condition' => 'store_case_id=:sci',
                    'params' => array(
                            ':sci' => $value['id']
                        )
                ));
            $item['poster'] = $img['img_url'].'?x-oss-process=image/resize,m_lfit,h_1000,w_1000';
            $case_data[] = $item;
        };
        $case_id_list = rtrim($case_id_list, ',');
        $case_id_list .= ")";

        //构造filter
        $filter = yii::app()->db->createCommand('select id,name from store_case_type where id in (select type_id from store_case where id in '.$case_id_list.')');
        $filter = $filter->queryAll();

        $result = array(
                'product' => $case_data,
                'filter' => $filter,
                'total_page' => $total_page
            );
        return $result;
    }

    public function actionGet_case_color_style_list()
    {
        //参数表
        $token = $_GET['token'];
        
        echo json_encode($this->get_ccs_list($token));
    }

    public function get_ccs_list($token)
    {
        //area
        $subarea = yii::app()->db->createCommand('select id,name from order_show_area_subarea where supplier_type=30');
        $subarea = $subarea->queryAll();

        //case
        $case = yii::app()->db->createCommand('select id,name from order_show_img_case where staff_id='.$token.' and `show`=1');
        $case = $case->queryAll();

        //color
        $color = yii::app()->db->createCommand('select id,name from order_show_img_color where `show`=1');
        $color = $color->queryAll();

        //style
        $style = yii::app()->db->createCommand('select id,name from order_show_img_style where `show`=1');
        $style = $style->queryAll();

        return array(
                'subarea' => $subarea,
                'case' => $case,
                'color' => $color,
                'style' => $style
            );
    }

    public function actionInsert_service_product()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $staff_id = $post->token;
        $subarea_id = $post->area_id;
        $name = $post->name;
        $price = $post->price;
        $total_inventory = $post->total_inventory;
        $unit = $post->unit;
        $description = $post->description;
        $img_list = $post->img_list;
        $case_id = 0;
        $color_id = 0;
        $style_id = 0;
        $psd = '';
        if(isset($post->case_id)){
            $case_id = $post->case_id;
        };
        if(isset($post->color_id)){
            $color_id = $post->color_id;
        };
        if(isset($post->style_id)){
            $style_id = $post->style_id;
        };
        if(isset($post->psd)){
            $psd = $post->psd;
        };


        // $staff_id = '102';
        // $subarea_id = '4';
        // $name = '';
        // $price = 100;
        // $total_inventory = 100;
        // $unit = '张';
        // $description = '';
        // $img_list = '1,2,3';
        // $case_id = 0;
        // $color_id = 0;
        // $style_id = 0;
        // $psd = '';


        //预备信息
        $staff = Staff::model()->findByPk($staff_id);
        $subarea = OrderShowAreaSubarea::model()->findByPk($subarea_id);
        $service_person = ServicePerson::model()->find(array(
                'condition' => 'staff_id=:staff_id && service_type=:service_type',
                'params' => array(
                        ':staff_id' => $staff_id,
                        ':service_type' => $subarea['supplier_type']
                    )
            ));
        $service_person_id = 0;
        $img_list = explode(',', $img_list);

        if(empty($service_person)){
            $admin = new ServicePerson;
            $admin->team_id = 2;
            $admin->name = $staff['name'];
            $admin->show = 1;
            $admin->status = 1;
            $admin->telephone = $staff['telephone'];
            $admin->update_time = date('Y-m-d h:i:s',time());
            $admin->staff_id = $staff_id;
            $admin->service_type = $subarea['supplier_type'];
            $admin->save();
            $service_person_id = $admin->attributes['id'];
        }else{
            $service_person_id = $service_person['id'];
        };

        if($subarea['supplier_type'] == 30){
            foreach ($img_list as $key => $value) {
                if($value != ''){
                    //插入 service_product
                    $admin = new ServiceProduct;
                    $admin->service_person_id = $service_person_id;
                    $admin->service_type = $subarea['supplier_type'];
                    $admin->product_name = $name;
                    $admin->subarea = $subarea_id;
                    $admin->decoration_tap = $subarea['decoration_tap'];
                    $admin->recommend = 0;
                    $admin->price = $price;
                    $admin->cost = $price;
                    $admin->unit = $unit;
                    $admin->update_time = date('Y-m-d h:i:s',time());;
                    $admin->ref_pic_url = $value;
                    $admin->description = $description;
                    $admin->product_show = 1;
                    $admin->total_inventory = $total_inventory;
                    $admin->save();
                    $service_product_id = $admin->attributes['id'];

                    //插入 service_product_img
                    $admin = new ServiceProductImg;
                    $admin->service_product_id = $service_product_id;
                    $admin->img_url = $value;
                    $admin->order_show_img_case = $post->case_id;
                    $admin->order_show_img_color = $post->color_id;
                    $admin->order_show_img_style = $post->style_id;
                    $admin->psd_url = $post->psd;
                    $admin->sort = $key+1;
                    $admin->update_time = date('Y-m-d h:i:s',time());
                    $admin->save();
                };
            };
        }else{
            //插入 service_product
            $admin = new ServiceProduct;
            $admin->service_person_id = $service_person_id;
            $admin->service_type = $subarea['supplier_type'];
            $admin->product_name = $name;
            $admin->subarea = $subarea_id;
            $admin->decoration_tap = $subarea['decoration_tap'];
            $admin->recommend = 0;
            $admin->price = $price;
            $admin->cost = $price;
            $admin->unit = $unit;
            $admin->update_time = date('Y-m-d h:i:s',time());;
            $admin->ref_pic_url = $img_list[0];
            $admin->description = $description;
            $admin->product_show = 1;
            $admin->total_inventory = $total_inventory;
            $admin->save();
            $service_product_id = $admin->attributes['id'];

            //插入 service_product_img

            foreach ($img_list as $key => $value) {
                $admin = new ServiceProductImg;
                $admin->service_product_id = $service_product_id;
                $admin->img_url = $value;
                $admin->sort = $key+1;
                $admin->update_time = date('Y-m-d h:i:s',time());
                $admin->save();
            };
        }       

        echo 1;
    }

    public function actionUpdate_service_product()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $staff_id = $post->token;
        $service_product_id = $post->service_product_id;
        $name = $post->name;
        $price = $post->price;
        $total_inventory = $post->total_inventory;
        $unit = $post->unit;
        $description = $post->description;
        $img_list = $post->img_list;
        $case_id = '';
        $color_id = '';
        $style_id = '';
        $area_id = '';

        if(isset($post->case_id)){
            $case_id = $post->case_id;    
        };
        if(isset($post->case_id)){
            $color_id = $post->color_id;    
        };
        if(isset($post->case_id)){
            $style_id = $post->style_id;    
        };
        if(isset($post->area_id)){
            $area_id = $post->area_id;
        };

        // $staff_id = 102;
        // $service_product_id = 9522;
        // $name = "测试";
        // $price = "2.52";
        // $total_inventory = "3";
        // $unit = "个";
        // $description = "迎宾水牌精美花艺装饰，迎宾水牌木架一座，主题色纱幔木架装饰，个性迎宾水牌设计";
        // $img_list = "http://inspitation-img-store.oss-cn-beijing.aliyuncs.com/jZz6w8X4Yc.JPG";

        //预备信息
        $service_product = ServiceProduct::model()->findByPk($service_product_id);
        $subarea_id = $service_product['subarea'];
        $subarea = OrderShowAreaSubarea::model()->findByPk($subarea_id);
        $service_person = ServicePerson::model()->find(array(
                'condition' => 'staff_id=:staff_id',
                'params' => array(
                        ':staff_id' => $staff_id,
                    )
            ));

        //修改service_product
        ServiceProduct::model()->updateByPk($service_product_id,array(
            'product_name'=>$name,
            'cost'=>$price,
            'unit'=>$unit,
            'subarea'=>$area_id,
            'ref_pic_url'=>$img_list[0],
            'description'=>$description,
            'total_inventory'=>$total_inventory,
            'update_time'=>date('y-m-d h:i:s',time())
        ));

        //删除原有img，新增img
        ServiceProductImg::model()->deleteAll('service_product_id=:spi',array(':spi' => $service_product_id));
        $arr_img = explode(',', $img_list);
        foreach ($arr_img as $key => $value) {
            $admin = new ServiceProductImg;
            $admin->service_product_id = $service_product_id;
            $admin->order_show_img_case = $case_id;
            $admin->order_show_img_color = $color_id;
            $admin->order_show_img_style = $style_id;
            $admin->img_url = $value;
            $admin->psd_url = $post->psd;
            $admin->sort = $key+1;
            $admin->update_time = date('Y-m-d h:i:s',time());
            $admin->save();
        };
        echo "1";
    }

    public function actionGet_product_detail()
    {
        //参数表
        $service_product_id = $_GET['service_product_id'];
        $token = $_GET['token'];

        //预备信息
        $service_product = ServiceProduct::model()->findByPk($service_product_id);
        $service_product_img = ServiceProductImg::model()->findAll(array(
                'condition' => 'service_product_id=:spi',
                'params' => array(
                        ':spi' => $service_product_id
                    ),
                'order' => 'sort'
            ));
        

        //构造返回信息
        $ccs = $this->get_ccs_list($token);
        $result = array(
                'product' => array(
                        'description' => $service_product['description'],
                        'name' => $service_product['product_name'],
                        'price' => $service_product['cost'],
                        'total_inventory' => $service_product['total_inventory'],
                        'unit' => $service_product['unit'],
                        'subarea' => array(),
                        'case' => array(),
                        'color' => array(),
                        'style' => array()
                    ),
                'img_list' => array(),
                'psd_list' => array(),
                'subarea' => $ccs['subarea'],
                'case' => $ccs['case'],
                'color' => $ccs['color'],
                'style' => $ccs['style']
            );
        foreach ($ccs['subarea'] as $key => $value) {
            if($value['id'] == $service_product['subarea']){
                $result['product']['subarea'] = array('id' => $value['id'], 'name' => $value['name']);
            };
        };


        if(!empty($service_product_img)){
            $case = OrderShowImgCase::model()->findByPk($service_product_img[0]['order_show_img_case']);
            $color = OrderShowImgColor::model()->findByPk($service_product_img[0]['order_show_img_color']);
            $style = OrderShowImgStyle::model()->findByPk($service_product_img[0]['order_show_img_style']);
            if(!empty($case)){
                $item = array(
                        'id' => $case['id'],
                        'name' => $case['name']
                    );
                $result['product']['case'] = $item;
            };
            if(!empty($color)){
                $item = array(
                        'id' => $color['id'],
                        'name' => $color['name']
                    );
                $result['product']['color'] = $item;
            };
            if(!empty($style)){
                $item = array(
                        'id' => $style['id'],
                        'name' => $style['name']
                    );
                $result['product']['style'] = $item;
            };
        };
        
        foreach ($service_product_img as $key => $value) {
            $t = explode('/', $value['img_url']);
            if(isset($t[0])){
                if($t[0] == 'http:'){
                    $result['img_list'][] = $value['img_url'];
                }else{
                    $url = $this->add_string_to_url($value['img_url'], 'xs');
                    $result['img_list'][] = "http://file.cike360.com".$url;
                };
            };
            $result['psd_list'][] = $value['psd_url'];
        };

        echo json_encode($result);
    }

    public function actionGet_supplier_info()
    {
        //参数表
        $token = $_GET['token'];

        //info
        $staff = yii::app()->db->createCommand('select staff.id,staff.name,gender,avatar as pic_url,sp.id as province_id,province_name,spc.id as city_id,city_name '.
            ' from staff '.
            ' left join service_province_city spc on staff.city_id=spc.id '.
            ' left join service_province sp on spc.province_id=sp.id '.
            ' where staff.id='.$token);
        $staff = $staff->queryAll();

        $result = array(
                'info' => array(),
                'address' => $this->get_province_city()
            );
        if(!empty($staff)){
            $result['info'] = $staff[0];
        };
        echo json_encode($result);
    }

    public function get_province_city()
    {
        //查找所有province
        $province = yii::app()->db->createCommand('select * from service_province where id in '.
            ' (select province_id from service_province_city where id in '.
                ' (select city_id from staff where id in '.
                    ' (select staff_id from service_person where service_type in '.
                        ' (select supplier_type from order_show_area_subarea where father_area in (3,4,5,7)))))');
        $province = $province->queryAll();

        $result = array();
        foreach ($province as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['province_name'];
            //查找所有city
            $city_all = yii::app()->db->createCommand('select * from service_province_city where province_id='.$value['id'].' and id in '.
                ' (select city_id from staff where id in '.
                    ' (select staff_id from service_person where service_type in '.
                        ' (select supplier_type from order_show_area_subarea where father_area in (3,4,5,7)))) ');
            $item['city_list'] = $city_all->queryAll();
            $result[] = $item;
        };
        return $result;
    }

    public function actionUpdate_supplier_info()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $token = $post->token;
        $name = $post->name;
        $gender = $post->gender;
        $city_id = $post->city_id;

        $t = Staff::model()->updateByPk($token, array('name' => $name, 'gender' => $gender, 'city_id' => $city_id));

        echo json_encode(array('result' => $t));
    }

    public function actionUpdate_supplier_pic()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $token = $post->token;
        $pic_url = $post->pic_url;

        $t = Staff::model()->updateByPk($token, array('avatar' => $pic_url));

        echo json_encode(array('result' => $t));
    }

    public function actionIndex_front()
    {
        $this->render('index_front');
    }

    public function actionIndex_front_data()
    {
        //参数表
        $token = $_GET['token'];

        //预备信息
        $staff = Staff::model()->findByPk($token);

        //取订单
        $tt = yii::app()->db->createCommand("select o.id,o.order_name,o.order_date,o.designer_id,staff.name as designer_name,order_status,o.order_type ".
            " from `order` o ".
            " left join staff on designer_id=staff.id ".
            " where designer_id=".$token." or planner_id=".$token);
        $tt = $tt->queryAll();
        $order_data = $this->get_order_doing_and_done3($tt);

        //取套系
        $order_model =  yii::app()->db->createCommand("select * from order_model ".
            " where model_show=1 and is_menu=0 and account_id=".$staff['account_id'].' limit 0,3');
        $order_model =  $order_model->queryAll();
        $model_data = array();
        foreach ($order_model as $key => $value) {
            $url = "";
            $t = explode('/', $value['poster_img']);
            if(isset($t[0])){
                if($t[0] == "http:"){
                    $url = $value['poster_img'].'?x-oss-process=image/resize,m_lfit,h_300,w_300';
                }else if($t[0] == 'upload'){
                    $url = 'http://file.cike360.com/'.$this->add_string_to_url($value['poster_img'], 'xs');
                }else if($t[0] == ''){
                    $url = 'http://file.cike360.com'.$this->add_string_to_url($value['poster_img'], 'xs');
                };
            };
            $item = array();
            $item['id'] = $value['id'];
            $item['poster'] = $url;
            $item['name'] = $value['name'];
            $item['model_order'] = $value['model_order'];
            $item['img_amount'] = 0;
            $model_data[] = $item;
        };

        echo json_encode(array(
                'order_doing' => $order_data['doing'],
                'order_model' => $model_data
            ));
    }

    public function actionSales_list()
    {
        $this->render("sales_list");
    }

    public function actionGet_share_folder_list()
    {
        //参数表
        $type_id = $_GET['type_id'];
        $page = $_GET['page'];

        //预备信息

        //取type
        $type = yii::app()->db->createCommand('select * from library_share_folder_type');
        $type = $type->queryAll();

        //构造type_id
        if($_GET['type_id'] == ''){
            if(empty($type)){
                $type_id = 0;
            }else{
                $type_id = $type[0]['id'];
            };
        };

        //构造total_page
        $folder = yii::app()->db->createCommand('select Folder_ID,Folder_Name,name '.
            ' from library_staff_folder lsf '.
            ' left join staff s on lsf.Staff_ID=s.id where Folder_ID in '.
            '(select folder_id from library_share_folder where share_type='.$type_id.') ');
        $folder = $folder->queryAll();
        $total_page = ceil(count($folder)/9);

        //取folder
        $start = ($page-1)*9;
        $folder = yii::app()->db->createCommand('select Folder_ID,Folder_Name,name '.
            ' from library_staff_folder lsf '.
            ' left join staff s on lsf.Staff_ID=s.id where Folder_ID in '.
            ' (select folder_id from library_share_folder where share_type='.$type_id.') limit '.$start.',9');
        $folder = $folder->queryAll(); 
        $folder_data = array();
        foreach ($folder as $key1 => $value1) {
            $t = array();
            $t['id'] = $value1['Folder_ID'];
            $t['name'] = $value1['Folder_Name'];
            $t['owner_name'] = $value1['name'];
            $t['poster'] = '';
            $img = yii::app()->db->createCommand('select * from library_web_case_img where Img_ID in '.
                '(select Img_ID from library_folder_bind where Folder_ID='.$value1['Folder_ID'].') ');
            $img = $img->queryAll();
            if(!empty($img)){
                $m = explode('/', $img[0]['local_URL']);
                if(!empty($m)){
                    if($m[0] == 'http:'){
                        $t['poster'] = $img[0]['local_URL'].'?x-oss-process=image/resize,m_lfit,h_500,w_500';
                    }else{
                        $t['poster'] = 'http://file.cike360.com' . $img[0]['local_URL'];
                    };
                };
            };
            $folder_data[] = $t;
        };

        $result = array(
                'type' => $type,
                'folder' => $folder_data,
                'total_page' => $total_page,
                'cur_page' => $page
            );

        echo json_encode($result);
    }

    public function actionLibrary_search()
    {
        //参数表
        $key_words = $_GET['key_words'];
        $page = $_GET['page'];

        //预备信息
        $start = ($page-1)*60;

        //查询
        $img = yii::app()->db->createCommand('select * from library_web_case_img '.
            ' where Case_ID in (select Case_ID from library_web_case where Case_Name like "%'.$key_words.'%" or Case_Description like "%'.$key_words.'%")'.
            ' limit '.$start.',60');
        $img = $img->queryAll();

        //构造返回信息
        $result = array();
        foreach ($img as $key1 => $value1) {
            $t = explode('/', $value1['local_URL']);
            $item = array();
            $item['id'] = $value1['Img_ID'];
            $item['name'] = '';
            $item['company'] = '';
            if(isset($t[0])){
                if($t[0] == 'http:'){
                    $item['cover_img'] = $value1['local_URL'].'?x-oss-process=image/resize,m_lfit,h_500,w_500';
                    $item['cover_img_lg'] = $value1['local_URL'].'?x-oss-process=image/resize,m_lfit,h_1900,w_1900';
                }else{
                    $item['cover_img'] = "http://file.cike360.com".ltrim($value1['local_URL'], ".");
                    $item['cover_img_lg'] = "http://file.cike360.com".ltrim($value1['local_URL'], ".");
                };
            };
            $item['page'] = $_GET['page'];
            $item['case_name'] = '';
            $item['case_description'] = '';
            $item['case_poster'] = '';
            $result[] = $item;
        };

        echo json_encode($result);
    }

	public function actionLogin_cat()
    {
        $this->render('login_cat');
    }



	public function actionRegist_cat()
    {
        $this->render('regist_cat');
    }

    public function actionIndex_supplier()
    {
        $this->render('index_supplier');
    }

    public function actionBasicInfo()
    {
        $this->render('basicInfo');
    }

    public function actionBasicInfo_list2()
    {
        $this->render('basicInfo_list2');
    }

    public function actionGoods()
    {
        $this->render('goods');
    }

    public function actionGoods_list2()
    {
        $this->render('goods_list2');
    }

    public function actionGoods_list3()
    {
        $this->render('goods_list3');
    }

    public function actionGoodsAdd()
    {
        $this->render('goodsAdd');
    }

    public function actionGoodsAddCase()
    {
        $this->render('goodsAddCase');
    }

    public function actionGoodsAddMake()
    {
        $this->render('goodsAddMake');
    }
    public function actionBuy_account()
    {
        $this->render('buy_account');
    }

    public function actionService_product_del()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $service_product_id = $post->service_product_id;

        $t1 = ServiceProduct::model()->updateByPk($service_product_id, array('product_show' => 0));
        $t2 = SupplierProduct::model()->updateAll(array('product_show' => 0), 'service_product_id=:service_product_id', array(':service_product_id' => $service_product_id));

        $result = array(
                'service' => $t1,
                'supplier' => $t2
            );
        echo json_encode($result);
    }

    public function actionEdit_service_product()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $service_product_id = $post->service_product_id;
        $name = $post->name;
        $price = $post->price;
        $unit = $post->unit;
        $cost = $post->cost;
        $inventory = $post->inventory;
        $description = $post->description;

        //预备信息
        $img = ServiceProductImg::model()->find(array(
                'condition' => 'service_product_id=:service_product_id',
                'params' => array(
                        ':service_product_id' => $service_product_id
                    )
            ));

        $t1 = ServiceProduct::model()->updateByPk($service_product_id, array(
                'product_name' => $name,
                'price' => $price,
                'unit' => $unit,
                'cost' => $cost,
                'inventory' => $inventory,
                'description' => $description
            ));
        $t2 = SupplierProduct::moder()->updateAll(array(
                'name' => $name,
                'unit_price' => $price,
                'unit' => $unit,
                'cost' => $cost,
                'description' => $description
            ));

        $result = array(
                'service' => $t1,
                'supplier' => $t2
            );
        echo json_encode($result);
    }

    public function actionCase_color_insert()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $name = $post->name;
        $token = $post->token;

        //预备信息
        $staff = Staff::model()->findByPk($token);

        $admin = new CaseInfoColor;
        $admin->account_id = $staff['account_id'];
        $admin->name = $name;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array(
                'id' => $t
            ));
    }

    public function actionCase_style_insert()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $name = $post->name;
        $token = $post->token;

        //预备信息
        $staff = Staff::model()->findByPk($token);

        $admin = new CaseInfoStyle;
        $admin->account_id = $staff['account_id'];
        $admin->name = $name;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array(
                'id' => $t
            ));
    }

    public function actionCase_space_insert()
    {
        //参数表
        $post = json_decode(file_get_contents('php://input'));
        $name = $post->name;
        $token = $post->token;

        //预备信息
        $staff = Staff::model()->findByPk($token);

        $admin = new CaseInfoSpace;
        $admin->account_id = $staff['account_id'];
        $admin->city_id = $staff['city_id'];
        $admin->name = $name;
        $admin->update_time = date('y-m-d h:i:s',time());
        $admin->save();
        $t = $admin->attributes['id'];

        echo json_encode(array(
                'id' => $t
            ));
    }


     public function actionWeixin()
    {   
        $url ='http://crm.cike360.com/portal/index.php?r=dailyReport/Get_weixin_open_data&code=8888';
        $contentStr = file_get_contents($url);
        $contentArr = json_decode($contentStr);
        $appId = $contentArr->id;
        $redirectUrl = 'http://dev.msch.com/portal/index.php?r=background/weixin_get_code';//这里的域名需要和开发平台注册的相同
        $redirectUrl = urlencode($redirectUrl);
        $state = rand();
        $urlCOde = 'https://open.weixin.qq.com/connect/qrconnect?appid='.$appId.'&redirect_uri='.$redirectUrl.'&response_type=code&scope=snsapi_login&state='.$state.'#wechat_redirect';
        Header("HTTP/1.1 303 See Other");
        Header("Location: $urlCOde");
        exit;
    }

    public function actionweixin_get_code(){
        $code = $_GET['code'];
        if(!empty($code)){
            //用户未登陆
        }else{
            $url ='http://crm.cike360.com/portal/index.php?r=dailyReport/Get_weixin_open_data&code=8888';
            $contentStr = file_get_contents($url);
            $contentArr = json_decode($contentStr);
            $appId = $contentArr->id;
            $secret = $contentArr->secret;
            $tokenUrl='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
            $token = file_get_contents($tokenUrl);
            $tokenObj = json_decode($token);
            $accessToken = $tokenObj->access_token;
            $openId = $tokenObj->openid;
            $userUrl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$accessToken.'&openid='.$openId;
            $userStr = file_get_contents($userUrl);
            $userObj = json_decode($userStr);
            var_dump($userObj);die();
        }
        
    }















}

<?php

/**
 * Class StaffForm
 * Staff info
 */
class LibraryForm extends InitForm
{
    public function get_my_folders($token)
    {
        //取folder
        $folder = yii::app()->db->createCommand("select folder.Folder_ID,folder.Folder_Name,tab.id as tab_id,tab.name as tab_name ".
            " from library_staff_folder folder left join library_staff_folder_tab tab on folder.tab_id=tab.id ".
            " where folder.Staff_ID=".$token.
            " order by folder.Folder_ID DESC");
        $folder = $folder->queryAll();

        $folder_data = array();
        foreach ($folder as $key => $value) {
            $result =  yii::app()->db->createCommand("select * from library_web_case_img where Img_ID in ".
                " (select Img_ID from library_folder_bind where Folder_ID=".$value['Folder_ID'].") limit 0,4");
            $result =  $result->queryAll();
            $item = array();
            $item['id'] = $value['Folder_ID'];
            $item['name'] = $value['Folder_Name'];
            $item['tab_name'] = $value['tab_name'];
            $item['tab_id'] = $value['tab_id'];
            if(empty($result)){
                $item['poster'] = '';
            }else{
                $item['poster'] = 'http://file.cike360.com'.ltrim($result[0]['local_URL'], '.');
                $t = explode('/', $result[0]['local_URL']);
                if(isset($t[0])){
                    if($t[0] == 'http:'){
                        $item['poster'] = $result[0]['local_URL'];
                    };
                };
            };
            $item['img_list'] = array();
            foreach ($result as $key1 => $value1) {
                if($key1 == 1 || $key1 == 2 || $key1 == 3){
                    $tem = 'http://file.cike360.com'.ltrim($value1['local_URL'], '.');
                    $t = explode('/', $value1['local_URL']);
                    if(isset($t[0])){
                        if($t[0] == 'http:'){
                            $tem = $value1['local_URL'];
                        };
                    };
                    $item['img_list'][] = $tem;
                };
            };

            $folder_data[] = $item;
        };

        //取分类
        $tab = yii::app()->db->createCommand("select * from library_staff_folder_tab where staff_id=".$token);
        $tab = $tab->queryAll();

        $result = array(
                'folder_data' => $folder_data,
                'folder_tab' => $tab
            );

        return json_encode($result);
    }

    






































}

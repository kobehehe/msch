<?php

class LibraryStaffFolder extends InitActiveRecord
{
	public function tableName()
	{
		return 'library_staff_folder';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('Folder_ID',$this->Folder_ID);
		$criteria->compare('Folder_Name',$this->Folder_Name);
		$criteria->compare('tab_id',$this->tab_id);
		$criteria->compare('Staff_ID',$this->Staff_ID);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

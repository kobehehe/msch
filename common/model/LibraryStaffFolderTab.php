<?php

class LibraryStaffFolderTab extends InitActiveRecord
{
	public function tableName()
	{
		return 'library_staff_folder_tab';
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('name',$this->name);
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

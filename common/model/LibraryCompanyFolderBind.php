<?php

class LibraryCompanyFolderBind extends InitActiveRecord
{
	public function tableName()
	{
		return 'library_company_folder_bind';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('folder_id',$this->folder_id);
		$criteria->compare('img_id',$this->img_id);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

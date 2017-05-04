<?php

class LibraryShareFolder extends InitActiveRecord
{
	public function tableName()
	{
		return 'library_share_folder';
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('folder_id',$this->folder_id);
		$criteria->compare('share_type',$this->share_type);
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

<?php

class AppVersion extends InitActiveRecord
{
	public function tableName()
	{
		return 'app_version';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('app_name',$this->app_name);
		$criteria->compare('app_no',$this->app_no);
		$criteria->compare('version',$this->version);
		$criteria->compare('version_file',$this->version_file);
		$criteria->compare('description',$this->description);
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

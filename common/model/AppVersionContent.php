<?php

class AppVersionContent extends InitActiveRecord
{
	public function tableName()
	{
		return 'app_version_content';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('app_no',$this->app_no);
		$criteria->compare('app_version',$this->app_version);
		$criteria->compare('content_no',$this->content_no);
		$criteria->compare('version_content',$this->version_content);
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

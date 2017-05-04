<?php

class ServicePersonImg extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_person_img';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('service_person_id',$this->service_person_id);
		$criteria->compare('img_url',$this->img_url);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('img_show',$this->img_show);
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

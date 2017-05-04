<?php

class ServiceProductVideo extends InitActiveRecord
{
	public function tableName()
	{
		return 'service_product_video';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('service_product_id',$this->service_product_id);
		$criteria->compare('video_url',$this->video_url);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('show',$this->show);
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

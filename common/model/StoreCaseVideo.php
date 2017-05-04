<?php

class StoreCaseVideo extends InitActiveRecord
{
	public function tableName()
	{
		return 'store_case_video';
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('store_case_id',$this->store_case_id);
		$criteria->compare('video_url',$this->video_url);
		$criteria->compare('is_example',$this->is_example,true);
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

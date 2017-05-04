<?php

class AppMessage extends InitActiveRecord
{
	public function tableName()
	{
		return 'app_message';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('app_id',$this->app_id);
		$criteria->compare('post_type',$this->post_type);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('hotel_id',$this->hotel_id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('content_type',$this->content_type);
		$criteria->compare('report_id',$this->report_id);
		$criteria->compare('title',$this->title);
		$criteria->compare('sub_title',$this->sub_title);
		$criteria->compare('content',$this->content);
		$criteria->compare('unread',$this->unread);
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

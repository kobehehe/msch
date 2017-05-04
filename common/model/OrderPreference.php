<?php
class OrderPreference extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_preference';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('img_url',$this->img_url);
		$criteria->compare('video_url',$this->video_url);
		$criteria->compare('data_id',$this->data_id);
		$criteria->compare('data_source',$this->data_source);
		$criteria->compare('data_type',$this->data_type);
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

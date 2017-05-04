<?php
class OrderTemplate extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_template';
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('staff_id',$this->staff_id,true);
		$criteria->compare('name',$this->name);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('free',$this->free);
		$criteria->compare('price',$this->price);
		$criteria->compare('poster_url',$this->poster_url);
		$criteria->compare('view_time',$this->view_time);
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

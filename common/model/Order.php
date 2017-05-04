<?php
class Order extends InitActiveRecord
{
	public function tableName()
	{
		return 'order';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('planner_id',$this->planner_id);
		$criteria->compare('designer_id',$this->designer_id,true);
		$criteria->compare('adder_id',$this->adder_id,true);
		$criteria->compare('staff_hotel_id',$this->staff_hotel_id);
		$criteria->compare('template_id',$this->template_id,true);
		$criteria->compare('order_name',$this->order_name,true);
		$criteria->compare('order_place',$this->order_place,true);
		$criteria->compare('order_type',$this->order_type,true);
		$criteria->compare('order_date',$this->order_date,true);
		$criteria->compare('order_time',$this->order_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('order_status',$this->order_status);
		$criteria->compare('guest_amount',$this->guest_amount);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('other_discount',$this->other_discount,true);
		$criteria->compare('feast_discount',$this->feast_discount,true);
		$criteria->compare('except_table',$this->except_table,true);
		$criteria->compare('feast_deposit',$this->feast_deposit);
		$criteria->compare('medium_term',$this->medium_term);
		$criteria->compare('final_payments',$this->final_payments);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

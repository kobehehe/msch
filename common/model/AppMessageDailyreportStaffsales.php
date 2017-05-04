<?php

class AppMessageDailyreportStaffsales extends InitActiveRecord
{
	public function tableName()
	{
		return 'app_message_dailyreport_staff_sales';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('report_id',$this->report_id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('staff_name',$this->staff_name);
		$criteria->compare('sales',$this->sales);
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

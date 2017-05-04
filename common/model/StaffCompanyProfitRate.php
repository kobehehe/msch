<?php

class StaffCompanyProfitRate extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_company_profit_rate';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('area_type',$this->area_type);
		$criteria->compare('profit_rate',$this->profit_rate);
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

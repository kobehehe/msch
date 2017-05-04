<?php
class StaffCompanyVipPurchase extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_company_vip_purchase';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('payer_id',$this->payer_id);
		$criteria->compare('staff_amount',$this->staff_amount);
		$criteria->compare('month_amount',$this->month_amount);
		$criteria->compare('total_price',$this->total_price);
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

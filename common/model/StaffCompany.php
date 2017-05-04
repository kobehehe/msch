<?php
class StaffCompany extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff_company';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_total_max',$this->staff_total_max);
		$criteria->compare('type',$this->type);
		$criteria->compare('company_rename',$this->company_rename);
		$criteria->compare('due_date',$this->due_date);
		$criteria->compare('name',$this->name);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('sign_time',$this->sign_time);
		$criteria->compare('sign_out_time',$this->sign_out_time);
		$criteria->compare('sign_place',$this->sign_place);
		$criteria->compare('place',$this->place);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('corpid',$this->update_time);
		$criteria->compare('corpsecreat',$this->update_time);
		$criteria->compare('first_service_team',$this->first_service_team);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

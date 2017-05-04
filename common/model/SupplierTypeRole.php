<?php

class SupplierTypeRole extends InitActiveRecord
{
	public function tableName()
	{
		return 'supplier_type_role';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		$criteria->compare('is_shopping',$this->is_shopping);
		$criteria->compare('sort',$this->sort);
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

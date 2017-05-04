<?php

class SupplierTypeViewPage extends InitActiveRecord
{
	public function tableName()
	{
		return 'supplier_type_view_page';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('supplier_type',$this->supplier_type,true);
		$criteria->compare('page',$this->page,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

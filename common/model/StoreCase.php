<?php
class StoreCase extends InitActiveRecord
{
	public function tableName()
	{
		return 'store_case';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('service_product_id',$this->service_product_id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('recommend',$this->recommend);
		$criteria->compare('name',$this->name);
		$criteria->compare('description',$this->description);
		$criteria->compare('price',$this->price);
		$criteria->compare('wedding_price',$this->wedding_price);
		$criteria->compare('case_show',$this->case_show);
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

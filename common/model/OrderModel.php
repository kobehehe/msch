<?php
class OrderModel extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_model';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('is_menu',$this->is_empty);
		$criteria->compare('is_empty',$this->is_empty);
		$criteria->compare('model_order',$this->model_order);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('poster_img',$this->poster_img);
		$criteria->compare('model_show',$this->model_show);
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

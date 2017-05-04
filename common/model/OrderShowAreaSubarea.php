<?php

/**
 * This is the model class for table "supplier_type".
 *
 * The followings are the available columns in table 'supplier_type':
 * @property integer $id
 * @property integer $account_id
 * @property string $name
 * @property string $role
 * @property string $update_time
 * @property string $avatar
 */
class OrderShowAreaSubarea extends InitActiveRecord
{

	public function tableName()
	{
		return 'order_show_area_subarea';
	}

	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		$criteria->compare('eng_name',$this->eng_name);
		$criteria->compare('poster',$this->poster);
		$criteria->compare('decoration_tap',$this->decoration_tap);
		$criteria->compare('lss_tap',$this->decoration_tap);
		$criteria->compare('father_area',$this->father_area,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('sort',$this->sort,true);
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

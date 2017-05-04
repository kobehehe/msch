<?php

class CaseInfo extends InitActiveRecord
{
	public function tableName()
	{
		return 'case_info';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('CI_ID',$this->CI_ID);
		$criteria->compare('CI_Name',$this->CI_Name);
		$criteria->compare('style',$this->CI_Place,true);
		$criteria->compare('space',$this->CI_Place,true);
		$criteria->compare('color',$this->CI_Place,true);
		$criteria->compare('CI_Place',$this->CI_Place,true);
		$criteria->compare('CI_Pic',$this->CI_Pic,true);
		$criteria->compare('CI_Time',$this->CI_Time,true);
		$criteria->compare('CI_CreateTime',$this->CI_CreateTime,true);
		$criteria->compare('CI_Sort',$this->CI_Sort,true);
		$criteria->compare('CI_Show',$this->CI_Show,true);
		$criteria->compare('CI_Remarks',$this->CI_Remarks,true);
		$criteria->compare('CI_Type',$this->CI_Type,true);
		$criteria->compare('CT_ID',$this->CT_ID,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

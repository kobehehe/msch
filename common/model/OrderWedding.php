<?php
class OrderWedding extends InitActiveRecord
{
	public function tableName()
	{
		return 'order_wedding';
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('groom_name',$this->groom_name);
		$criteria->compare('groom_phone',$this->groom_phone);
		$criteria->compare('groom_wechat',$this->groom_wechat);
		$criteria->compare('groom_qq',$this->groom_qq);
		$criteria->compare('bride_name',$this->bride_name);
		$criteria->compare('bride_phone',$this->bride_phone);	
		$criteria->compare('bride_wechat',$this->bride_wechat);	
		$criteria->compare('bride_qq',$this->bride_qq);		
		$criteria->compare('contact_name',$this->bride_qq);		
		$criteria->compare('contact_phone',$this->bride_qq);		
		$criteria->compare('guest_amount',$this->guest_amount);		
		$criteria->compare('wedding_style',$this->wedding_style);		
		$criteria->compare('remark',$this->remark);		
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

<?php

class Staff extends InitActiveRecord
{
	public function tableName()
	{
		return 'staff';
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('avatar_media_id',$this->avatar_media_id,true);
		$criteria->compare('job_title',$this->job_title,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('wechat_openid',$this->wechat_openid,true);
		$criteria->compare('wechat_unionid',$this->wechat_unionid,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('department_list',$this->department_list,true);
		$criteria->compare('hotel_list',$this->hotel_list,true);
		$criteria->compare('extattr',$this->extattr,true);
		$criteria->compare('target',$this->target,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

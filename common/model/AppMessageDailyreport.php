<?php
class AppMessageDailyreport extends InitActiveRecord
{
	public function tableName()
	{
		return 'app_message_dailyreport';
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('today_indoor',$this->today_indoor,true);
		$criteria->compare('today_follow',$this->today_follow,true);
		$criteria->compare('today_carry_out',$this->today_carry_out,true);
		$criteria->compare('today_order_win',$this->today_order_win,true);
		$criteria->compare('today_order_win',$this->today_order_win,true);
		$criteria->compare('today_cash',$this->today_cash,true);
		$criteria->compare('year_sales',$this->year_sales,true);
		$criteria->compare('year_case',$this->year_case,true);
		$criteria->compare('year_profit',$this->year_profit,true);
		$criteria->compare('order_pool',$this->order_pool,true);
		$criteria->compare('order_doing',$this->order_doing,true);
		$criteria->compare('order_win',$this->order_win,true);
		$criteria->compare('order_lose',$this->order_lose,true);
		$criteria->compare('hotel_id',$this->hotel_id,true);
		$criteria->compare('report_date',$this->report_date,true);
		$criteria->compare('update_time',$this->update_time,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

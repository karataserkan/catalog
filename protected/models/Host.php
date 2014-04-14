<?php

/**
 * This is the model class for table "host".
 *
 * The followings are the available columns in table 'host':
 * @property string $id
 * @property string $address
 * @property integer $port
 * @property string $key1
 * @property string $key2
 *
 * The followings are the available model relations:
 * @property Content[] $contents
 */
class Host extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'host';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, address, port, key1, key2', 'required'),
			array('port', 'numerical', 'integerOnly'=>true),
			array('id, address', 'length', 'max'=>120),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, address, port, key1, key2', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'contents' => array(self::MANY_MANY, 'Content', 'content_host(host_id, content_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'address' => 'Address',
			'port' => 'Port',
			'key1' => 'Key1',
			'key2' => 'Key2',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('port',$this->port);
		$criteria->compare('key1',$this->key1,true);
		$criteria->compare('key2',$this->key2,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Host the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

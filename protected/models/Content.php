<?php

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property string $contentId
 * @property string $organisationId
 * @property string $contentType
 * @property string $contentTitle
 * @property string $contentExplanation
 * @property string $contentIsForSale
 * @property double $contentPrice
 * @property integer $contentPriceCurrencyCode
 * @property string $contentReaderGroup
 * @property string $created
 * @property string $organisationName
 * @property string $author
 *
 * The followings are the available model relations:
 * @property Categories[] $categories
 * @property Host[] $hosts
 */
class Content extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contentId, organisationId, contentType, contentTitle, contentExplanation, contentIsForSale, contentPrice, contentPriceCurrencyCode, created, organisationName', 'required'),
			array('contentPriceCurrencyCode', 'numerical', 'integerOnly'=>true),
			array('contentPrice', 'numerical'),
			array('contentId, contentReaderGroup', 'length', 'max'=>80),
			array('organisationId', 'length', 'max'=>100),
			array('contentType, contentIsForSale', 'length', 'max'=>4),
			array('organisationName', 'length', 'max'=>1000),
			array('author', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('contentId, organisationId, contentType, contentTitle, contentExplanation, contentIsForSale, contentPrice, contentPriceCurrencyCode, contentReaderGroup, created, organisationName, author', 'safe', 'on'=>'search'),
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
			'categories' => array(self::MANY_MANY, 'Categories', 'content_categories(content_id, category_id)'),
			'hosts' => array(self::MANY_MANY, 'Host', 'content_host(content_id, host_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'contentId' => 'Content',
			'organisationId' => 'Organisation',
			'contentType' => 'Content Type',
			'contentTitle' => 'Content Title',
			'contentExplanation' => 'Content Explanation',
			'contentIsForSale' => 'Content Is For Sale',
			'contentPrice' => 'Content Price',
			'contentPriceCurrencyCode' => 'Content Price Currency Code',
			'contentReaderGroup' => 'Content Reader Group',
			'created' => 'Created',
			'organisationName' => 'Organisation Name',
			'author' => 'Author',
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

		$criteria->compare('contentId',$this->contentId,true);
		$criteria->compare('organisationId',$this->organisationId,true);
		$criteria->compare('contentType',$this->contentType,true);
		$criteria->compare('contentTitle',$this->contentTitle,true);
		$criteria->compare('contentExplanation',$this->contentExplanation,true);
		$criteria->compare('contentIsForSale',$this->contentIsForSale,true);
		$criteria->compare('contentPrice',$this->contentPrice);
		$criteria->compare('contentPriceCurrencyCode',$this->contentPriceCurrencyCode);
		$criteria->compare('contentReaderGroup',$this->contentReaderGroup,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('organisationName',$this->organisationName,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Content the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

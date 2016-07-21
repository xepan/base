<?php
namespace xepan\base;

class View_Activity extends \View{

	function init(){
	parent::init();

	$document_array = [	
						"Quotation" => "xepan_commerce_quotationdetail",
						"SalesOrder" => "xepan_commerce_salesorderdetail",
						"SalesInvoice" => "xepan_commerce_salesinvoicedetail",
						"PurchaseOrder" => "xepan_commerce_purchaseorderdetail",
						"PurchaseInvoice" => "xepan_commerce_purchaseinvoicedetail",
						"Item" => "xepan_commerce_itemdetail",
						"Post" => "xepan_hr_post",
						"Department" => "xepan_hr_department",
					];

	$contact_array = [
						"Lead" => "xepan_marketing_leaddetails",
						"Customer" => "xepan_commerce_customerdetail",
						"Supplier" => "xepan_commerce_supplierdetail",
						"Employee" => "xepan_hr_employeedetail"
					];


	$activity_model=$this->add('xepan\base\Model_Activity');
	$activity_model->addExpression('contact_type',$activity_model->refSQL('related_contact_id')->fieldQuery('type'));
	$activity_model->addExpression('document_type',$activity_model->refSQL('related_document_id')->fieldQuery('type'));
	
	$grid = $this->add('xepan\base\Grid',null,null,['view/activity/activities']);
	$grid->setModel($activity_model);

	}
}

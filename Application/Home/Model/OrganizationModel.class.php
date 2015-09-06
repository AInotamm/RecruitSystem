<?php
namespace Home\Model;
use Think\Model;
class OrganizationModel extends Model {
	private $_organization;
	public function setOrg(){
		$this->_organization = M('organization');
	}

	public function checkOrg($org_id){
		$this->setOrg();
		$condition['id'] = $org_id;
		$org = $this->_organization->where($condition)->find();
		return $org;
	}

}